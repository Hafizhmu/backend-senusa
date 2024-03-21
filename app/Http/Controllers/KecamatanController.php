<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Http\Resources\KecamatanResource;
use App\Http\Requests\StoreKecamatanRequest;
use App\Http\Requests\UpdateKecamatanRequest;
use App\Models\Kabupaten;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lakukan paginasi pada kueri builder
        $kec = Kecamatan::paginate($request->data);
        return KecamatanResource::collection($kec);
    }

    public function filter($id_kabupaten)
    {
        // Ambil data kecamatan berdasarkan id_kabupaten
        $kecamatans = Kecamatan::select('kecamatans.id as id_kecamatan', 'kecamatans.kecamatan', 'kabupatens.id as id_kabupaten', 'kabupatens.kabupaten')
            ->join('kabupatens', 'kabupatens.id', '=', 'kecamatans.id_kabupaten')
            ->where('kabupatens.id', $id_kabupaten)
            ->get();

        // Periksa apakah ada kecamatan yang ditemukan
        if ($kecamatans->isEmpty()) {
            return response()->json(['message' => 'Tidak ada kecamatan yang ditemukan untuk kabupaten ini'], 404);
        }

        // Jika ada, kembalikan data kecamatan dalam format JSON
        return response()->json($kecamatans, 200);
    }
    public function getKecamatanById($id)
    {
        $find = Kecamatan::find($id);
        
        if (!$find) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 400);
        }

        $kecamatans = Kecamatan::where('id', $find->id)->get();


        // Jika ada, kembalikan data kecamatan dalam format JSON
        return response()->json($kecamatans, 200);
    }

    public function filterParams(Request $request)
    {
        $filter = Kecamatan::query();

        $filter->when($request->id_kabupaten, function ($query) use ($request) {
            return $query->select('kecamatans.id as id_kecamatan', 'kecamatans.kecamatan', 'kabupatens.id as id_kabupaten', 'kabupatens.kabupaten')
                ->join('kabupatens', 'kabupatens.id', '=', 'kecamatans.id_kabupaten')
                ->where('kabupatens.id', $request->id_kabupaten);
        });

        // Periksa apakah ada kecamatan yang ditemukan
        // if ($request->id_kabupaten->isEmpty()) {
        //     return response()->json(['message' => 'Tidak ada kecamatan yang ditemukan untuk kabupaten ini'], 404);
        // }

        // Jika ada, kembalikan data kecamatan dalam format JSON
        return response()->json($filter->get(), 200);
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
    public function store(StoreKecamatanRequest $request)
    {
        try {
            Kecamatan::create([
                'id_kabupaten' => $request->id_kabupaten,
                'kecamatan' => $request->kecamatan
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
    public function show(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKecamatanRequest $request, $id)
    {
        try {
            $update = Kecamatan::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->id_kabupaten = $request->id_kabupaten;
            $update->kecamatan = $request->kecamatan;

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
            $delete = Kecamatan::find($id);
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
