<?php



// Кабинет инвестора
use App\Http\Controllers\Api\FundController as ApiFundController;
use App\Http\Controllers\Cabinet\CabinetController;
use App\Http\Controllers\Cabinet\DocumentController;
use App\Http\Controllers\Cabinet\FundController;
use App\Http\Controllers\Cabinet\OmittedController;
use App\Http\Controllers\Cabinet\ProfileController;
use App\Http\Controllers\Cabinet\VotingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/omitted/download/{id}', function (int $id) {
    return (new OmittedController())->upload($id);
})->name('user.omitted.upload.file');
