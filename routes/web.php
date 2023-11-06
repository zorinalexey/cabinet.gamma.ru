<?php

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MyProfileController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Api\FnsController;
use App\Http\Controllers\Api\FundController as ApiFundController;
use App\Http\Controllers\Api\OmittedController as ApiOmittedController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Cabinet\CabinetController;
use App\Http\Controllers\Cabinet\DocumentController;
use App\Http\Controllers\Cabinet\EsiaController;
use App\Http\Controllers\Cabinet\FundController;
use App\Http\Controllers\Cabinet\LoginController;
use App\Http\Controllers\Cabinet\NewsController;
use App\Http\Controllers\Cabinet\OmittedController;
use App\Http\Controllers\Cabinet\PageController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\SearchController;
use App\Http\Controllers\Cabinet\VotingController;
use App\Http\Middleware\Guest;
use App\Http\Middleware\NoLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(Guest::class)->group(function () {

    // Новости
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

    Route::post('api/getInn', function (Request $request){
        return (new FnsController())->getInn($request);
    });
});

Route::middleware([Guest::class, NoLogin::class])->group(function () {

    // Главная страница
    Route::get('/', [LoginController::class, 'login'])->name('login');

    // Двухфакторная авторизация по СМС
    Route::post('/getCode', [LoginController::class, 'getCode'])->name('get_aut_code');
    Route::post('/auth', [LoginController::class, 'auth'])->name('auth');

    // Авторизация через Госуслуги
    Route::any('/esia/auth', [EsiaController::class, 'auth'])->name('esia_auth');
    Route::any('/esia/login', [EsiaController::class, 'login'])->name('esia_login');

    // Вход в админку
    Route::get('admin/login', [AdminLoginController::class, 'login'])->name('admin_login');

});

Route::post('admin/auth', [AdminLoginController::class, 'auth'])->middleware('web')->name('admin_auth');
Route::post('set/pass', [AdminLoginController::class, 'setNewPassword'])->middleware('web')->name('admin_new_pass');

// Выход из приложения
Route::get('/logout', [LoginController::class, 'logOut'])->middleware('web')->name('logout');

// Админ панель
Route::middleware(['auth', 'admin', 'web'])->prefix('admin')->group(function () {

    Route::get('{part}/upload/{id}', function (string $part, int $id){
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'upload')) {
            return (new $class())->upload($id);
        }
        return abort(404);
    })->name('admin_upload');

    // Загрузка файлов в WYSIWYG редакторе
    Route::post('upload/{path}', function (string $path, Request $request) {
        (new UploadController())->editorUpload($path, $request);
    });

    Route::get('main', [MainController::class, 'index'])->name('admin_main');

    // Мой профиль
    Route::get('my/profile/settings', [MyProfileController::class, 'settings'])->name('admin_my_profile_settings');

    // Страница обновление записи модуля
    Route::post('{part}/update/{id}', function (string $part, int $id, Request $request) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'update')) {
            return (new $class())->update($request, $id);
        }
        return abort(404);
    })->name('admin_update');

    // Страница начала редактирования записи модуля
    Route::get('{part}/edit/{id}', function (string $part, int $id) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'edit')) {
            return (new $class())->edit($id);
        }
        return abort(404);
    })->name('admin_edit');

    // Страница мягкого удаления (с возможностью восстановления) записи модуля
    Route::get('{part}/destroy/{id}', function (string $part, int $id) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'destroy')) {
            return (new $class())->destroy($id);
        }
        return abort(404);
    })->name('admin_destroy');

    // Страница полного удаления записи модуля
    Route::get('{part}/delete/{id}', function (string $part, int $id) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'delete')) {
            return (new $class())->delete($id);
        }
        return abort(404);
    })->name('admin_delete');

    // Страница просмотра записи модуля
    Route::get('{part}/show/{id}', function (string $part, int $id) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'show')) {
            return (new $class())->show($id);
        }
        return abort(404);
    })->name('admin_show');

    // Страница сохранения записи модуля
    Route::post('{part}/store', function (string $part, Request $request) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'store')) {
            return (new $class())->store($request);
        }
        return abort(404);
    })->name('admin_store');

    // Страница начала создания записи модуля
    Route::get('{part}/create', function (string $part) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'create')) {
            return (new $class())->create();
        }
        return abort(404);
    })->name('admin_create');

    // Страница общего просмотра модуля
    Route::get('{part}', function (string $part) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'index')) {
            return (new $class())->index();
        }
        return abort(404);
    })->name('admin_index');

    // Страница восстановления модуля
    Route::get('{part}/restore/{id}', function (string $part, int $id) {
        $class = '\\App\\Http\\Controllers\\Admin\\' . ucfirst($part) . 'Controller';
        if (class_exists($class) && method_exists($class, 'restoreModel')) {
            return (new $class())->restoreModel($id);
        }
        return abort(404);
    })->name('admin_restore');

    // Проверка по базам
    Route::get('users/check/{id}', function ($id) {
        return UsersController::check((int)$id);
    })->name('admin_user_check');

    // Добавление фонда пользователя
    Route::post('user/add_fund', function (Request $request) {
        return UsersController::addFund($request);
    })->name('admin_add_user_fund');

    // Удаление фонда пользователя
    Route::get('user/drop/fund/{fund_id}', function ($fund_id) {
        return UsersController::dropFund($fund_id);
    })->name('admin_drop_user_fund');

    // Изменение количества паёв фонда
    Route::post('user/edit/count_pif', function (Request $request) {
        return UsersController::editCountPif($request);
    })->name('admin_edit_user_count_pif');

    Route::post('voting/delete/{id}', function ($id, Request $request) {
        return (new ApiOmittedController)->removeVoting($id, $request);
    });
});

