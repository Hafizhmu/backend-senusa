<?php

namespace App\Http\Controllers;

use App\Models\RiwayatCetak;
use App\Http\Requests\StoreRiwayatCetakRequest;
use App\Http\Requests\UpdateRiwayatCetakRequest;
use App\Http\Resources\RiwayatCetakResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RiwayatCetakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $riwayat = RiwayatCetak::where('id_transaksi', $request->input('id_transaksi'))
            ->orderByDesc('id')
            ->get();

        return RiwayatCetakResource::collection($riwayat);
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
    public function store(StoreRiwayatCetakRequest $request)
    {
        try {
            RiwayatCetak::create([
                'id_transaksi' => $request->id_transaksi,
                'jenis_dokumen' => $request->jenis_dokumen,
                'nama_pencetak' => $request->nama_pencetak,
                'tanggal' => Carbon::now()
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
    public function show(RiwayatCetak $riwayatCetak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RiwayatCetak $riwayatCetak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRiwayatCetakRequest $request, RiwayatCetak $riwayatCetak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RiwayatCetak $riwayatCetak)
    {
        //
    }
}
