<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\TvaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TaxeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FactureprodController;
use App\Http\Controllers\AccountantController;


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

//public routes

Route::post('/login',[AuthController::class,'login']);
//protected routes 
Route::group(['middleware' => ['auth:sanctum']],function()
{

    //User
    Route::get('/user',[AuthController::class, 'user']);
    Route::put('/user',[AuthController::class, 'userEdit']);
    Route::post('/logout',[AuthController::class, 'Logout']);
    //Clients
    Route::post('/clients', [ClientsController::class, 'store']);
    Route::get('/clients', [ClientsController::class, 'getClientsForUser']);
    Route::get('/clients/{id}', [ClientsController::class, 'show']);
    Route::put('/clients/{id}', [ClientsController::class, 'update']);
    Route::delete('/clients/{id}', [ClientsController::class, 'destroy']);
    // Categories
    Route::post('/categorys', [CategoryController::class, 'store']);
    Route::get('/categorys', [CategoryController::class, 'getCategoriesForUser']);
    Route::get('/categorys/{id}', [CategoryController::class, 'show']);
    Route::put('/categorys/{id}', [CategoryController::class, 'update']);
    Route::delete('/categorys/{id}', [CategoryController::class, 'destroy']);

    // Brands
    Route::post('/brands', [BrandController::class, 'store']);
    Route::get('/brands', [BrandController::class, 'getBrandsForUser']);
    Route::get('/brands/{id}', [BrandController::class, 'show']);
    Route::put('/brands/{id}', [BrandController::class, 'update']);
    Route::delete('/brands/{id}', [BrandController::class, 'destroy']);
    //tva
    Route::post('/tvas', [TvaController::class, 'store']);
    Route::get('/tvas', [TvaController::class, 'getTvasForUser']);
    Route::get('/tvas/{id}', [TvaController::class, 'show']);
    Route::put('/tvas/{id}', [TvaController::class, 'update']);
    Route::delete('/tvas/{id}', [TvaController::class, 'destroy']);
    //product
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products', [ProductController::class, 'getProductsForUser']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    // get countrys
    Route::get('/countrys', [CountryController::class, 'index']);
    //get accountants 
    Route::get('/accountants', [AccountantController::class, 'getemailsaccountant']);
    
    //get organiztion
    //Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations', [OrganizationController::class, 'index']);
    //  taxes
    Route::post('/taxes', [TaxeController::class, 'store']);
    Route::get('/taxes', [TaxeController::class, 'getTaxesForUser']);
    Route::get('/taxes/{id}', [TaxeController::class, 'show']);
    Route::put('/taxes/{id}', [TaxeController::class, 'update']);
    Route::delete('/taxes/{id}', [TaxeController::class, 'destroy']);
    //factures 

    //get invoices
    Route::get('/invoices', [FactureController::class, 'getInvoicesForUser']);
    //get Po
    Route::get('/estimates', [FactureController::class, 'getEstimatesForUser']);
    //get estimate
    Route::get('/purchases', [FactureController::class, 'getPurchaseOrdersForUser']);

    Route::post('/factures', [FactureController::class, 'store']);
    Route::get('/factures/{id}', [FactureController::class, 'show']);
    Route::put('/factures/{id}', [FactureController::class, 'update']);
    Route::delete('/factures/{id}', [FactureController::class, 'destroy']);
    //Produits_factures
    //for  
    Route::get('/factures/{facture_id}/factureprods', [FactureprodController::class, 'index']);
    Route::post('/factures/{facture_id}/factureprods', [FactureprodController::class, 'store']);
    Route::get('/factures/{facture_id}/factureprods/{id}', [FactureprodController::class, 'show']);
    Route::put('/factures/{facture_id}/factureprods/{id}', [FactureprodController::class, 'update']);
    Route::delete('/factures/{facture_id}/factureprods/{id}', [FactureprodController::class, 'destroy']);

    
    

    

});


