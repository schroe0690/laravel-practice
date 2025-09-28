<?php

use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\StampController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//  --- 打刻画面 ---
Route::get('/stamp-view', [StampController::class, 'show']);    // 表示
Route::post('/stamp', [StampController::class, 'stamp']);       // 打刻

//  --- 出勤簿 ---
Route::get('/attendance-record', [AttendanceRecordController::class, 'show']);  // 表示