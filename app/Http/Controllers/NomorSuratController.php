<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\nomor_surat;
use Illuminate\Http\Request;
use App\Http\Requests\Storenomor_suratRequest;
use App\Http\Requests\Updatenomor_suratRequest;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $desa = DB::table('desas')
            ->where('id_kecamatan', $req->input('id_kecamatan'))
            ->pluck('id_desa');
        // var_dump($desa);

        foreach ($desa as $id_desa) {
            var_dump('Id desa : ' . $id_desa);
            // mavs in 5;

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
    public function update(Updatenomor_suratRequest $request, nomor_surat $nomor_surat)
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
