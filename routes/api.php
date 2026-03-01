<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\BrandController;
// use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BrandUnitController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\OccupationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::prefix('api')->group(function () {});
// Route::middleware('auth:sanctum')->group(function () {});


Route::apiResource('company', CompanyController::class);
Route::apiResource('generic', GenericController::class);
Route::apiResource('brand', BrandController::class);
Route::apiResource('unit', UnitController::class);
Route::apiResource('brandunit', BrandUnitController::class);
Route::apiResource('order', OrderController::class);
Route::apiResource('bookmark', BookmarkController::class);
Route::apiResource('occupation', OccupationController::class);