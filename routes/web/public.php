<?php

// Новости
use App\Http\Controllers\Api\FnsController;
use App\Http\Controllers\Cabinet\NewsController;
use App\Http\Controllers\Cabinet\PageController;
use App\Http\Controllers\Cabinet\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/news', [NewsController::class, 'all'])->name('news');
Route::get('/news/{alias}', static function ($alias) {
    return (new NewsController())->getPost($alias);
})->name('post');

// Статические страницы
Route::get('/page/{alias}', static function ($alias) {
    return (new PageController())->getPage($alias);
})->name('pages');

Route::get('pages', [PageController::class, 'list'])->name('static_pages');

// Контакты
Route::get('/contacts', static function () {
    return (new PageController())->getPage('kontakty');
})->name('contacts');

// Техническая поддержка
Route::get('/support', function () {
    return (new PageController())->getPage('podderzka');
})->name('support');

// Поиск на сайте
Route::post('/search', [SearchController::class, 'view'])->name('search');

Route::post('api/getInn', function (Request $request) {
    return (new FnsController())->getInn($request);
});
