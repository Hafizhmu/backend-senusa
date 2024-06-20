<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Http\Resources\PerusahaanResource;
use App\Http\Requests\StorePerusahaanRequest;
use App\Http\Requests\UpdatePerusahaanRequest;

class PerusahaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cari = $request->input('keyword');
        if ($cari) {
            $perusahaan = Perusahaan::where('nama_perusahaan', 'LIKE', "%$cari%")
            ->paginate($request->data);
        } else {
            # code...
            $perusahaan = Perusahaan::paginate($request->data);
        }


        return PerusahaanResource::collection($perusahaan);
        // return response()->json(Perusahaan::paginate($request->has('data')),200);
    }

    public function getPerusahaanById(Request $request)
    {
        $find = Perusahaan::find($request->id);
        // Ambil data Perusahaan berdasarkan id_kabupaten

        if (!$find) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 400);
        }

        $perusahaan = Perusahaan::where('id', $find->id)->get();


        // Jika ada, kembalikan data kecamatan dalam format JSON
        return response()->json($perusahaan, 200);
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
    public function store(StorePerusahaanRequest $request)
    {
        try {
            Perusahaan::create([
                'nama_perusahaan' => $request->nama_perusahaan,
                'nama_direktur' => $request->nama_direktur,
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
    public function show(Perusahaan $perusahaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perusahaan $perusahaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePerusahaanRequest $request, $id)
    {
        try {
            $update = Perusahaan::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->nama_perusahaan = $request->nama_perusahaan;
            $update->nama_direktur = $request->nama_direktur;

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
            $delete = Perusahaan::find($id);
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
