<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stamps', function (Blueprint $table) {
            $table->id();
            $table->enum('stamp_status', [  // 打刻タイプ
                'work_start',   // 出勤
                'break_start',  // 休憩開始
                'break_end',    // 休憩終了
                'work_end',     // 退勤
            ])->default('work_start');
            $table->timestamp('created_at')->useCurrent()->nullable();  // 作成日時
            $table->timestamp('updated_at')->useCurrent()->nullable();  // 更新日時
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamps');
    }
};
