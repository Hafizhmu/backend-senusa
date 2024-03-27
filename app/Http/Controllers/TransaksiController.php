<?php

namespace App\Http\Controllers;

use DB;
use mPDF;
use Dompdf\Dompdf;
use App\Models\Desa;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\TransaksiResource;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use Mpdf\Mpdf as MpdfMpdf;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $query = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', 'transaksis.status_pembayaran', 'transaksis.status_kontrak', 'transaksis.ppn', 'transaksis.pph', 'transaksis.status_pembayaran', 'transaksis.status_kontrak', 'transaksis.id_pajak', 'pajaks.jenis_pajak', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'))
            ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
            ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
            ->join('pajaks', 'transaksis.id_pajak', '=', 'pajaks.id')
            ->join('kecamatans', 'desas.id_kecamatan', '=', 'kecamatans.id')
            ->join('kabupatens', 'kecamatans.id_kabupaten', '=', 'kabupatens.id')
            ->orderBy('transaksis.id_transaksi');

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

        $transaksis = $query->paginate($request->data);
        return TransaksiResource::collection($transaksis);
    }

    public function searchTrans(Request $request)
    {
        $keyword = $request->input('keyword');
        $transaksi = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', 'transaksis.ppn', 'transaksis.pph', 'transaksis.status_pembayaran', 'transaksis.status_kontrak', 'transaksis.id_pajak', 'pajaks.jenis_pajak', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'), 'transaksis.status_pembayaran', 'status_kontrak')
            ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
            ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
            ->join('pajaks', 'transaksis.id_pajak', '=', 'pajaks.id')
            ->where('desas.nama_desa', 'LIKE', "%$keyword%")
            ->orderBy('desas.nama_desa')
            ->paginate($request->data);

        return TransaksiResource::collection($transaksi);
    }


    public function searchTransaksiByDesa(Request $request, $id_desa)
    {
        // Ambil ID desa dari request
        $find = Desa::find($id_desa);

        // Cek jika ID desa telah diberikan
        if ($find) {
            // Mengambil data transaksi yang dilakukan di desa dengan ID tertentu
            $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', 'transaksis.status_pembayaran', 'transaksis.status_kontrak', 'transaksis.id_pajak', 'pajaks.jenis_pajak', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'))
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->join('pajaks', 'transaksis.id_pajak', '=', 'pajaks.id')
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
            return $query->select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.ppn', 'transaksis.pph', 'transaksis.id_pajak', 'pajaks.id', DB::raw('transaksis.harga + (transaksis.harga * transaksis.ppn / 100) + (transaksis.harga * transaksis.pph / 100) as harga_total'), 'transaksis.status_pembayaran', 'status_kontrak')
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->join('pajaks', 'transaksis.id_pajak', '=', 'pajaks.id')
                ->where('transaksis.id_transaksi', $request->id_transaksi);
        });

        return response()->json($query->get(), 200);
    }

    public function pdfTransById(Request $request)
    {

        $data = $this->searchTransaksiById($request)->getData();

        // Load view PDF dengan data transaksi
        $pdf = new Dompdf();
        $pdf->loadHtml(view('kontrak', compact('data'))->render());

        // Atur ukuran dan orientasi halaman
        $pdf->setPaper('A4', 'potrait');

        // Render PDF
        $pdf->render();

        // Simpan atau kirimkan PDF kepada pengguna
        return $pdf->stream("invoice-pdf", array("Attachment" => false));
    }

    public function pdf(Request $request)
    {
        $data = $this->searchTransaksiById($request)->getData();
        $mpdf = new \Mpdf\Mpdf();
        $html = view('kontrak', compact('data'))->render(); // Ganti 'nama_file_html' dengan nama file HTML Anda
        $mpdf->WriteHTML($html);
        $mpdf->Output();
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
                'id_pajak' => $request->id_pajak,
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
            $update->id_pajak = $request->id_pajak;
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
