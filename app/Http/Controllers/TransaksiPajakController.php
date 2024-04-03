<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\Transaksi_Pajak;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Resources\Transaksi_PajakResource;
use App\Http\Requests\StoreTransaksi_PajakRequest;
use App\Http\Requests\UpdateTransaksi_PajakRequest;

class TransaksiPajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tp = Transaksi_Pajak::paginate($request->data);

        return Transaksi_PajakResource::collection($tp);
    }

    public function getTransById(Request $request)
    {
        $query = Transaksi_Pajak::query();
        if (!$query) {
            return response()->json(['message' => 'ID desa harus disediakan.'], 400);
        }

        $query->when($request->id_transaksi, function ($query) use ($request) {
            return $query->select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.status_pembayaran', 'status_kontrak', 'nominal', 'pajaks.jenis_pajak')
                ->join('transaksis', 'transaksi_pajaks.id_transaksi', '=', 'transaksis.id_transaksi')
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->join('pajaks', 'transaksi_pajaks.id_pajak', '=', 'pajaks.id')
                ->where('transaksi_pajaks.id_transaksi', $request->id_transaksi);
        });

        return response()->json($query->get(), 200);
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
    public function store(StoreTransaksi_PajakRequest $request, StoreTransaksiRequest $req)
    {

        try {
            $id_pajak = $request->id_pajak;
            $nominal = $request->nominal;

            // Membuat transaksi jika id_transaksi tersedia dalam request
            if (!$request->has('id_transaksi')) {
                $transaksi = Transaksi::create([
                    'id_projek' => $req->id_projek,
                    'id_desa' => $req->id_desa,
                    'harga' => $req->harga,
                    'status_kontrak' => $req->status_kontrak,
                    'status_pembayaran' => $req->status_pembayaran,
                    'tanggal_pembayaran' => $req->tanggal_pembayaran,
                    'tanggal_transaksi' => $req->tanggal_transaksi,
                    'id_perusahaan' => $req->id_perusahaan
                ]);
                $id_transaksi = DB::getPDO()->lastInsertId(); // Mengambil ID transaksi yang baru saja dibuat
            }

            // Jika id_transaksi tidak tersedia atau transaksi tidak dibuat, lanjutkan dengan mengambil ID dari request
            $id_transaksi = $request->input('id_transaksi') ?? $id_transaksi;

            // Iterasi melalui array id_pajak
            foreach ($id_pajak as $index => $key) {
                $data = array(
                    'id_transaksi' => $id_transaksi,
                    'id_pajak' => $key,
                    'nominal' => $nominal[$index]
                );
                Transaksi_Pajak::create($data);
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

        // try {
        //     $id_pajak = $request->id_pajak;
        //     $nominal = $request->nominal;
        //     Transaksi::create([
        //         'id_projek' => $req->id_projek,
        //         'id_desa' => $req->id_desa,
        //         'harga' => $req->harga,
        //         'status_kontrak' => $req->status_kontrak,
        //         'status_pembayaran' => $req->status_pembayaran,
        //         'tanggal_pembayaran' => $req->tanggal_pembayaran,
        //         'tanggal_transaksi' => $req->tanggal_transaksi
        //     ]);
        //     $id_transaksi = DB::getPDO()->lastInsertId();
        //     // Iterasi melalui array id_pajak
        //     foreach ($id_pajak as $index => $key) {
        //         $data = array(
        //             'id_transaksi' => $id_transaksi,
        //             'id_pajak' => $key,
        //             'nominal' => $nominal[$index]
        //         );
        //         Transaksi_Pajak::create($data);
        //     }


        //     // Response JSON
        //     return response()->json([
        //         'message' => 'Data berhasil ditambahkan'
        //     ], 200);
        // } catch (\Exception $e) {
        //     // Tangani kesalahan
        //     return response()->json([
        //         'message' => "Terjadi Kesalahan: " . $e->getMessage()
        //     ], 500);
        // }
        // try {
        //     Transaksi_Pajak::create([
        //         'id_projek' => $request->id_projek,
        //         'id_desa' => $request->id_desa,
        //         'id_pajak' => $request->id_pajak
        //     ]);

        //     //return response json
        //     return response()->json([
        //         'message' => 'Data Berhasil ditambahkan'
        //     ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'message' => "Terjadi Kesalahan" . $e->getMessage()
        //     ], 500);
        // }

        // try {
        //     $id_pajak = [$request->id_pajak];

        //     // Iterasi melalui array id_pajak
        //     foreach ($id_pajak as $key) {
        //         Transaksi_Pajak::create([
        //             'id_projek' => $request->id_projek,
        //             'id_desa' => $request->id_desa,
        //             'id_pajak' => $key,
        //         ]);
        //     }

        //     // Response JSON
        //     return response()->json([
        //         'message' => 'Data berhasil ditambahkan'
        //     ], 200);
        // } catch (\Exception $e) {
        //     // Tangani kesalahan
        //     return response()->json([
        //         'message' => "Terjadi Kesalahan: " . $e->getMessage()
        //     ], 500);
        // }


        // this will decode your json data that you're getting in the post data
        // $postdata = json_decode($request->all(), true);
        // $data = [];
        // foreach ($postdata as $key => $value) {
        //     $data[$key]['id_desa'] = $value[$request->id_desa];
        //     $data[$key]['id_projek'] = $value[$request->id_projek];
        //     $data[$key]['id_pajak'] = $value[$request->id_pajak];
        // }


        // $order = Transaksi_Pajak::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi_Pajak $transaksi_Pajak)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi_Pajak $transaksi_Pajak)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransaksi_PajakRequest $request)
    {

        $id_pajak = $request->id_pajak;
        $nominal = $request->nominal;
        $id_transaksi = $request->id_transaksi;
        $count = count(Transaksi_Pajak::where('id_transaksi', $id_transaksi)->get());
        var_dump($id_transaksi);
        $i = -1;
        var_dump('jmlh ' . count($id_pajak));
        // var_dump($nominal);
        var_dump('id_transaksi = ' . $id_transaksi);
        if (count($id_pajak) < $count || count($id_pajak) > $count) {
            try {
                Transaksi_Pajak::where('id_transaksi', $id_transaksi)->delete();
                foreach ($id_pajak as $index => $key) {
                    $data = array(
                        'id_transaksi' => $id_transaksi,
                        'id_pajak' => $key,
                        'nominal' => $nominal[$index]
                    );
                    Transaksi_Pajak::create($data);
                }
                return response()->json([
                    'message' => 'Data berhasil diperbarui'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => "Terjadi Kesalahan: " . $e->getMessage()
                ], 500);
            }
        } else {
            try {
                foreach ($id_pajak as $index) {
                    // Lakukan pencarian berdasarkan id_transaksi dan id_pajak
                    $update = Transaksi_Pajak::where('id_transaksi', $id_transaksi)->get();
                    // Jika entri sudah ada, update nilai nominal
                    $counter = count($update);
                    var_dump('jumlah loop yang akan terjadi = ' . $counter);
                    foreach ($update as $data) {
                        $i++;
                        // Update nilai nominal
                        $data->id_pajak = $request->id_pajak[$i];
                        $data->nominal = $request->nominal[$i];
                        $data->save();

                        var_dump('Loop ke - ' . $i);
                    }
                    return response()->json([
                        'message' => 'Data berhasil diperbarui'
                    ], 200);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message' => "Terjadi Kesalahan: " . $e->getMessage()
                ], 500);
            }
        }

        // $nominal = $request->get('nominal');
        // $id_pajak = $request->get('id_pajak');
        // $id_transaksi = $request->get('id_transaksi');

        // $find = Transaksi_Pajak::where('id_transaksi', '=', $id_transaksi)->get();
        // $counter = count($find);

        // for ($i = 0; $i < $counter; $i++) {
        //     $update = Transaksi_Pajak::where('id_transaksi', $id_transaksi[$i])->first();

        //     $update->update([
        //         'nominal' => $nominal[$i],
        //         'id_pajak' => $id_pajak[$i],
        //         'id_transaksi' => $id_transaksi
        //     ]);
        // }
        // return response()->json([
        //     'message' => 'Data berhasil diperbarui'
        // ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi_Pajak $transaksi_Pajak)
    {
        //
    }
}