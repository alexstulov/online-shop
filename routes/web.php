<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProductController;

Route::get('/', [GroupController::class, 'index']);
Route::get('/category/{category_id}', [GroupController::class, 'index']);
Route::get('/product/{product_id}', [ProductController::class, 'view']);

Route::get('/welcome', function () { return view('welcome');});
