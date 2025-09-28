<?php

namespace App\Http\Controllers;

use App\Services\AttendanceRecordService;
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
class AttendanceRecordController extends Controller
{
    protected AttendanceRecordService $attendanceRecordService;

    public function __construct(AttendanceRecordService $attendanceRecordService)
    {
        $this->attendanceRecordService = $attendanceRecordService;
    }

    /**
     * 出勤簿を表示する
     */
    public function show()
    {
        // 今月の打刻履歴を取得
        $now = now();
        $stampHistoryThisMonth = $this->attendanceRecordService->getStampHistoryByMonth($now->year, $now->month);
        
        return view('attendance_record', [
            'stampHistoryOneMonth' => $stampHistoryThisMonth,
        ]);
    }

}