// Закрытая клиентская часть
Route::middleware(['auth', 'web'])->group(callback: function () {

    // Кабинет инвестора
    Route::get('/cabinet', [CabinetController::class, 'cabinet'])->name('cabinet');
    Route::get('/return/money', [CabinetController::class, 'returnMoney']);
    Route::get('/view/accounting', [CabinetController::class, 'viewAccounting'])->name('view.accounting');

    // Фонды инвестора
    Route::get('/funds', [FundController::class, 'all'])->name('funds');
    Route::get('/fund/{id}', static function ($id) {
        return (new FundController())->viewFund($id);
    })->name('fund');

    // Документы
    Route::get('/documents', [DocumentController::class, 'list'])->name('documents');
    Route::any('/document/sign/{id}', function ($id, Request $request) {
        return (new DocumentController())->signDocument($id, $request);
    })->name('sign_document');

    // Инвестиционный комитет
    Route::get('/omitted', [OmittedController::class, 'all'])->name('omitted');
    Route::get('/omitted/{id}', function ($id) {
        return (new OmittedController())->omitted_view($id);
    })->name('view_omitted');

    // Голосование
    Route::get('/voting/{id}', function ($id) {
        return (new VotingController())->view($id);
    })->name('voting');
    Route::post('/voting/{id}', [VotingController::class, 'save'])->name('voting_save');

    // Профиль инвестора
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');

    Route::post('/profile/add/ruble_account', [ProfileController::class, 'addRubleAccount'])->name('add_ruble_account');

    Route::post('/profile/edit/ruble_account/{id}', function (int $id, Request $request) {
        return (new ProfileController())->editRubleAccount($id, $request);
    })->name('edit_ruble_account');

    Route::get('/profile/drop/ruble_account/{id}', function (int $id) {
        return (new ProfileController())->dropRubleAccount($id);
    })->name('drop_ruble_account');

    Route::post('/profile/add/currency_account', [ProfileController::class, 'addCurrencyAccount'])->name('add_currency_account');

    Route::post('/profile/edit/currency_account/{id}', function (int $id, Request $request) {
        return (new ProfileController())->editCurrencyAccount($id, $request);
    })->name('edit_currency_account');

    Route::get('/profile/drop/currency_account/{id}', function (int $id) {
        return (new ProfileController())->dropCurrencyAccount($id);
    })->name('drop_currency_account');

    Route::post('/profile/edit/phone', [ProfileController::class, 'editPhone'])->name('edit_phone');
    Route::post('/profile/edit/email', [ProfileController::class, 'editEmail'])->name('edit_email');

    // Запросы ajax при покупке паев
    Route::post('/api/pay/funds', [ApiFundController::class, 'getDocuments']);
    Route::post('/api/send/codeDocuments', [ApiFundController::class, 'sendCodeSignDocuments']);
    Route::post('/api/sign/documents', [ApiFundController::class, 'signDocuments']);

    Route::get('/omitted/download/{id}', function (int $id){
        return (new OmittedController())->upload($id);
    })->name('user.omitted.upload.file');
});
