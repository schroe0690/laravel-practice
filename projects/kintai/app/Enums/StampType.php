<?php

namespace App\Enums;

enum StampType: string
{
    case CLOCK_IN = 'clock_in';
    case BREAK_IN = 'break_in';
    case BREAK_OUT = 'break_out';
    case CLOCK_OUT = 'clock_out';

    /**
     * 次に行う行動を取得
     */
    public function actionLabel(): string
    {
        return match($this) {
            self::CLOCK_IN => '休憩開始',
            self::BREAK_IN => '休憩終了',
            self::BREAK_OUT => '退勤',
            self::CLOCK_OUT => '出勤',
        };
    }

    /**
     * 現在の状態を取得
     */
    public function statusLabel(): string
    {
        return match($this) {
            self::CLOCK_IN => '勤務中（休憩前）',
            self::BREAK_IN => '休憩中',
            self::BREAK_OUT => '勤務中（休憩後）',
            self::CLOCK_OUT => '退勤済',
        };
    }
}
