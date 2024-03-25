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
        //
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
    public function update(UpdatePajakRequest $request, Pajak $pajak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pajak $pajak)
    {
        //
    }
}
