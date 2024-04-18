<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\TransaksiPajakController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//method GET
//route untuk get table overall
Route::get('index', [DesaController::class, 'index']);
//route untuk get table transaksi
Route::get('transaksi', [TransaksiController::class, 'index']);
//route untuk get table transaksi
Route::get('transaksiById', [TransaksiController::class, 'searchTransaksiById']);
//pdf
Route::get('kontrak/pdf', [TransaksiController::class, 'pdfKontrak'])->name('transactions.pdf');
//pdf
Route::get('invoice/pdf', [TransaksiController::class, 'pdfInvoice'])->name('transactions.pdf');
//pdf
Route::get('kwitansi/pdf', [TransaksiController::class, 'pdfKwitansi'])->name('transactions.pdf');
//route untuk get desa
Route::get('desa', [DesaController::class, 'getDesa']);
//route untuk get desa by ID
// Route::get('desa', [DesaController::class, 'searchDesa']);
//route untuk get desa by ID
Route::get('desa/{id}', [DesaController::class, 'getDesaById']);
//route untuk get filter desa by kecamatan and kabupaten
Route::get('desaFilter', [DesaController::class, 'filterDesa']);
//route untuk detail desa
Route::get('desa/detail/{idDesa}', [TransaksiController::class, 'searchTransaksiByDesa']);
//route untuk get kecamatan
Route::get('kecamatanFiltered', [KecamatanController::class, 'filterParams']);
//route untuk get kecamatan
Route::get('kecamatan', [KecamatanController::class, 'index']);
//route untuk get kecamatan
// Route::get('kecamatan', [KecamatanController::class, 'searchKec']);
//route untuk get kecamatan
Route::get('getKecamatan', [KecamatanController::class, 'getKecamatanById']);
//route untuk get kabupaten
Route::get('kabupaten', [KabupatenController::class, 'index']);
//route untuk get kabupaten
Route::get('kabupaten', [KabupatenController::class, 'searchKab']);
//route untuk get kabupaten
Route::get('getKabupaten', [KabupatenController::class, 'getKabupatenById']);
//route untuk get perusahaan
Route::get('perusahaan', [PerusahaanController::class, 'index']);
//route untuk get perusahaan
Route::get('getPerusahaan', [PerusahaanController::class, 'getPerusahaanById']);
//route untuk get projek
Route::get('projek', [ProjekController::class, 'index']);
//route untuk get projek
Route::get('projek', [ProjekController::class, 'searchProjek']);
//route untuk get projek
Route::get('getProjek', [ProjekController::class, 'getProjekById']);
//route untuk get projek
Route::get('pajak', [PajakController::class, 'index']);
//route untuk get projek
Route::get('getPajakById', [PajakController::class, 'getPajakById']);
//route untuk get projek
Route::get('transaksiPajak', [TransaksiPajakController::class, 'index']);
//route untuk get projek
Route::get('getTransaksiPajak', [TransaksiPajakController::class, 'getTransById']);


//method POST
//route untuk add desa
Route::post('add/desa', [DesaController::class, 'store']);
//route untuk add kecamatan
Route::post('add/kecamatan', [KecamatanController::class, 'store']);
//route untuk add kecamatan
Route::post('add/kabupaten', [KabupatenController::class, 'store']);
//route untuk add kecamatan
Route::post('add/projek', [ProjekController::class, 'store']);
//route untuk add kecamatan
Route::post('add/transaksi', [TransaksiController::class, 'store']);
//route untuk add perusahaan
Route::post('add/perusahaan', [PerusahaanController::class, 'store']);
//route untuk add pajak
Route::post('add/pajak', [PajakController::class, 'store']);
//route untuk add perusahaan
Route::post('add/transaksiPajak', [TransaksiPajakController::class, 'store']);
//route untuk add perusahaan
Route::post('add/projekTransaksi', [ProjekController::class, 'bulkTrans']);
//route untuk add perusahaan
Route::post('add/dokumen', [DokumenController::class, 'store']);



//method PUT
//route untuk update desa
Route::put('update/desa/{id}', [DesaController::class, 'update']);
//route untuk update kecamatan
Route::put('update/kecamatan/{id}', [KecamatanController::class, 'update']);
//route untuk update kabupaten
Route::put('update/kabupaten/{id}', [KabupatenController::class, 'update']);
//route untuk update projek
Route::put('update/projek/{id}', [ProjekController::class, 'update']);
//route untuk update transaksi
Route::put('update/transaksi/{id}', [TransaksiController::class, 'update']);
//route untuk update perusahaan
Route::put('update/perusahaan/{id}', [PerusahaanController::class, 'update']);
//route untuk update perusahaan
Route::put('update/pajak/{id}', [PajakController::class, 'update']);
//route untuk update perusahaan
Route::put('update/transaksiPajak', [TransaksiPajakController::class, 'update']);

//method DELETE
//route untuk mengapus desa
Route::delete('delete/desa/{id}', [DesaController::class, 'destroy']);
//route untuk mengapus kecamatan
Route::delete('delete/kecamatan/{id}', [KecamatanController::class, 'destroy']);
//route untuk mengapus kabupaten
Route::delete('delete/kabupaten/{id}', [KabupatenController::class, 'destroy']);
//route untuk mengapus projek
Route::delete('delete/projek/{id}', [ProjekController::class, 'destroy']);
//route untuk mengapus transaksi
Route::delete('delete/transaksi/{id}', [TransaksiController::class, 'destroy']);
//route untuk mengapus transaksi
Route::delete('delete/perusahaan/{id}', [PerusahaanController::class, 'destroy']);
//route untuk mengapus transaksi
Route::delete('delete/pajak/{id}', [PajakController::class, 'destroy']);
