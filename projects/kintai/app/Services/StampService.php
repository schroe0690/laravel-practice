<?php

namespace App\Services;

use App\Models\Stamp;
use App\Enums\StampType;
use App\Repositories\StampRepository;

class StampService
{
    protected StampRepository $stampRepository;

    public function __construct(StampRepository $stampRepository)
    {
        $this->stampRepository = $stampRepository;
    }

    /**
     * 最後の打刻を取得する
     */
    public function getLastStamp(): ?Stamp
    {
        // 最後の打刻を取得
        return $this->stampRepository->getLastStamp();
    }

    /**
     * 打刻を実行する
     */
    public function executeStamp(): Stamp
    {
        // 次の打刻タイプを取得
        $nextStampType = $this->stampRepository->getNextStampType();

        // 打刻を実行
        return $this->stampRepository->createStamp($nextStampType);
    }

    /**
     * 打刻(出勤)を実行する
     */
    public function executeStampClockIn(): Stamp
    {
        // 打刻(出勤)を実行
        return $this->stampRepository->createStamp(StampType::CLOCK_IN);
    }
}
