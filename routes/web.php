<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\CustomerCatalogueController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\GenerateController;
use App\Http\Controllers\Backend\ProductCatalogueController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\WidgetController;
use App\Http\Controllers\Backend\SourceController;
use App\Http\Controllers\Backend\PromotionController;
use App\Http\Controllers\Backend\SlideController;
use App\Http\Controllers\Backend\AttributeCatalogueController;
use App\Http\Controllers\Backend\AttributeController;
use App\Http\Controllers\Backend\SystemController;
//@@useController@@
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\AjaxAttributeController;
use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\MenuController as AjaxMenuController;
use App\Http\Controllers\Ajax\ProductController as AjaxProductController;
use App\Http\Controllers\Ajax\SourceController as AjaxSourceController;
use Illuminate\Support\Facades\Route;


use App\Http\Middleware\AuthenticateMiddleware;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware(AuthenticateMiddleware::class);

    Route::group(['prefix' => 'user'], function () {
        Route::get('index', [UserController::class, 'index'])->name('user.index');
        Route::get('create', [UserController::class, 'create'])->name('user.create');
        Route::post('store', [UserController::class, 'store'])->name('user.store');
        Route::get('{id}/edit', [UserController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('user.edit');
        Route::post('{id}/update', [UserController::class, 'update'])->where(['id'=>'[0-9]+'])->name('user.update');
        Route::get('{id}/delete', [UserController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('user.delete');
        Route::delete('{id}/destroy', [UserController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('user.destroy');
    });

    Route::group(['prefix' => 'user/catalogue'], function () {
        Route::get('index', [UserCatalogueController::class, 'index'])->name('user.catalogue.index');
        Route::get('create', [UserCatalogueController::class, 'create'])->name('user.catalogue.create');
        Route::post('store', [UserCatalogueController::class, 'store'])->name('user.catalogue.store');
        Route::get('{id}/edit', [UserCatalogueController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('user.catalogue.edit');
        Route::post('{id}/update', [UserCatalogueController::class, 'update'])->where(['id'=>'[0-9]+'])->name('user.catalogue.update');
        Route::get('{id}/delete', [UserCatalogueController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('user.catalogue.delete');
        Route::delete('{id}/destroy', [UserCatalogueController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('user.catalogue.destroy');
        Route::get('permission', [UserCatalogueController::class, 'permission'])->name('user.catalogue.permission');
        Route::post('updatePermission', [UserCatalogueController::class, 'updatePermission'])->name('user.catalogue.updatePermission');
    });
    Route::group(['prefix' => 'customer'], function () {
        Route::get('index', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('{id}/edit', [CustomerController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('customer.edit');
        Route::post('{id}/update', [CustomerController::class, 'update'])->where(['id'=>'[0-9]+'])->name('customer.update');
        Route::get('{id}/delete', [CustomerController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('customer.delete');
        Route::delete('{id}/destroy', [CustomerController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('customer.destroy');
    });

    Route::group(['prefix' => 'customer/catalogue'], function () {
        Route::get('index', [CustomerCatalogueController::class, 'index'])->name('customer.catalogue.index');
        Route::get('create', [CustomerCatalogueController::class, 'create'])->name('customer.catalogue.create');
        Route::post('store', [CustomerCatalogueController::class, 'store'])->name('customer.catalogue.store');
        Route::get('{id}/edit', [CustomerCatalogueController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('customer.catalogue.edit');
        Route::post('{id}/update', [CustomerCatalogueController::class, 'update'])->where(['id'=>'[0-9]+'])->name('customer.catalogue.update');
        Route::get('{id}/delete', [CustomerCatalogueController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('customer.catalogue.delete');
        Route::delete('{id}/destroy', [CustomerCatalogueController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('customer.catalogue.destroy');
    });

    Route::group(['prefix' => 'slide'], function () {
        Route::get('index', [SlideController::class, 'index'])->name('slide.index');
        Route::get('create', [SlideController::class, 'create'])->name('slide.create');
        Route::post('store', [SlideController::class, 'store'])->name('slide.store');
        Route::get('{id}/edit', [SlideController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('slide.edit');
        Route::post('{id}/update', [SlideController::class, 'update'])->where(['id'=>'[0-9]+'])->name('slide.update');
        Route::get('{id}/delete', [SlideController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('slide.delete');
        Route::delete('{id}/destroy', [SlideController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('slide.destroy');
    });
    Route::group(['prefix' => 'widget'], function () {
        Route::get('index', [WidgetController::class, 'index'])->name('widget.index');
        Route::get('create', [WidgetController::class, 'create'])->name('widget.create');
        Route::post('store', [WidgetController::class, 'store'])->name('widget.store');
        Route::get('{id}/edit', [WidgetController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('widget.edit');
        Route::post('{id}/update', [WidgetController::class, 'update'])->where(['id'=>'[0-9]+'])->name('widget.update');
        Route::get('{id}/delete', [WidgetController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('widget.delete');
        Route::delete('{id}/destroy', [WidgetController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('widget.destroy');
    });
    Route::group(['prefix' => 'source'], function () {
        Route::get('index', [sourceController::class, 'index'])->name('source.index');
        Route::get('create', [sourceController::class, 'create'])->name('source.create');
        Route::post('store', [sourceController::class, 'store'])->name('source.store');
        Route::get('{id}/edit', [sourceController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('source.edit');
        Route::post('{id}/update', [sourceController::class, 'update'])->where(['id'=>'[0-9]+'])->name('source.update');
        Route::get('{id}/delete', [sourceController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('source.delete');
        Route::delete('{id}/destroy', [sourceController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('source.destroy');
    });
    Route::group(['prefix' => 'promotion'], function () {
        Route::get('index', [PromotionController::class, 'index'])->name('promotion.index');
        Route::get('create', [PromotionController::class, 'create'])->name('promotion.create');
        Route::post('store', [PromotionController::class, 'store'])->name('promotion.store');
        Route::get('{id}/edit', [PromotionController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('promotion.edit');
        Route::post('{id}/update', [PromotionController::class, 'update'])->where(['id'=>'[0-9]+'])->name('promotion.update');
        Route::get('{id}/delete', [PromotionController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('promotion.delete');
        Route::delete('{id}/destroy', [PromotionController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('promotion.destroy');
    });

    Route::group(['prefix' => 'language'], function () {
        Route::get('index', [LanguageController::class, 'index'])->name('language.index');
        Route::get('create', [LanguageController::class, 'create'])->name('language.create');
        Route::post('store', [LanguageController::class, 'store'])->name('language.store');
        Route::get('{id}/edit', [LanguageController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('language.edit');
        Route::post('{id}/update', [LanguageController::class, 'update'])->where(['id'=>'[0-9]+'])->name('language.update');
        Route::get('{id}/delete', [LanguageController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('language.delete');
        Route::delete('{id}/destroy', [LanguageController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('language.destroy');
        Route::get('{id}/switch', [LanguageController::class, 'swithBackendLanguage'])->where(['id'=>'[0-9]+'])->name('language.switch');
        Route::get('{id}/{languageId}/{model}/translate', [LanguageController::class, 'translate'])->where(['id'=>'[0-9]+', 'languageId' => '[0-9]+'])->name('language.translate');
        Route::post('storeTranslate', [LanguageController::class, 'storeTranslate'])->name('language.storeTranslate');
    });

    Route::group(['prefix' => 'generate'], function () {
        Route::get('index', [GenerateController::class, 'index'])->name('generate.index');
        Route::get('create', [GenerateController::class, 'create'])->name('generate.create');
        Route::post('store', [GenerateController::class, 'store'])->name('generate.store');
        Route::get('{id}/edit', [GenerateController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('generate.edit');
        Route::post('{id}/update', [GenerateController::class, 'update'])->where(['id'=>'[0-9]+'])->name('generate.update');
        Route::get('{id}/delete', [GenerateController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('generate.delete');
        Route::delete('{id}/destroy', [GenerateController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('generate.destroy');
    });
    Route::group(['prefix' => 'system'], function () {
        Route::get('index', [SystemController::class, 'index'])->name('system.index');
        Route::post('store', [SystemController::class, 'store'])->name('system.store');
    });
    
    Route::group(['prefix' => 'post/catalogue'], function () {
        Route::get('index', [PostCatalogueController::class, 'index'])->name('post.catalogue.index');
        Route::get('create', [PostCatalogueController::class, 'create'])->name('post.catalogue.create');
        Route::post('store', [PostCatalogueController::class, 'store'])->name('post.catalogue.store');
        Route::get('{id}/edit', [PostCatalogueController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('post.catalogue.edit');
        Route::post('{id}/update', [PostCatalogueController::class, 'update'])->where(['id'=>'[0-9]+'])->name('post.catalogue.update');
        Route::get('{id}/delete', [PostCatalogueController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('post.catalogue.delete');
        Route::delete('{id}/destroy', [PostCatalogueController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('post.catalogue.destroy');
    });

    Route::group(['prefix' => 'post'], function () {
        Route::get('index', [PostController::class, 'index'])->name('post.index');
        Route::get('create', [PostController::class, 'create'])->name('post.create');
        Route::post('store', [PostController::class, 'store'])->name('post.store');
        Route::get('{id}/edit', [PostController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('post.edit');
        Route::post('{id}/update', [PostController::class, 'update'])->where(['id'=>'[0-9]+'])->name('post.update');
        Route::get('{id}/delete', [PostController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('post.delete');
        Route::delete('{id}/destroy', [PostController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('post.destroy');
    });
    Route::group(['prefix' => 'menu'], function () {
        Route::get('index', [MenuController::class, 'index'])->name('menu.index');
        Route::get('create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('store', [MenuController::class, 'store'])->name('menu.store');
        Route::get('{id}/edit', [MenuController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('menu.edit');
        Route::get('{id}/editmenu', [MenuController::class, 'editMenu'])->where(['id'=>'[0-9]+'])->name('menu.editMenu');
        Route::post('{id}/update', [MenuController::class, 'update'])->where(['id'=>'[0-9]+'])->name('menu.update');
        Route::get('{id}/delete', [MenuController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('menu.delete');
        Route::delete('{id}/destroy', [MenuController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('menu.destroy');
        Route::get('{id}/children', [MenuController::class, 'children'])->where(['id'=>'[0-9]+'])->name('menu.children');
        Route::post('{id}/saveChildren', [MenuController::class, 'saveChildren'])->where(['id'=>'[0-9]+'])->name('menu.save.children');
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('index', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('create', [PermissionController::class, 'create'])->name('permission.create');
        Route::post('store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('{id}/edit', [PermissionController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('permission.edit');
        Route::post('{id}/update', [PermissionController::class, 'update'])->where(['id'=>'[0-9]+'])->name('permission.update');
        Route::get('{id}/delete', [PermissionController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('permission.delete');
        Route::delete('{id}/destroy', [PermissionController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('permission.destroy');
    });

    Route::group(['prefix' => 'product/catalogue'], function () {
        Route::get('index', [ProductCatalogueController::class, 'index'])->name('product.catalogue.index');
        Route::get('create', [ProductCatalogueController::class, 'create'])->name('product.catalogue.create');
        Route::post('store', [ProductCatalogueController::class, 'store'])->name('product.catalogue.store');
        Route::get('{id}/edit', [ProductCatalogueController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('product.catalogue.edit');
        Route::post('{id}/update', [ProductCatalogueController::class, 'update'])->where(['id'=>'[0-9]+'])->name('product.catalogue.update');
        Route::get('{id}/delete', [ProductCatalogueController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('product.catalogue.delete');
        Route::delete('{id}/destroy', [ProductCatalogueController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('product.catalogue.destroy');
    });
    Route::group(['prefix' => 'product'], function () {
        Route::get('index', [ProductController::class, 'index'])->name('product.index');
        Route::get('create', [ProductController::class, 'create'])->name('product.create');
        Route::post('store', [ProductController::class, 'store'])->name('product.store');
        Route::get('{id}/edit', [ProductController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('product.edit');
        Route::post('{id}/update', [ProductController::class, 'update'])->where(['id'=>'[0-9]+'])->name('product.update');
        Route::get('{id}/delete', [ProductController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('product.delete');
        Route::delete('{id}/destroy', [ProductController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('product.destroy');
    });

    Route::group(['prefix' => 'attribute/catalogue'], function () {
        Route::get('index', [AttributeCatalogueController::class, 'index'])->name('attribute.catalogue.index');
        Route::get('create', [AttributeCatalogueController::class, 'create'])->name('attribute.catalogue.create');
        Route::post('store', [AttributeCatalogueController::class, 'store'])->name('attribute.catalogue.store');
        Route::get('{id}/edit', [AttributeCatalogueController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('attribute.catalogue.edit');
        Route::post('{id}/update', [AttributeCatalogueController::class, 'update'])->where(['id'=>'[0-9]+'])->name('attribute.catalogue.update');
        Route::get('{id}/delete', [AttributeCatalogueController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('attribute.catalogue.delete');
        Route::delete('{id}/destroy', [AttributeCatalogueController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('attribute.catalogue.destroy');
    });
    Route::group(['prefix' => 'attribute'], function () {
        Route::get('index', [AttributeController::class, 'index'])->name('attribute.index');
        Route::get('create', [AttributeController::class, 'create'])->name('attribute.create');
        Route::post('store', [AttributeController::class, 'store'])->name('attribute.store');
        Route::get('{id}/edit', [AttributeController::class, 'edit'])->where(['id'=>'[0-9]+'])->name('attribute.edit');
        Route::post('{id}/update', [AttributeController::class, 'update'])->where(['id'=>'[0-9]+'])->name('attribute.update');
        Route::get('{id}/delete', [AttributeController::class, 'delete'])->where(['id'=>'[0-9]+'])->name('attribute.delete');
        Route::delete('{id}/destroy', [AttributeController::class, 'destroy'])->where(['id'=>'[0-9]+'])->name('attribute.destroy');
    });
//@@new-module@@

// AJAX
    Route::get('/ajax/location/getLocation', [LocationController::class, 'getLocation'])->name('ajax.location.index');
    Route::post('/ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])->name('ajax.dashboard.changeStatus');
    Route::post('/ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])->name('ajax.dashboard.changeStatusAll');
    Route::get('/ajax/dashboard/getMenu', [AjaxDashboardController::class, 'getMenu'])->name('ajax.dashboard.getMenu');
    Route::get('/ajax/dashboard/findPromotionObject', [AjaxDashboardController::class, 'findPromotionObject'])->name('ajax.dashboard.findPromotionObject');
    Route::get('/ajax/dashboard/getPromotionConditionValue', [AjaxDashboardController::class, 'getPromotionConditionValue'])->name('ajax.dashboard.getPromotionConditionValue');
    Route::get('/ajax/dashboard/findModelByObject', [AjaxDashboardController::class, 'findModelByObject'])->name('ajax.dashboard.findModelByObject');
    Route::get('/ajax/attribute/getAttribute', [AjaxAttributeController::class, 'getAttribute'])->name('ajax.attribute.getAttribute');
    Route::get('/ajax/attribute/loadAttribute', [AjaxAttributeController::class, 'loadAttribute'])->name('ajax.attribute.loadAttribute');
    Route::post('/ajax/menu/createCatalogue', [AjaxMenuController::class, 'createCatalogue'])->name('ajax.menu.createCatalogue');
    Route::post('/ajax/menu/drag', [AjaxMenuController::class, 'drag'])->name('ajax.menu.drag');
    Route::get('/ajax/product/loadProductPromotion', [AjaxProductController::class, 'loadProductPromotion'])->name('ajax.product.loadProductPromotion');
    Route::get('/ajax/source/getAllSource', [AjaxSourceController::class, 'getAllSource'])->name('ajax.source.getAllSource');
});


Route::get('/admin', [AuthController::class, 'index'])->name('auth.admin')->middleware('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');


