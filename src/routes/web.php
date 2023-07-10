<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Microoculus\AllUploads\Controllers\UploadController;
use Microoculus\AllUploads\Controllers\ListController;


Route::get('/pt', function () {
    return "pt thee";
});

Route::post('/all-uploads/store-upload', [UploadController::class, 'store'])->name('all-uploads.store-upload');
Route::get('/all-uploads/cursor-paginate-uploads', [ListController::class, 'cursorpaginate'])->name('all-uploads.cursor-paginate-uploads');
Route::get('/all-uploads/remote-list-uploads', [ListController::class, 'remoteList'])->name('all-uploads.remote-list-uploads');