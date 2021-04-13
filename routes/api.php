<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('auth/google/url', [GoogleController::class, 'googleLoginUrl']);
Route::get('auth/google/callback', [GoogleController::class, 'loginCallback']);

Route::get('auth/facebook/url', [FacebookController::class, 'facebookLoginUrl']);
Route::get('auth/facebook/callback', [FacebookController::class, 'loginCallback']);

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/products/{categoryId}', [ProductController::class, 'getProductsByCategory']);
Route::get('/subcategories/{categoryId}', [SubcategoryController::class, 'getSubcategories']);
Route::get('/products/{subcategoryId}', [ProductController::class, 'getProductsBySubcategory']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function(Request $request) {
        return auth()->user();
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
