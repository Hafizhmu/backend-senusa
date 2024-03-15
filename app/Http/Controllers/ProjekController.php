<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Http\Requests\StoreProjekRequest;
use App\Http\Requests\UpdateProjekRequest;

class ProjekController extends Controller
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
    public function store(StoreProjekRequest $request)
    {
        try {
            Projek::create([
                'nama' => $request->nama,
                'harga' => $request->harga
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
    public function show(Projek $projek)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projek $projek)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjekRequest $request, Projek $projek)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Projek $projek)
    {
        //
    }
}
