<?php

namespace App\Repositories;

use App\Models\Stamp;
use App\Enums\StampType;
use Illuminate\Database\Eloquent\Collection;

class StampRepository
{
    /**
     * 最後の打刻レコードを取得
     */
    public function getLastStamp(): ?Stamp
    {
        return Stamp::orderBy('id', 'desc')->first();
    }

    /**
     * 最後の打刻タイプを取得
     */
    public function getLastStampType(): ?StampType
    {
        $lastStamp = $this->getLastStamp();
        return $lastStamp ? $lastStamp->stamp_type : null;
    }

    /**
     * 次に打刻すべきタイプを決定
     */
    public function getNextStampType(): StampType
    {
        $lastType = $this->getLastStampType();

        switch ($lastType) {
            case null:
            case StampType::CLOCK_OUT:
                return StampType::CLOCK_IN;
            case StampType::CLOCK_IN:
                return StampType::BREAK_IN;
            case StampType::BREAK_IN:
                return StampType::BREAK_OUT;
            case StampType::BREAK_OUT:
                return StampType::CLOCK_OUT;
            default:
                return StampType::CLOCK_IN;
        }
    }

    /**
     * 打刻を作成
     */
    public function createStamp(StampType $stampType): Stamp
    {
        return Stamp::create([
            'stamp_type' => $stampType,
            'stamp_time' => now(),
        ]);
    }

    /**
     * 指定月の打刻履歴を取得
     */
    public function getStampsByMonth(int $year, int $month): Collection
    {
        return Stamp::whereYear('stamp_time', $year)
            ->whereMonth('stamp_time', $month)
            ->orderBy('stamp_time', 'asc')
            ->get();
    }
}
