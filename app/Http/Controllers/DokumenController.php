<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Http\Requests\StoreDokumenRequest;
use App\Http\Requests\UpdateDokumenRequest;
use Illuminate\Http\JsonResponse;

class DokumenController extends Controller
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
    public function store(StoreDokumenRequest $request)
    {
        try {
            $dokumen = $request->file('nama_dokumen');
            var_dump($dokumen);
            $nama_dokumen = 'FT' . date('Ymdhis') . '.' . $request->file('nama_dokumen')->getClientOriginalExtension();
            $dokumen->move('docs/', $nama_dokumen);
            Dokumen::create([
                'nama_dokumen' => $nama_dokumen
            ]);
            return response()->json([
                'message' => 'Data Berhasil ditambahkan'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Terjadi Kesalahan " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dokumen $dokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumen $dokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDokumenRequest $request, Dokumen $dokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumen $dokumen)
    {
        //
    }
}
