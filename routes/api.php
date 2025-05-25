<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Products\ApiProductController;
use App\Http\Controllers\Api\Products\BulkVariation\BulkVariationController;
use App\Http\Controllers\Api\Products\Color\ColorController;
use App\Http\Controllers\Api\Products\Size\SizeController;
use App\Http\Controllers\Api\Products\Variation\VariationController;
use App\Http\Controllers\Api\Products\Category\ApiCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(SupplierController::class)->group(function () {
    Route::get('/supplier', 'index')->name('supplier');
    Route::post('/supplier/store', 'store')->name('supplier.store');
    Route::get('/supplier/view', 'view')->name('supplier.view');
    Route::get('/supplier/edit/{id}', 'edit')->name('supplier.edit');
    Route::post('/supplier/update/{id}', 'update')->name('supplier.update');
    Route::get('/supplier/destroy/{id}', 'destroy')->name('supplier.destroy');
    // Supplier Profiling
    // Route::get('/supplier/profile/{id}', 'SupplierProfile')->name('supplier.profile');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

  Route::controller(ApiProductController::class)->group(function () {

            Route::get('/product', 'index')->name('product');
            Route::post('/product/store', 'store')->name('product.store');
            Route::get('/product/view', 'view')->name('product.view');
            Route::get('/product/edit/{id}', 'edit')->name('product.edit');
            Route::post('/product/update/{id}', 'update')->name('product.update');
            Route::get('/product/destroy/{id}', 'destroy')->name('product.destroy');
            Route::get('/product/find/{id}', 'find')->name('product.find');
            Route::get('/variant/barcode/{id}', 'variantBarcode');
            Route::get('/search/{value}', 'globalSearch');
            Route::get('/product/variation/view/{id}', 'productVariationView')->name('product.variation.view');
            Route::post('/product/status/{id}',  'productStatus')->name('product.status');
           // product ledger
            Route::get('/product/ledger/{id}', 'productLedger')->name('product.ledger');
            Route::get('/latest-product', 'latestProduct');




});

Route::controller(BulkVariationController::class)->group(function () {


        Route::get('/product/bulk_variation/view', 'bulkVariationView')->name('product.bulk_variation.view');
        Route::get('/bulk/variation/data', 'bulkVariationData');
        Route::post('/bulk/variation/update', 'bulkVariationUpdate');

});


Route::controller(SizeController::class)->group(function () {
        Route::get('/latest-product-size', 'latestProductSize');
        Route::get('/variation-product-size/{id}', 'variationProductSize');
        Route::get('/edit-product-size/{id}', 'editProductSize');
});


Route::controller(VariationController::class)->group(function () {
        Route::post('/store-variation', 'storeVariation');
        Route::get('/variant/find/{id}', 'findVariant');
        Route::post('/update-variation', 'updateVariation');
        Route::delete('/variation/delete/{id}', 'deleteVariation');
        Route::post('/variant/barcode/print-all',  'printAllBarcodes')->name('print.all.barcodes');
        Route::post('/variation/status/{id}', 'variationStatus')->name('variation.status');
});

Route::controller(ColorController::class)->group(function(){
        // color add
        Route::post('/color/add',  'colorAdd');
        Route::get('/color/view',  'colorView');
});


  // category related route
    Route::controller(ApiCategoryController::class)->group(function () {
        Route::get('/category', 'index')->name('product.category');
        Route::post('/category/store', 'store')->name('category.store');
        Route::get('/category/view', 'view')->name('category.view');
        Route::get('/category/edit/{id}', 'edit')->name('category.edit');
        Route::post('/category/update/{id}', 'update')->name('category.update');
        Route::post('/category/status/{id}', 'status')->name('category.status');
        Route::get('/category/destroy/{id}', 'destroy')->name('category.destroy');
        // Route::get('/categories/all', 'categoryAll')->name('categories.all');
    });
