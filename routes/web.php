<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WebsiteConfigController;

use App\Http\Controllers\Guest\DashboardController;
use App\Http\Controllers\Guest\ArticleController as GuestArticleController;

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

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('trackSession');
Route::get('/contact', [DashboardController::class, 'viewContact'])->name('viewContact');

Route::prefix('article')->group(function () {
    Route::get('/', [GuestArticleController::class, 'index'])->name('guest.article.index');
    Route::get('/read/{year}/{month}/{slug}', [GuestArticleController::class, 'read'])->name('guest.article.read');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->middleware(['checkDefaultPasswordUser'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Change Password
    Route::get('/change-password', [AuthController::class, 'viewPassword'])->name('viewPassword');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('updatePassword');

    Route::prefix('cms')->middleware(['checkDefaultPasswordUser'])->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->middleware('permission:List Role')->name('role.index');
            Route::get('/create', [RoleController::class, 'create'])->middleware('permission:Tambah Role')->name('role.create');
            Route::post('/store', [RoleController::class, 'store'])->middleware('permission:Tambah Role')->name('role.store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->middleware('permission:Edit Role')->name('role.edit');
            Route::post('/update', [RoleController::class, 'update'])->middleware('permission:Edit Role')->name('role.update');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->middleware('permission:List User')->name('user.index');
            Route::post('/store', [UserController::class, 'store'])->middleware('permission:Tambah User')->name('user.store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->middleware('permission:Edit User')->name('user.edit');
            Route::post('/update', [UserController::class, 'update'])->middleware('permission:Edit User')->name('user.update');
            Route::post('/resetPassword', [UserController::class, 'resetPassword'])->middleware('permission:Reset Password User')->name('user.resetPassword');
        });
    
        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->middleware('permission:List Kategori')->name('category.index');
            Route::post('/store', [CategoryController::class, 'store'])->middleware('permission:Tambah Kategori')->name('category.store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->middleware('permission:Edit Kategori')->name('category.edit');
            Route::post('/update', [CategoryController::class, 'update'])->middleware('permission:Edit Kategori')->name('category.update');
        });
    
        Route::prefix('article')->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->middleware('permission:List Artikel')->name('article.index');
            Route::get('/create', [ArticleController::class, 'create'])->middleware('permission:Tambah Artikel')->name('article.create');
            Route::post('/store', [ArticleController::class, 'store'])->middleware('permission:Tambah Artikel')->name('article.store');
            Route::get('/edit/{id}', [ArticleController::class, 'edit'])->middleware('permission:Edit Artikel')->name('article.edit');
            Route::post('/update', [ArticleController::class, 'update'])->middleware('permission:Edit Artikel')->name('article.update');

            Route::post('/uploadImage', [ArticleController::class, 'uploadImage'])->middleware('permission:Tambah Artikel|Edit Artikel')->name('article.uploadImage');
            Route::post('/deleteImage', [ArticleController::class, 'deleteImage'])->middleware('permission:Tambah Artikel|Edit Artikel')->name('article.deleteImage');
        });

        Route::prefix('website-config')->group(function () {
            Route::get('/', [WebsiteConfigController::class, 'index'])->middleware('permission:Update Pengaturan Website')->name('webCon.index');
            Route::post('/save', [WebsiteConfigController::class, 'save'])->middleware('permission:Update Pengaturan Website')->name('webCon.save');
        });
    });
});
