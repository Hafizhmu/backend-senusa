<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Http\Requests\StoreProjekRequest;
use App\Http\Requests\UpdateProjekRequest;
use App\Http\Resources\ProjekResource;
use Illuminate\Http\Request;

class ProjekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $get =  Projek::all();

        return ProjekResource::collection($get);
    }

    public function getProjekById(Request $request)
    {
        $find = Projek::find($request->id);
        // Ambil data Projek berdasarkan id_kabupaten

        if (!$find) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 400);
        }

        $projeks = Projek::where('id_projek', $find->id_projek)->get();


        // Jika ada, kembalikan data kecamatan dalam format JSON
        return response()->json($projeks, 200);
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
    public function update(UpdateProjekRequest $request, $id)
    {
        try {
            $update = Projek::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->nama = $request->nama;

            $update->save();

            // Return success response
            return response()->json([
                'message' => 'Data berhasil diperbarui'
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data', $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $delete = Projek::find($id);
            if (!$delete) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $delete->delete();

            // Return success response
            return response()->json([
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data', $e->getMessage()
            ], 500);
        }
    }
}
