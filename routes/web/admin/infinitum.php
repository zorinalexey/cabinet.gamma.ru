<?php



use App\Http\Controllers\Admin\InfinitumController;
use App\Models\Infinitum;
use Illuminate\Support\Facades\Route;


Route::prefix('infinitum')->name('infinitum.')->group(static function (){

    Route::get('list', [InfinitumController::class, 'index'])->name('list');

    Route::get('restore/{id}', static function (int $id){
        return (new InfinitumController())->restoreModel($id);
    })->name('restore');

    Route::get('delete/{id}', static function (int $id){
        return (new InfinitumController())->delete($id);
    })->name('delete');

    Route::get('destroy/{infinitum}', static function(Infinitum $infinitum){
        return (new InfinitumController())->destroy($infinitum);
    })->name('destroy');


    Route::get('upload/{infinitum}', static function(Infinitum $infinitum){
        return (new InfinitumController())->upload($infinitum);
    })->name('upload');
});
