<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(DocumentController::class)->prefix('documents')->group(function () {
   Route::post('create', 'create')->name('documents.create');
   Route::get('all', 'index')->name('documents.all');
   Route::get('inventories', 'productInventories')->name('product-inventories');
});
