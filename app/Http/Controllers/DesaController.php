<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Http\Requests\StoreDesaRequest;
use App\Http\Requests\UpdateDesaRequest;
use App\Http\Resources\DesaResource;

class DesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'desas.id_kabupaten', '=', 'kabupatens.id')
            ->paginate(10);


        return DesaResource::collection($desa);
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
    public function store(StoreDesaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Desa $desa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Desa $desa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesaRequest $request, Desa $desa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desa $desa)
    {
        //
    }
}
