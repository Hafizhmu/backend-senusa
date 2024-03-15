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
Route::get('desa/detail/', [TransaksiController::class, 'searchTransaksiByDesa']);
//route untuk get kecamatan
Route::get('kecamatan', [KecamatanController::class, 'index']);
//route untuk get kabupaten
Route::get('kabupaten', [KabupatenController::class, 'index']);


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
