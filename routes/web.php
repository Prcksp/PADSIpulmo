<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RpphController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', function() {
    return redirect('/dashboard');
});

Route::resource('/barangs', BarangController::class)->middleware('check.session');
Route::resource('/produks', ProdukController::class)->middleware('check.session');
Route::resource('/users', UserController::class)->middleware('check.session');
Route::resource('/customers', CustomerController::class)->middleware('check.session');


// Dashboard route with session check middleware
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('check.session');
// Other routes without middleware
Route::get('/permissions', [PermissionController::class, 'index'])->middleware('check.session');
Route::post('/permissions', [PermissionController::class, 'store'])->middleware('check.session');
Route::get('/permissions/{id}', [PermissionController::class, 'edit'])->middleware('check.session');
Route::patch('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('check.session');
Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('check.session');
Route::resource('/roles', RoleController::class)->middleware('check.session');


Route::resource('/rpph', RpphController::class)->middleware('check.session');





Route::get('rpph/{id}/cetak', [RpphController::class, 'cetak'])->name('rpph.cetak')->middleware('check.session');
