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
use App\Http\Controllers\Api\Products\Subcategory\ApiSubCategoryController;
use  App\Http\Controllers\Api\Products\Brand\BrandController;
use App\Http\Controllers\Api\Products\Stock\StockController;
use App\Http\Controllers\Api\Products\Unit\UnitController;
use App\Http\Controllers\Api\Products\Promotion\PromotionController;
use App\Http\Controllers\Api\Products\Attribute\AttributeController;
use App\Http\Controllers\Api\Auth\AuthControler;
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
Route::middleware('auth:sanctum')->group(function () {

    // This route returns the authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Grouped routes for PromotionController
    Route::controller(PromotionController::class)->group(function () {
        Route::get('/promotion/add', 'PromotionAdd')->name('promotion.add');
        Route::post('/promotion/store', 'PromotionStore')->name('promotion.store');
        Route::get('/promotion/view', 'PromotionView')->name('promotion.view');
        Route::get('/promotion/edit/{id}', 'PromotionEdit')->name('promotion.edit');
        Route::post('/promotion/update/{id}', 'PromotionUpdate')->name('promotion.update');
        Route::get('/promotion/delete/{id}', 'PromotionDelete')->name('promotion.delete');
        Route::get('/promotion/find/{id}', 'find')->name('promotion.find');

        // Promotion Details routes
        Route::get('/promotion/details/add', 'PromotionDetailsAdd')->name('promotion.details.add');
        Route::post('/promotion/details/store', 'PromotionDetailsStore')->name('promotion.details.store');
        Route::get('/promotion/details/view', 'PromotionDetailsView')->name('promotion.details.view');
        Route::get('/promotion/details/edit/{id}', 'PromotionDetailsEdit')->name('promotion.details.edit');
        Route::post('/promotion/details/update/{id}', 'PromotionDetailsUpdate')->name('promotion.details.update');
        Route::get('/promotion/details/delete/{id}', 'PromotionDetailsDelete')->name('promotion.details.delete');
        Route::get('/promotion/product', 'allProduct')->name('promotion.product');
        Route::get('/promotion/customers', 'allCustomers')->name('promotion.customers');
        Route::get('/promotion/branch', 'allBranch')->name('promotion.branch');
        Route::get('/promotion/details/find', 'PromotionDetailsFind')->name('promotion.details.find');
    });

});







Route::controller(AuthControler::class)->group(function () {
     Route::post('auth/login', 'authlogin')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/refresh', 'refresh')->name('refresh');
    Route::get('/user-profile', 'userProfile')->name('user.profile');
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


        Route::get('/product/size/add', 'ProductSizeAdd')->name('product.size.add');
        Route::post('/product/size/store', 'ProductSizeStore')->name('product.size.store');
        Route::get('/product/size/view', 'ProductSizeView')->name('product.size.view');
        Route::get('/product/size/edit/{id}', 'ProductSizeEdit')->name('product.size.edit');
        Route::post('/product/size/update/{id}', 'ProductSizeUpdate')->name('product.size.update');
        Route::get('/product/size/delete/{id}', 'ProductSizeDelete')->name('product.size.delete');
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

       // subcategory related route(n)
    Route::controller(ApiSubCategoryController::class)->group(function () {
        Route::get('/subcategory', 'index')->name('product.subcategory');
        Route::post('/subcategory/store', 'store')->name('subcategory.store');
        Route::get('/subcategory/view', 'view')->name('subcategory.view');
        Route::get('/subcategory/edit/{id}', 'edit')->name('subcategory.edit');
        Route::post('/subcategory/update/{id}', 'update')->name('subcategory.update');
        Route::get('/subcategory/destroy/{id}', 'destroy')->name('subcategory.destroy');
        Route::post('/subcategory/status/{id}', 'status')->name('subcategory.status');
        Route::get('/subcategory/find/{id}', 'find')->name('subcategory.find');
    });


     // Brand related route
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brand', 'index')->name('product.brand');
        Route::post('/brand/store', 'store')->name('brand.store');
        Route::get('/brand/view', 'view')->name('brand.view');
        Route::get('/brand/edit/{id}', 'edit')->name('brand.edit');
        Route::post('/brand/update/{id}', 'update')->name('brand.update');
        Route::post('/brand/status/{id}', 'status')->name('brand.status');
        Route::get('/brand/destroy/{id}', 'destroy')->name('brand.destroy');
    });

        // Stocks related route
    Route::controller(StockController::class)->group(function () {
        Route::get('/stock', 'index')->name('product.stock');
        Route::post('/stock/store', 'store');
        Route::get('/stock/view', 'view');
        Route::get('/stock/edit/{id}', 'edit');
        Route::post('/stock/update/{id}', 'update');
        Route::get('/stock/destroy/{id}', 'destroy');
    });


      // Unit related route
    Route::controller(UnitController::class)->group(function () {
        Route::get('/unit', 'index')->name('product.unit');
        Route::post('/unit/store', 'store')->name('unit.store');
        Route::get('/unit/view', 'view')->name('unit.view');
        Route::get('/unit/edit/{id}', 'edit')->name('unit.edit');
        Route::post('/unit/update/{id}', 'update')->name('unit.update');
        Route::get('/unit/destroy/{id}', 'destroy')->name('unit.destroy');
    });

  Route::controller(AttributeController::class)->group(function () {
        Route::post('/store/extra/datatype/field', 'store')->name('extrafield.data.type.store');
        Route::get('/get/extra/info/field/{id}', 'getExtraField')->name('get.extra.field');
        Route::get('get-extra-field/info/product/page/show', 'getExtraFieldInfoProductPageShow')->name('get.extra.field.info.product.page.show');
    });


