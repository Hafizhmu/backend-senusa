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
    public function index(Request $request)
    {
        $query = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id');

        $query->when($request->has('kecamatan'), function ($query) use ($request) {
            return $query->where('kecamatans.id', $request->kecamatan);
        });

        // Menambahkan kondisi kabupaten jika tersedia
        $query->when($request->has('kabupaten'), function ($query) use ($request) {
            return $query->where('kabupatens.id', $request->kabupaten);
        });

        // Menambahkan kondisi kabupaten&kecamatan jika tersedia
        $query->when($request->has('kabupaten') && $request->has('kecamatan'), function ($query) use ($request) {
            return $query->where('kabupatens.id', $request->kabupaten)
                ->where('kecamatans.id', $request->kecamatan);
        });

        // Menambahkan kondisi pencarian berdasarkan keyword
        $query->when($request->has('keyword'), function ($query) use ($request) {
            $keyword = $request->keyword;
            return $query->where(function ($query) use ($keyword) {
                $query->where('desas.nama_desa', 'LIKE', "%$keyword%");
            });
        });

        $query->when($request->has('kecamatan') && $request->has('keyword'), function ($query) use ($request) {
            $keyword = $request->keyword;
            return $query->where('kecamatans.id', $request->kecamatan)
                ->where('desas.nama_desa', 'LIKE', "%$keyword%");
        });

        $query->when($request->has('kabupaten') && $request->has('keyword'), function ($query) use ($request) {
            $keyword = $request->keyword;
            return $query->where('kabupatens.id', $request->kabupaten)
                ->where('desas.nama_desa', 'LIKE', "%$keyword%");
        });

        $query->when($request->has('kecamatan') && $request->has('kabupaten') && $request->has('keyword'), function ($query) use ($request) {
            $keyword = $request->keyword;
            return $query->where('kabupatens.id', $request->kabupaten)
                ->where('kecamatans.id', $request->kecamatan)
                ->where('desas.nama_desa', 'LIKE', "%$keyword%");
        });


        $desa = $query->paginate($request->data);

        return DesaResource::collection($desa);
        // $id_kabupaten = $request->id_kabupaten;
        // $id_kecamatan = $request->id_kecamatan;
        // $keyword = $request->input('keyword');
        // if ($keyword) {
        //     $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
        //         ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
        //         ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
        //         ->where('nama_desa', 'LIKE', "%$keyword%")
        //         ->orderBy('nama_desa')
        //         ->paginate($request->data);
        // } elseif ($id_kabupaten) {
        //     $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
        //         ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
        //         ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
        //         ->where('kecamatans.id_kabupaten', $id_kabupaten)
        //         ->orderBy('nama_desa')
        //         ->paginate($request->data);
        // } elseif ($id_kecamatan) {
        //     $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
        //         ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
        //         ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
        //         ->where('id_kecamatan', $id_kecamatan)
        //         ->orderBy('nama_desa')
        //         ->paginate($request->data);
        // } else {
        //     //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        //     $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
        //         ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
        //         ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
        //         ->paginate($request->data);
        // }
        // Menambahkan kondisi kecamatan jika tersedia
    }

    public function searchDesa(Request $request)
    {
        if ($request->has('keyword')) {
            # code...
            $keyword = $request->input('keyword');
            $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
                ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
                ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
                ->where('nama_desa', 'LIKE', "%$keyword%")
                ->orderBy('nama_desa')

                ->paginate($request->data);
        }


        return DesaResource::collection($desa);
    }

    public function getDesa(Request $request)
    {
        $cari = $request->input('keyword');
        if ($cari) {
            $desa = Desa::where('nama_desa', 'LIKE', "%$cari%")
                ->paginate($request->data);
        } else {
            $desa = Desa::paginate($request->data);
        }

        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten


        return response()->json($desa, 200);
    }

    public function filterDesa(Request $request)
    {
        $query = Desa::select('desas.id_desa as id_desa', 'desas.nama_desa', 'desas.nama_kades', 'kecamatans.kecamatan', 'kabupatens.kabupaten')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id');

        // Menambahkan kondisi kecamatan jika tersedia
        $query->when($request->has('kecamatan'), function ($query) use ($request) {
            return $query->where('kecamatans.id', $request->kecamatan);
        });

        // Menambahkan kondisi kabupaten jika tersedia
        $query->when($request->has('kabupaten'), function ($query) use ($request) {
            return $query->where('kabupatens.id', $request->kabupaten);
        });

        // Menambahkan kondisi kabupaten&kecamatan jika tersedia
        $query->when($request->has('kabupaten') && $request->has('kecamatan'), function ($query) use ($request) {
            return $query->where('kabupatens.id', $request->kabupaten)
                ->where('kecamatans.id', $request->kecamatan);
        });

        $desa = $query->get();
        return response()->json($desa, 200);


        // return DesaResource::collection($desa);
    }

    public function getDesaById(Request $request)
    {
        $find = Desa::find($request->input('id_desa'));

        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $desa = Desa::select('desas.id_desa', 'desas.nama_desa', 'desas.alamat', 'desas.telepon', 'desas.nama_kades', 'kecamatans.id as id_kecamatan', 'kecamatans.kecamatan', 'kabupatens.id as id_kabupaten', 'kabupatens.kabupaten')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
            ->where('desas.id_desa', $find->id_desa)
            ->get();


        return response()->json($desa, 200);
    }

    public function getDoc(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:transaksis,id_transaksi' // Pastikan id_transaksi ada dalam tabel transaksis
        ]);

        // Ambil id_transaksi dari request
        $id_transaksi = $request->input('id_transaksi');

        // Ambil data sesuai dengan id_transaksi
        $data = Desa::select(
            'desas.nama_desa',
            'kecamatans.kecamatan',
            'kabupatens.kabupaten',
            'perusahaans.nama_perusahaan',
            'desas.nama_kades',
            'perusahaans.nama_direktur',
            'transaksis.harga',
            'transaksis.tanggal_transaksi'
        )
            ->join('transaksis', 'desas.id_desa', '=', 'transaksis.id_desa')
            ->join('perusahaans', 'transaksis.id_perusahaan', '=', 'perusahaans.id')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
            ->where('transaksis.id_transaksi', $id_transaksi)
            ->get(); // Menggunakan first() untuk mendapatkan model tunggal

        // Periksa apakah data ditemukan
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($data, 200);
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
