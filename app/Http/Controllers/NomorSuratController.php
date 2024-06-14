<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNomorRequest;
use Carbon\Carbon;
use App\Models\Desa;
use App\Models\nomor_surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NomorSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function generateSuratNumber()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNomorRequest $request, Request $req)
    {
        try {
            $desa = DB::table('desas')->pluck('id_desa', 'id_kecamatan')->where('id_kecamatan', $req->input('id_kecamatan'));
            $currentDate = Carbon::now();
            $year = $currentDate->year;
            $month = $currentDate->month;
            foreach ($desa as $key) {
                $id_desa = $key->id_desa;
                $kecamatanId = $key->id_kecamatan; // Asumsikan tabel 'villages' memiliki kolom 'kecamatan_id'

                // Cek apakah ada entry untuk desa, bulan, dan tahun ini
                $suratNumber = DB::table('surat_numbers')
                    ->where('id_desa', $id_desa)
                    ->where('id_kecamatan', $kecamatanId)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->orderBy('no_surat', 'desc')
                    ->first();

                if ($suratNumber) {
                    // Increment nomor surat terakhir
                    $lastSuratNumberParts = explode('/', $suratNumber->no_surat);
                    $lastNumber = end($lastSuratNumberParts);
                    $newNumber = (int)$lastNumber + 1;
                } else {
                    // Reset nomor surat jika bulan atau tahun berganti
                    $newNumber = 1;
                }

                // Format nomor surat sesuai kebutuhan
                $formattedNumber = sprintf('%04d', $newNumber); // Misalnya: 0001, 0002, dll
                $noSurat = "SURAT/{$id_desa}/{$year}/{$month}/{$formattedNumber}";

                // Insert nomor surat baru ke database
                DB::table('surat_numbers')->insert([
                    'id_desa' => $id_desa,
                    'id_kecamatan' => $kecamatanId,
                    'id_transaksi' => $request->id_transaksi, // Atur sesuai kebutuhan atau biarkan $request->
                    'id_dokumen' => $request->id_dokumen, // Atur sesuai kebutuhan atau biarkan $request->
                    'id_projek' => $request->id_dokumen, // Atur sesuai kebutuhan atau biarkan null
                    'no_surat' => $noSurat,
                    'created_at' => $currentDate,
                    'updated_at' => $currentDate
                ]);

                return response()->json([
                    'message' => 'Data berhasil ditambahkan'
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Terjadi Kesalahan" . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(nomor_surat $nomor_surat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(nomor_surat $nomor_surat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(nomor_surat $nomor_surat)
    {
        //
    }
}
