<?php


use App\Http\Controllers\Admin\UsersController as UserController;
use App\Http\Requests\AddUserFundRequest;
use App\Http\Requests\EditUserFundRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\UserFund;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->name('user.')->group(static function (){


    // Проверка по базам
    Route::get('check/{user}', function (User $user) {
        return UserController::check($user);
    })->name('check');

    Route::get('list', [UserController::class, 'index'])->name('list');

    Route::post('update/{user}', static function (UserUpdateRequest $request, User $user){
        return (new UserController())->update($request, $user);
    })->name('update');

    Route::get('edit/{user}', static function (User $user){
        return (new UserController())->edit($user);
    })->name('edit');

    Route::get('restore/{id}', static function (int $id){
        return (new UserController())->restoreModel($id);
    })->name('restore');

    Route::get('delete/{id}', static function (int $id){
        return (new UserController())->delete($id);
    })->name('delete');

    Route::get('destroy/{user}', static function(User $user){
        return (new UserController())->destroy($user);
    })->name('destroy');

    Route::post('store', static function (UserCreateRequest $request){
        return (new UserController())->store($request);
    })->name('store');

    Route::get('create', static function (){
        return (new UserController())->create();
    })->name('create');

    Route::get('show/{fund}', static function (User $fund){
        return (new UserController())->show($fund);
    })->name('show');

    Route::prefix('{user}/fund')->name('fund.')->group(static function(){
        Route::post('add', static function(User $user, AddUserFundRequest $request){
            return UserController::addFund($user, $request);
        })->name('add');

        Route::post('edit/{userFund}', static function(User $user, UserFund $userFund, EditUserFundRequest $request){
            return UserController::editCountPif($user, $userFund, $request);
        })->name('edit');

        Route::get('drop/{userFund}', static function(User $user, UserFund $userFund){
            return UserController::dropFund($user, $userFund);
        })->name('drop');
    });
});
