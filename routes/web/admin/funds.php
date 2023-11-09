<?php



use App\Http\Controllers\Admin\FundsController as FundController;
use App\Http\Requests\FundCreateRequest;
use App\Http\Requests\FundUpdateRequest;
use App\Models\Fund;
use Illuminate\Support\Facades\Route;


Route::prefix('fund')->name('fund.')->group(static function (){

    Route::get('list', [FundController::class, 'index'])->name('list');

    Route::post('update/{fund}', static function (FundUpdateRequest $request, Fund $fund){
        return (new FundController())->update($request, $fund);
    })->name('update');

    Route::get('edit/{fund}', static function (Fund $fund){
        return (new FundController())->edit($fund);
    })->name('edit');

    Route::get('restore/{id}', static function (int $id){
        return (new FundController())->restoreModel($id);
    })->name('restore');

    Route::get('delete/{id}', static function (int $id){
        return (new FundController())->delete($id);
    })->name('delete');

    Route::get('destroy/{fund}', static function(Fund $fund){
        return (new FundController())->destroy($fund);
    })->name('destroy');

    Route::post('store', static function (FundCreateRequest $request){
        return (new FundController())->store($request);
    })->name('store');

    Route::get('create', static function (){
        return (new FundController())->create();
    })->name('create');

    Route::get('show/{fund}', static function (Fund $fund){
        return (new FundController())->show($fund);
    })->name('show');
});
