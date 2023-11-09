<?php


use App\Http\Controllers\Admin\TechController;
use App\Http\Requests\TechCreateRequest;
use Illuminate\Support\Facades\Route;


Route::prefix('tech')->name('tech.')->group(static function (){

    Route::get('list', [TechController::class, 'index'])->name('list');

    Route::post('store', static function (TechCreateRequest $request){
        return (new TechController())->store($request);
    })->name('store');
});
