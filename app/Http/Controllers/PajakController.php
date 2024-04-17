<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use Illuminate\Http\Request;
use App\Http\Resources\PajakResource;
use App\Http\Requests\StorePajakRequest;
use App\Http\Requests\UpdatePajakRequest;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cari = $request->input('keyword');
        if ($cari) {
            $pajak = Pajak::where('jenis_pajak', 'LIKE', "%$cari%")
                ->paginate($request->data);
        } else {
            # code...
            $pajak = Pajak::paginate($request->data);
        }


        return PajakResource::collection($pajak);
    }
    public function getPajakById(Request $request)
    {
        $find = Pajak::find($request->id);
        // Ambil data Perusahaan berdasarkan id_kabupaten

        if (!$find) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 400);
        }

        $perusahaan = Pajak::where('id', $find->id)->get();


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
    public function store(StorePajakRequest $request)
    {
        try {
            Pajak::create([
                'jenis_pajak' => $request->jenis_pajak
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
    public function show(Pajak $pajak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pajak $pajak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePajakRequest $request, $id)
    {
        try {
            $update = Pajak::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->jenis_pajak = $request->jenis_pajak;

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
            $delete = Pajak::find($id);
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
