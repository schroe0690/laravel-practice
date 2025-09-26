<?php

use App\Http\Controllers\StampController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- 打刻画面 ---
// 表示
Route::get('/show', [StampController::class, 'show']);
