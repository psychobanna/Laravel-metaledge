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
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OwnSliderController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\WebsiteController;
use App\Models\Cart;
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
Route::post('/forgot-password', [PasswordResetLinkController::class, 'storeNewPassword'])->middleware('guest')->name('password.email');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.update');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->middleware('auth');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/website', [WebsiteController::class, 'websiteshow'])->middleware('guest');
Route::get('view-active-banner',[OwnSliderController::class, 'bannerActiveShow']);
Route::get('view-active-banner/{id}',[OwnSliderController::class, 'bannerActiveShow']);
Route::get('view-active-product',[ProductController::class, 'productActiveShow']);
Route::get('view-active-product/{id}',[ProductController::class, 'productActiveShow']);
Route::get('view-active-productbycategory/{id}',[ProductController::class, 'productActiveShowByCategory']);
Route::get('view-active-page',[PagesController::class, 'showActivePages']);
Route::get('view-active-page/{id}',[PagesController::class, 'showActivePages']);
Route::get('view-active-category',[CategoryController::class, 'showActiveCategory']);
// Route::get('view-active-category/{id}',[CategoryController::class, 'showActiveCategory']);
Route::get('view-active-category/{id}/{parent_id}',[CategoryController::class, 'showActiveCategory']);
Route::get('view-active-blog',[BlogController::class, 'showActiveBlog']);
Route::get('view-active-blog/{id}',[BlogController::class, 'showActiveBlog']);

// Customer
Route::post('customer-register',[CustomerController::class, 'userRegister']);
Route::post('customer-login',[CustomerController::class, 'userLogin']);
Route::post('add-customer-subscribe',[SubscribeController::class, 'storeSubscribe']);
Route::get('view-customer-subscribe',[SubscribeController::class, 'showSubscribe']);

Route::post('add-review',[ReviewsController::class, 'storeReview']);
Route::get('view-review/{product_id}',[ReviewsController::class, 'showReview']);


// Cart
Route::post('add-cart',[CartController::class, 'storeCart']);
Route::get('view-cart/{id}',[CartController::class, 'showCart']);
Route::get('view-cart/{id}/{product_id}',[CartController::class, 'showCart']);



Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    // Admin
    Route::post('/change-password', [NewPasswordController::class, 'changepassword']);
    Route::get('/profile', [AuthenticatedSessionController::class, 'viewProfile']);
    Route::post('/profile', [AuthenticatedSessionController::class, 'profile']);

    // Category
    Route::post('add-category',[CategoryController::class, 'store']);
    Route::get('view-category',[CategoryController::class, 'show']);
    Route::get('view-category/{id}',[CategoryController::class, 'show']);
    Route::get('view-category/{id}/{parent_id}',[CategoryController::class, 'show']);
    Route::post('edit-category/{id}',[CategoryController::class, 'update']);
    Route::post('edit-category-status/{id}',[CategoryController::class, 'updateStatus']);
    Route::delete('delete-category/{id}',[CategoryController::class, 'destroy']);

    // Page
    Route::post('add-page',[PagesController::class, 'storePages']);
    Route::get('view-page',[PagesController::class, 'showPages']);
    Route::get('view-page/{id}',[PagesController::class, 'showPages']);
    Route::post('edit-page/{id}',[PagesController::class, 'editPage']);
    Route::post('edit-page-status/{id}',[PagesController::class, 'updatePageStatus']);
    Route::delete('delete-page/{id}',[PagesController::class, 'destroyPage']);

    // Blog
    Route::post('add-blog',[BlogController::class, 'storeBlog']);
    Route::get('view-blog',[BlogController::class, 'showBlog']);
    Route::get('view-blog/{id}',[BlogController::class, 'showBlog']);
    Route::post('edit-blog/{id}',[BlogController::class, 'editBlog']);
    Route::post('edit-blog-status/{id}',[BlogController::class, 'updateBlogStatus']);
    Route::delete('delete-blog/{id}',[BlogController::class, 'destroyBlog']);

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
