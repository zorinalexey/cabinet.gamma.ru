<?php

// Главная страница
use App\Http\Controllers\Cabinet\EsiaController;
use App\Http\Controllers\Cabinet\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'login'])->name('login');

// Двухфакторная авторизация по СМС
Route::post('/getCode', [LoginController::class, 'getCode'])->name('get_aut_code');
Route::post('/auth', [LoginController::class, 'auth'])->name('auth');

// Авторизация через Госуслуги
Route::any('/esia/auth', [EsiaController::class, 'auth'])->name('esia_auth');
Route::any('/esia/login', [EsiaController::class, 'login'])->name('esia_login');
