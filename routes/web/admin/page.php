<?php

use App\Http\Controllers\Admin\PagesController;
use App\Http\Requests\StaticPageCreateRequest;
use App\Http\Requests\StaticPageUpdateRequest;
use App\Models\StaticPage;
use Illuminate\Support\Facades\Route;


Route::prefix('page')->name('page.')->group(static function (){

    Route::get('list', [PagesController::class, 'index'])->name('list');

    Route::post('update/{staticPage}', static function (StaticPageUpdateRequest $request, StaticPage $staticPage){
        return (new PagesController())->update($request, $staticPage);
    })->name('update');

    Route::get('edit/{staticPage}', static function (StaticPage $staticPage){
        return (new PagesController())->edit($staticPage);
    })->name('edit');

    Route::get('restore/{id}', static function (int $id){
        return (new PagesController())->restoreModel($id);
    })->name('restore');

    Route::get('delete/{id}', static function (int $id){
        return (new PagesController())->delete($id);
    })->name('delete');

    Route::get('destroy/{staticPage}', static function(StaticPage $staticPage){
        return (new PagesController())->destroy($staticPage);
    })->name('destroy');

    Route::post('store', static function (StaticPageCreateRequest $request){
        return (new PagesController())->store($request);
    })->name('store');

    Route::get('create', static function (){
        return (new PagesController())->create();
    })->name('create');
});
