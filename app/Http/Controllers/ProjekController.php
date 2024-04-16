<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use App\Models\Projek;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\Transaksi_Pajak;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProjekResource;
use App\Http\Requests\StoreProjekRequest;
use App\Http\Requests\UpdateProjekRequest;
use App\Http\Requests\StoreTransaksiRequest;

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

    public function searchProjek(Request $request)
    {
        $keyword = $request->input('keyword');
        $projek = Projek::where('nama', 'LIKE', "%$keyword%")
            ->orderBy('nama')
            ->paginate($request->data);

        return ProjekResource::collection($projek);
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
    public function bulkTrans(StoreTransaksiRequest $request)
    {
        $id_desa = $request->id_desa;
        try {
            foreach ($id_desa as $index => $key) {
                $data = array(
                    'id_projek' => $request->id_projek,
                    'id_desa' => $request->id_desa[$index],
                    'harga' => $request->harga,
                    'status_kontrak' => $request->status_kontrak,
                    'status_pembayaran' => $request->status_pembayaran,
                    'tanggal_pembayaran' => $request->input('tanggal_pembayaran'),
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'id_perusahaan' => $request->id_perusahaan
                );
                Transaksi::create($data);
                $id_transaksi = DB::getPdo()->lastInsertId();
                var_dump($id_transaksi);
                $id_pajak = $request->id_pajak;
                $nominal = $request->nominal;
                if ($request->has('id_pajak')) {
                    var_dump('id pajak = ', $id_pajak);
                    foreach ($id_desa as $index => $key) {
                        $dataTransaksiPajak = [
                            'id_transaksi' => $id_transaksi,
                            'id_pajak' => $id_pajak[$index],
                            'nominal' => $nominal[$index]
                            // Tambahkan data lainnya untuk transaksi pajak sesuai kebutuhan
                        ];

                        // Membuat transaksi pajak baru
                        Transaksi_Pajak::create($dataTransaksiPajak);
                    }
                } else {
                    foreach ($id_desa as $index => $key) {
                        $dataTransaksiPajak = [
                            'id_transaksi' => $id_transaksi
                            // Tambahkan data lainnya untuk transaksi pajak sesuai kebutuhan
                        ];

                        // Membuat transaksi pajak baru
                        Transaksi_Pajak::create($dataTransaksiPajak);
                    }
                }
            }

            // Response JSON
            return response()->json([
                'message' => 'Data berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan
            return response()->json([
                'message' => "Terjadi Kesalahan: " . $e->getMessage()
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
