<?php


use App\Http\Controllers\Admin\NewsController;
use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Models\News;
use Illuminate\Support\Facades\Route;


Route::prefix('news')->name('post.')->group(static function (){

    Route::get('list', [NewsController::class, 'index'])->name('list');

    Route::post('update/{news}', static function (NewsUpdateRequest $request, News $news){
        return (new NewsController())->update($request, $news);
    })->name('update');

    Route::get('edit/{news}', static function (News $news){
        return (new NewsController())->edit($news);
    })->name('edit');

    Route::get('restore/{id}', static function (int $id){
        return (new NewsController())->restoreModel($id);
    })->name('restore');

    Route::get('delete/{id}', static function (int $id){
        return (new NewsController())->delete($id);
    })->name('delete');

    Route::get('destroy/{news}', static function(News $news){
        return (new NewsController())->destroy($news);
    })->name('destroy');

    Route::post('store', static function (NewsCreateRequest $request){
        return (new NewsController())->store($request);
    })->name('store');

    Route::get('create', static function (){
        return (new NewsController())->create();
    })->name('create');
});
