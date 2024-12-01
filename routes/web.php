<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\RiwayatPenjualanController;
use App\Http\Controllers\LaporanPenjualanController;
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
Route::resource('/penjualans', PenjualanController::class)->middleware('check.session');
Route::resource('/pembelians', PembelianController::class)->middleware('check.session');
Route::resource('/riwayatpenjualans', RiwayatPenjualanController::class)->middleware('check.session');
Route::resource('/laporanpenjualans', LaporanPenjualanController::class)->middleware('check.session');
Route::post('/laporanpenjualans/generate', [LaporanPenjualanController::class, 'generateLaporan'])
    ->name('laporanpenjualans.generate')
    ->middleware('check.session');

Route::post('/laporanpenjualans/generate-monthly', [LaporanPenjualanController::class, 'generateLaporanBulanan'])
    ->name('laporanpenjualans.generateMonthly')
    ->middleware('check.session');
    // Monthly report route
Route::get('/pembelians/monthly', [PembelianController::class, 'monthlyReport'])->name('pembelians.monthly');

// Date range report route
Route::get('/pembelians/date-range', [PembelianController::class, 'dateRangeReport'])->name('pembelians.date-range');


Route::post('/penjualans/addCart', [PenjualanController::class, 'addCart'])->name('penjualans.addCart')->middleware('check.session');
Route::delete('penjualans/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualans.destroy');
Route::post('/penjualans/confirm', [PenjualanController::class, 'confirm'])->name('penjualans.confirm');
Route::get('/riwayatpenjualans/{kode_transaksi}', [RiwayatPenjualanController::class, 'getDetailTransaksi']);
Route::get('/penjualan/detail/{kode_transaksi}', [RiwayatPenjualanController::class, 'show'])->name('penjualan.detail');
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
