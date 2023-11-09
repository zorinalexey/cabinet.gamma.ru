<?php

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MyProfileController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Api\OmittedController as ApiOmittedController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Cabinet\LoginController;
use App\Http\Middleware\Guest;
use App\Http\Middleware\NoLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('set/pass', [AdminLoginController::class, 'setNewPassword'])->middleware('web')->name('admin_new_pass');

Route::middleware('web')->group(static function(){
    // Выход из приложения
    Route::get('/logout', [LoginController::class, 'logOut'])->name('logout');

    // Открытые гостевые роуты
    Route::middleware(Guest::class)->group(static function(){
        require_once __DIR__ .'/web/public.php';

        // Роуты авторизации пользователя в личном кабинете
        Route::middleware(NoLogin::class)->group(static function(){
            require_once __DIR__ .'/web/cabinet/auth.php';
        });
    });

    // Админ панель
    Route::prefix('admin')->name('admin.')->group(static function(){

        // Роут входа администратора
        Route::get('login', [AdminLoginController::class, 'login'])->name('login');
        // Роут авторизации администратора
        Route::post('auth', [AdminLoginController::class, 'auth'])->name('auth');

        // Роуты для авторизованного администратора
        Route::middleware(['auth', 'admin'])->group(static function () {
            Route::get('main', [MainController::class, 'index'])->name('main');
            Route::get('my/profile/settings', [MyProfileController::class, 'settings'])->name('my_profile_settings');

            require_once __DIR__ . '/web/admin/omitted.php';
            require_once __DIR__ . '/web/admin/users.php';
            require_once __DIR__ . '/web/admin/funds.php';
            require_once __DIR__ . '/web/admin/document.php';
            require_once __DIR__ . '/web/admin/infinitum.php';
            require_once __DIR__ . '/web/admin/page.php';
            require_once __DIR__ . '/web/admin/news.php';
            require_once __DIR__ . '/web/admin/tech.php';
        });
    });

    // Роуты для авторизованного пользователя
    Route::middleware(['auth'])->group(static function(){
        include __DIR__ . '/web/cabinet/private.php';
    });
});


// Админ панель
Route::middleware(['auth', 'admin', 'web'])->prefix('admin')->name('admin.')->group(function () {


    Route::get('{part}/upload/{id}', function (string $part, int $id) {
        $class = '\\App\\Http\\Controllers\\Admin\\'.ucfirst($part).'Controller';
        if (class_exists($class) && method_exists($class, 'upload')) {
            return (new $class())->upload($id);
        }

        return abort(404);
    })->name('admin_upload');

    // Загрузка файлов в WYSIWYG редакторе
    Route::post('upload/{path}', function (string $path, Request $request) {
        (new UploadController())->editorUpload($path, $request);
    });


    Route::post('voting/delete/{id}', static function ($id, Request $request) {
        (new ApiOmittedController())->removeVoting($id, $request);
    });
});

