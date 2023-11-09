<?php


use App\Http\Controllers\Admin\OmittedController;
use App\Http\Requests\OmittedCreateRequest;
use App\Http\Requests\OmittedUpdateRequest;
use App\Models\Omitted;
use Illuminate\Support\Facades\Route;


Route::prefix('omitted')->name('omitted.')->group(static function (){

    Route::get('list', [OmittedController::class, 'index'])->name('list');

    Route::post('update/{omitted}', static function (OmittedUpdateRequest $request, Omitted $omitted){
        return (new OmittedController())->update($request, $omitted);
    })->name('update');

    Route::get('edit/{omitted}', static function (Omitted $omitted){
        return (new OmittedController())->edit($omitted);
    })->name('edit');

    Route::get('restore/{id}', static function (int $id){
        return (new OmittedController())->restoreModel($id);
    })->name('restore');

    Route::get('delete/{id}', static function (int $id){
        return (new OmittedController())->delete($id);
    })->name('delete');

    Route::get('destroy/{omitted}', static function(Omitted $omitted){
        return (new OmittedController())->destroy($omitted);
    })->name('destroy');

    Route::post('store', static function (OmittedCreateRequest $request){
        return (new OmittedController())->store($request);
    })->name('store');

    Route::get('create', static function (){
        return (new OmittedController())->create();
    })->name('create');

    Route::get('generate/protocol/{omitted}', function (Omitted $omitted){
        return (new OmittedController())->generateProtocol($omitted);
    })->name('protocol.gen');
});
