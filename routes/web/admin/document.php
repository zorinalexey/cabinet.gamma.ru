<?php


use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Requests\DocumentCreateRequest;
use App\Http\Requests\DocumentUpdateRequest;
use App\Models\UserDocument as Document;
use Illuminate\Support\Facades\Route;


Route::prefix('document')->name('document.')->group(static function (){

    Route::get('list', [DocumentsController::class, 'index'])->name('list');

    Route::get('restore/{id}', static function (int $id){
        return (new DocumentsController())->restoreModel($id);
    })->name('restore');

    Route::get('destroy/{document}', static function(Document $document){
        return (new DocumentsController())->destroy($document);
    })->name('destroy');

    Route::get('upload/{document}', static function(Document $document){
        return (new DocumentsController())->upload($document);
    })->name('upload');

});
