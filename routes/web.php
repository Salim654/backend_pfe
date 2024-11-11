<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AccountantController;
use App\Http\Controllers\DashboardController;

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
//public routes
Route::get('/login', [AuthController::class, 'index'])->name('auth.login');
Route::post('/login', [AuthController::class, 'logadmin']);
Route::group(['middleware' => ['auth:sanctum']],function()
{
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    //country CRUD
    Route::get('/admin/countrys', [CountryController::class, 'showAll'])->name('admin.countrys');
    Route::post('/admin/countrys', [CountryController::class, 'store'])->name('country.store');  
    Route::get('/admin/countrys/{id}/edit', [CountryController::class, 'edit'])->name('country.edit');
    Route::put('/admin/countrys/{id}', [CountryController::class, 'update'])->name('country.update');
    Route::delete('/admin/countrys/{id}', [CountryController::class, 'delete'])->name('country.delete');
    //log out
    Route::post('/logout',[AuthController::class, 'logoutad']);
    //accountant
    Route::resource('/admin/accountants', AccountantController::class);
    //org CRUD
    Route::get('/admin/organizations', [OrganizationController::class, 'showAll'])->name('admin.organizations');
    Route::post('/admin/organizations', [OrganizationController::class, 'addorganization'])->name('organization.store');
    Route::put('/admin/organizations/{id}', [OrganizationController::class, 'update'])->name('organization.update');
    Route::delete('/admin/organizations/{id}', [OrganizationController::class, 'delete'])->name('organization.delete');
    //users Crud
    Route::get('/admin/users', [AuthController::class, 'showAll'])->name('admin.users');
    Route::post('/admin/users', [AuthController::class, 'adduser'])->name('user.store');
    Route::put('/admin/users/{id}', [AuthController::class, 'update'])->name('user.update');
    Route::delete('/admin/users/{id}', [AuthController::class, 'delete'])->name('user.delete');
    //change mdp user by admin 
    Route::post('admin/user/{id}/change-password', [AuthController::class, 'changePassword'])->name('user.change-password');


    //Admin
    Route::get('/user',[AuthController::class, 'user']);
    Route::post('/addUser',[AuthController::class,'addUser']);
    Route::post('/addOrg',[OrganizationController::class,'addorganization']);
    //Route::post('/logout',[AuthController::class, 'Logout']);

});
Route::get('/error', function () {
    return view('error');
})->name('error');
