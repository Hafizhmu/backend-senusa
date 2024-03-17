<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Resources\TransaksiResource;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.id_desa', 'desas.nama_desa', 'kecamatans.kecamatan', 'kabupatens.kabupaten', 'projeks.harga', 'transaksis.status_kontrak', 'transaksis.status_pembayaran')
            ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
            ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
            ->join('kecamatans', 'transaksis.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'transaksis.id_kabupaten', '=', 'kabupatens.id')
            ->orderBy('transaksis.id_transaksi')
            ->paginate(10);


        return TransaksiResource::collection($transaksis);
    }
    public function searchTransaksiByDesa(Request $request, $id_desa)
    {
        // Ambil ID desa dari request
        $find = Desa::find($id_desa);

        // Cek jika ID desa telah diberikan
        if ($find) {
            // Mengambil data transaksi yang dilakukan di desa dengan ID tertentu
            $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'kecamatans.kecamatan', 'kabupatens.kabupaten', 'projeks.harga', 'desas.alamat')
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->join('kecamatans', 'transaksis.id_kecamatan', '=', 'kecamatans.id')
                ->join('kabupatens', 'transaksis.id_kabupaten', '=', 'kabupatens.id')
                ->where('transaksis.id_desa', $find->id_desa)
                ->get();

            return TransaksiResource::collection($transaksis);
        } else {
            // Jika ID desa tidak diberikan, kembalikan pesan kesalahan
            return response()->json(['message' => 'ID desa harus disediakan.'], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransaksiRequest $request)
    {
        try {
            Transaksi::create([
                'id_projek' => $request->id_projek,
                'id_desa' => $request->id_desa,
                'id_kecamatan' => $request->id_kecamatan,
                'id_kabupaten' => $request->id_kabupaten,
                'status_kontrak' => $request->status_kontrak,
                'status_pembayaran' => $request->status_pembayaran
            ]);

            //return response json
            return response()->json([
                'message' => 'Data Berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Terjadi Kesalahan" . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
