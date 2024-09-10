<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('cms')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/store', [UserController::class, 'store'])->name('user.store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
            Route::post('/update', [UserController::class, 'update'])->name('user.update');
            Route::post('/resetPassword', [UserController::class, 'resetPassword'])->name('user.resetPassword');
        });
    
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('category.index');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::post('/update', [CategoryController::class, 'update'])->name('category.update');
        });
    
        Route::prefix('article')->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->name('article.index');
            Route::get('/create', [ArticleController::class, 'create'])->name('article.create');
            Route::post('/uploadImage', [ArticleController::class, 'uploadImage'])->name('article.uploadImage');
            Route::post('/deleteImage', [ArticleController::class, 'deleteImage'])->name('article.deleteImage');
            Route::post('/store', [ArticleController::class, 'store'])->name('article.store');
            Route::get('/edit/{id}', [ArticleController::class, 'edit'])->name('article.edit');
            Route::post('/update', [ArticleController::class, 'update'])->name('article.update');
        });
    });
});
