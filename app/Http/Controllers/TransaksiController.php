<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
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
        $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'kecamatans.kecamatan', 'kabupatens.kabupaten', 'projeks.harga')
            ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
            ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
            ->join('kecamatans', 'transaksis.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'transaksis.id_kabupaten', '=', 'kabupatens.id')
            ->orderBy('transaksis.id_transaksi')
            ->paginate(10);


        return TransaksiResource::collection($transaksis);
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
        //
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
