<?php

use App\Models\Projek;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\ProjekController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\TransaksiController;

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
//route untuk get desa
Route::get('desa', [DesaController::class, 'getDesa']);
//route untuk detail desa
Route::get('desa/detail/{idDesa}', [TransaksiController::class, 'searchTransaksiByDesa']);
//route untuk get kecamatan
Route::get('kecamatan', [KecamatanController::class, 'index']);
//route untuk get kabupaten
Route::get('kabupaten', [KabupatenController::class, 'index']);
//route untuk get projek
Route::get('projek', [ProjekController::class, 'index']);


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
