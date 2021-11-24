<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OwnSliderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebsiteController;
use App\Models\ownSlider;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Admin
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'registration'])->middleware('guest');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.update');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->middleware('auth');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/website', [WebsiteController::class, 'websiteshow'])->middleware('guest');
Route::get('view-active-product',[ProductController::class, 'productActiveShow']);
Route::get('view-active-product/{id}',[ProductController::class, 'productActiveShow']);

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    // Admin

    // Category
    Route::post('add-category',[CategoryController::class, 'store']);
    Route::get('view-category',[CategoryController::class, 'show']);
    Route::get('view-category/{id}',[CategoryController::class, 'show']);
    Route::get('view-category/{id}/{parent_id}',[CategoryController::class, 'show']);
    Route::post('edit-category/{id}',[CategoryController::class, 'update']);
    Route::post('edit-category-status/{id}',[CategoryController::class, 'updateStatus']);
    Route::delete('delete-category/{id}',[CategoryController::class, 'destroy']);

    // Product
    Route::post('add-product',[ProductController::class, 'productStore']);
    Route::get('view-product',[ProductController::class, 'productShow']);
    Route::get('view-product/{id}',[ProductController::class, 'productShow']);
    Route::post('edit-product/{id}',[ProductController::class, 'productUpdate']);
    Route::post('edit-product-status/{id}',[ProductController::class, 'UpdateProductStatus']);
    Route::delete('delete-product/{id}',[ProductController::class, 'productDestroy']);

    // Banner
    Route::post('add-banner',[OwnSliderController::class, 'bannerstore']);
    Route::get('view-banner',[OwnSliderController::class, 'bannershow']);
    Route::get('view-banner/{id}',[OwnSliderController::class, 'bannershow']);
    Route::post('edit-banner/{id}',[OwnSliderController::class, 'bannerupdate']);
    Route::post('edit-banner-status/{id}',[OwnSliderController::class, 'bannerUpdateStatus']);
    Route::delete('delete-banner/{id}',[OwnSliderController::class, 'bannerDestroy']);

});

// Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    // });
