<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stamp extends Authenticatable
{
    /**
     * このモデルで使用する
     *
     * @var string
     */
    protected $table = 'stamps';

    /**
     * 更新を許可するカラムを指定
     *
     * @var list<string>
     */
    protected $fillable = [
        'stamp_status',
        'created_at',
        'updated_at',
    ];

    /**
     * 出力時に非表示とするカラムを指定
     *
     * @var list<string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
