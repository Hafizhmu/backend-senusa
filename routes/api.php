<?php

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesaController;
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

//route untuk get table overall
Route::get('index', [DesaController::class, 'index']);
//route untuk get table transaksi
Route::get('transaksi', [TransaksiController::class, 'index']);
//route untuk get id dan nama desa
Route::get('desa', [DesaController::class, 'getDesa']);
//route untuk get id dan nama desa
Route::get('desa/detail', [TransaksiController::class, 'searchTransaksiByDesa']);
