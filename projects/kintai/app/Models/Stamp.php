<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\StampType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stamp extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * 出力可能なカラム
     *
     * @var list<string>
     */
    protected $fillable = [
        'stamp_type',
        'stamp_time',
    ];

        /**
     * 出力に使用しないカラム
     *
     * @var list<string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * それぞれのカラムのキャスト
     *
     * @return array<string> string>
     */
    protected function casts(): array
    {
        return [
            'stamp_type' => StampType::class,
            'stamp_time' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * 最新の打刻記録を取得
     */
    public static function getLatestStamp()
    {
        return self::latest()->first();
    }
}
