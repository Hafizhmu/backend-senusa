<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use Illuminate\Http\Request;
use App\Http\Resources\KabupatenResource;
use App\Http\Requests\StoreKabupatenRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\UpdateKabupatenRequest;


class KabupatenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lakukan paginasi pada kueri builder
        $kab = Kabupaten::paginate($request->data);

        // Gunakan KabupatenResource untuk mengubah koleksi data
        return KabupatenResource::collection($kab);
    }

    public function searchKab(Request $request)
    {
        $keyword = $request->input('keyword');
        $kabupatens = Kabupaten::where('kabupaten', 'LIKE', "%$keyword%")
            ->orderBy('kabupaten')
            ->paginate($request->data);


        return KabupatenResource::collection($kabupatens);
    }

    public function getKabupatenById(Request $request)
    {
        $find = Kabupaten::find($request->id);
        // Ambil data kabupaten berdasarkan id_kabupaten

        if (!$find) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 400);
        }

        $kabupatens = Kabupaten::where('id', $find->id)->get();


        // Jika ada, kembalikan data kecamatan dalam format JSON
        return response()->json($kabupatens, 200);
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
    public function store(StoreKabupatenRequest $request)
    {
        try {
            Kabupaten::create([
                'kabupaten' => 'Kabupaten '.$request->kabupaten
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
    public function show(Kabupaten $kabupaten)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kabupaten $kabupaten)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKabupatenRequest $request, $id)
    {
        try {
            $update = Kabupaten::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->kabupaten = $request->kabupaten;

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
            $delete = Kabupaten::find($id);
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
