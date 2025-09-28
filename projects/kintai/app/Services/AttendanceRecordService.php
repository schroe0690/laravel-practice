<?php

namespace App\Services;

use App\Repositories\StampRepository;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRecordService
{
    protected StampRepository $stampRepository;

    public function __construct(StampRepository $stampRepository)
    {
        $this->stampRepository = $stampRepository;
    }

    /**
     * 指定月の打刻履歴を取得する
     */
    public function getStampHistoryByMonth(int $year, int $month): Collection
    {
        // 指定月の打刻履歴を取得
        return $this->stampRepository->getStampsByMonth($year, $month);
    }
}
