<?php

namespace App\Http\Controllers;

use App\Models\Transaksi_Pajak;
use App\Http\Resources\Transaksi_PajakResource;
use App\Http\Requests\StoreTransaksi_PajakRequest;
use App\Http\Requests\UpdateTransaksi_PajakRequest;
use Illuminate\Http\Request;
use Validator;

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
    public function store(StoreTransaksi_PajakRequest $request)
    {
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

        try {
            $id_pajak = $request->id_pajak;
            $nominal = $request->nominal;
            // Iterasi melalui array id_pajak
            foreach ($id_pajak as $index => $key) {
                $data = array(
                    'id_transaksi' => $request->id_transaksi,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransaksi_PajakRequest $request, Transaksi_Pajak $transaksi_Pajak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi_Pajak $transaksi_Pajak)
    {
        //
    }
}
