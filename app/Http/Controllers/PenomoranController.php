<?php
namespace App\Http\Controllers;

use App\Http\Resources\PenomoranResource;
use App\Models\Penomoran;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PenomoranController extends Controller
{
    // Get specific penomoran based on parameters
    public function getPenomoranOptions(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:transaksis,id_transaksi',
            'id_dokumen' => 'required|exists:penomorans,id_dokumen',
        ]);

        // Cari transaksi berdasarkan id_transaksi
        $transaksi = Transaksi::findOrFail($request->id_transaksi);

        // Ambil id_kabupaten, id_kecamatan, id_projek, dan id_perusahaan dari transaksi
        $id_kabupaten = $transaksi->id_kabupaten;
        $id_kecamatan = $transaksi->id_kecamatan;
        $id_projek = $transaksi->id_projek;
        $id_perusahaan = $transaksi->id_perusahaan;

        // Lakukan filtering pada tabel penomorans
        $penomorans = Penomoran::where(function ($query) use ($id_kabupaten) {
            $query->where('id_kabupaten', $id_kabupaten)
                ->orWhereNull('id_kabupaten');
        })->where(function ($query) use ($id_kecamatan) {
            $query->where('id_kecamatan', $id_kecamatan)
                ->orWhereNull('id_kecamatan');
        })->where(function ($query) use ($id_projek) {
            $query->where('id_projek', $id_projek)
                ->orWhereNull('id_projek');
        })->where(function ($query) use ($id_perusahaan) {
            $query->where('id_perusahaan', $id_perusahaan)
                ->orWhereNull('id_perusahaan');
        })->where(function ($query) use ($request) {
            $query->where('id_dokumen', $request->id_dokumen)
                ->orWhereNull('id_dokumen');
        })->get(['id', 'nama', 'current_number', 'last_number', 'format_penomoran']);

        return response()->json($penomorans);
    }

    // get penomoran by id
    public function getPenomoranById(Request $request)
    {
        $find = Penomoran::find($request->id);
        // Ambil data penomoran berdasarkan id_kabupaten

        if (!$find) {
            return response()->json(['message' => 'Data Tidak Ditemukan'], 400);
        }

        $penomorans = Penomoran::where('id', $find->id)->get();


        // Jika ada, kembalikan data penomoran dalam format JSON
        return response()->json($penomorans, 200);
    }


    // Get all penomorans
    public function index(Request $request)
    {
        $penomorans = Penomoran::select('*', 'dokumens.nama_dokumen as nama_dokumen')
            ->join('dokumens', 'dokumens.id', '=', 'penomorans.id_dokumen')
            ->paginate($request->data);

        return PenomoranResource::collection($penomorans);

    }
    // public function index(Request $request)
    // {
    //     $penomorans = Penomoran::paginate($request->data);
    //     return PenomoranResource::collection($penomorans);
    // }

    public function searchPenomoran(Request $request)
    {
        $keyword = $request->input('keyword');
        $kabupatens = Penomoran::where('nama', 'LIKE', "%$keyword%")
            ->orderBy('nama')
            ->paginate($request->data);


        return PenomoranResource::collection($kabupatens);
    }

    // Increment current_number
    public function incrementCurrentNumber(Request $request, $id)
    {
        $penomoran = Penomoran::findOrFail($id);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if ($penomoran->bulan != $bulan || $penomoran->tahun != $tahun) {
            return response()->json(['error' => 'Bulan atau tahun tidak sesuai'], 400);
        }

        if ($penomoran->current_number >= $penomoran->last_number) {
            return response()->json(['error' => 'Current number telah mencapai atau melebihi last number'], 400);
        }

        $penomoran->current_number++;
        $penomoran->save();

        return response()->json($penomoran);
    }

    // Update penomoran data
    public function update(Request $request, $id)
    {
        $penomoran = Penomoran::findOrFail($id);
        $penomoran->update($request->all());
        return response()->json($penomoran);
    }

    // Create new penomoran
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'current_number' => 'required|integer',
            'last_number' => 'required|integer',
            'format_penomoran' => 'required|string',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        $penomoran = Penomoran::create($request->all());
        return response()->json($penomoran, 201);
    }

    // Delete penomoran
    public function destroy($id)
    {
        $penomoran = Penomoran::findOrFail($id);
        $penomoran->delete();
        return response()->json(null, 204);
    }
}
