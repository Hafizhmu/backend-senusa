<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;
use App\Http\Resources\DesaResource;
use App\Http\Requests\StoreDesaRequest;
use App\Http\Requests\UpdateDesaRequest;

class DesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'desas.id_kabupaten', '=', 'kabupatens.id')
            
            ->paginate(10);


        return DesaResource::collection($desa);
    }

    public function getDesa()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $desa = Desa::paginate(10);


        return DesaResource::collection($desa);
    }

    public function getDesaById($id){
        $find = Desa::find($id);

        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat','desas.telepon', 'desas.nama_kades', 'kecamatans.id','kecamatans.kecamatan', 'kabupatens.id','kabupatens.kabupaten')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'desas.id_kabupaten', '=', 'kabupatens.id')
            ->where('desas.id_desa', $find->id_desa)
            ->get();


        return response()->json($desa, 200);
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
        try {
            Desa::create([
                'nama_desa' => $request->nama_desa,
                'nama_kades' => $request->nama_kades,
                'id_kecamatan' => $request->id_kecamatan,
                'id_kabupaten' => $request->id_kabupaten,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon
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
    public function update(UpdateDesaRequest $request, $id)
    {
        try {
            $update = Desa::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->nama_desa = $request->nama_desa;
            $update->nama_kades = $request->nama_kades;
            $update->id_kecamatan = $request->id_kecamatan;
            $update->id_kabupaten = $request->id_kabupaten;
            $update->alamat = $request->alamat;
            $update->telepon = $request->telepon;

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
            $delete = Desa::find($id);
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
