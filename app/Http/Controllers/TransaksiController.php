<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Desa;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Resources\TransaksiResource;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.id_desa', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'), 'transaksis.status_kontrak', 'transaksis.status_pembayaran')
            ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
            ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
            ->orderBy('transaksis.id_transaksi')
            ->paginate(10);


        return TransaksiResource::collection($transaksis);
    }
    public function searchTransaksiByDesa(Request $request, $id_desa)
    {
        // Ambil ID desa dari request
        $find = Desa::find($id_desa);

        // Cek jika ID desa telah diberikan
        if ($find) {
            // Mengambil data transaksi yang dilakukan di desa dengan ID tertentu
            $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'))
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->where('transaksis.id_desa', $find->id_desa)
                ->get();

            return TransaksiResource::collection($transaksis);
        } else {
            // Jika ID desa tidak diberikan, kembalikan pesan kesalahan
            return response()->json(['message' => 'ID desa harus disediakan.'], 400);
        }
    }

    public function searchTransaksiById(Request $request)
    {

        $query = Transaksi::query();
        if (!$query) {
            return response()->json(['message' => 'ID desa harus disediakan.'], 400);
        }

        $query->when($request->id_transaksi, function ($query) use ($request) {
            return $query->select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'), 'transaksis.status_pembayaran', 'status_kontrak')
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->where('transaksis.id_transaksi', $request->id_transaksi);
        });

        return response()->json($query->get(), 200);
        // Ambil ID desa dari request
        // $find = Transaksi::find($id_transaksi);

        // // Cek jika ID desa telah diberikan
        // if ($find) {
        //     // Mengambil data transaksi yang dilakukan di desa dengan ID tertentu
        //     $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'), 'transaksis.status_pembayaran', 'status_kontrak')
        //         ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
        //         ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
        //         ->where('transaksis.id_transaksi', $find->id_transaksi)
        //         ->get();

        //     return response()->json($transaksis, 200);
        // } else {
        //     // Jika ID desa tidak diberikan, kembalikan pesan kesalahan
        //     return response()->json(['message' => 'ID desa harus disediakan.'], 400);
        // }
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
    public function store(StoreTransaksiRequest $request)
    {
        try {
            Transaksi::create([
                'id_projek' => $request->id_projek,
                'id_desa' => $request->id_desa,
                'harga' => $request->harga,
                'ppn' => $request->ppn,
                'pph' => $request->pph,
                'status_kontrak' => $request->status_kontrak,
                'status_pembayaran' => $request->status_pembayaran
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
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransaksiRequest $request, $id)
    {
        try {
            $update = Transaksi::find($id);
            if (!$update) {
                return response()->json([
                    'message' => "Data dengan ID $id tidak ditemukan"
                ], 404);
            }

            $update->harga = $request->harga;
            $update->ppn = $request->ppn;
            $update->pph = $request->pph;
            $update->status_kontrak = $request->status_kontrak;
            $update->status_pembayaran = $request->status_pembayaran;

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
            $delete = Transaksi::find($id);
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
