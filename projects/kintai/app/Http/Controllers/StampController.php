<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StampService;

/**
 * 勤怠管理システムのタイムスタンプ機能を管理するコントローラー
 *
 * このクラスは出勤・退勤の打刻機能と、
 * 打刻画面の表示を担当します。
 *
 * @package App\Http\Controllers
 * @author Your Name <your.email@example.com>
 * @since 1.0.0
 */
class StampController extends Controller
{
    protected StampService $stampService;

    public function __construct(StampService $stampService)
    {
        $this->stampService = $stampService;
    }

    /**
     * 打刻画面を表示する
     */
    public function show()
    {
        // 最後の打刻を取得
        $lastStamp = $this->stampService->getLastStamp();

        // 指定のカラムを取得
        $stampType = $lastStamp?->stamp_type;        // stamp_typeカラム
        $stampTime = $lastStamp?->stamp_time;        // stamp_timeカラム

        return view('stamp', [
            'last_stamp_type' => $stampType,
            'last_stamp_time' => $stampTime,
        ]);
    }

    /**
     * 打刻処理を実行する
     */
    public function stamp(Request $request)
    {
        // 日付を跨いだか
        $acrossDates = $request->input('across_dates', false);

        // 実行する打刻方法を選択
        if ($acrossDates) {
            // 日付を跨いでいれば打刻(出勤)を実行
            $stamp = $this->stampService->executeStampClockIn();
        } else {
            // 通常の打刻を実行
            $stamp = $this->stampService->executeStamp();
        }

        return redirect()->back()->with('success', '打刻が完了しました。');
    }
}
