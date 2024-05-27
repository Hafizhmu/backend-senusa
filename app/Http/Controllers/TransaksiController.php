<?php

namespace App\Http\Controllers;

use DB;
use mPDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Desa;
use App\Models\Projek;
use App\Models\Transaksi;
use App\Models\Perusahaan;
use Mpdf\Mpdf as MpdfMpdf;
use Illuminate\Http\Request;
use App\Models\Transaksi_Pajak;
use Illuminate\Routing\Controller;
use App\Http\Resources\TransaksiResource;
use App\Http\Requests\StoreTransaksiRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use App\Http\Requests\UpdateTransaksiRequest;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function hitungTransaksi()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $query = Transaksi::where('status_pembayaran', 1)->get();
        $query2 = Transaksi::where('status_pembayaran', 0)->get();
        $counter_pay = $query->count();
        $counter_not = $query2->count();
        $array = ['Bayar' => $counter_pay, 'Belum Bayar' => $counter_not];


        return response()->json($array, 200);
    }
    public function hitungProyek()
    {
        //Query untuk get table desa dengan atribut nama desa,nama kades,kecamatan,kabupaten
        $query = DB::table('projeks')->pluck('nama');
        $hitung = 1;
        // Loop melalui setiap proyek berdasarkan id proyek
        foreach ($query as $key => $nama_projek) {
            // Menghitung jumlah transaksi untuk setiap perusahaan berdasarkan id proyek
            $count = Transaksi::where('id_projek', $hitung)->count();
            // Memasukkan nilai ke dalam array dengan nama proyek sebagai kunci dan jumlah transaksi sebagai nilai
            $result[] = [
                "nama" => $nama_projek,
                "jumlah" => $count
            ];
            $hitung++;
        }
        return response()->json($result, 200);
        // $query2 = Transaksi::where('status_pembayaran', 0)->get();
        // $counter_not = $query2->count();
        // $array = ['Bayar' => $counter_pay, 'Belum Bayar' => $counter_not];


    }
    public function hitungBulanan(Request $request)
    {
        Carbon::setLocale('id'); // Atur lokal ke bahasa Indonesia
        $year = $request->input('year');
        $monthlyTransaksi = Transaksi::select(
            DB::raw('YEAR(tanggal_transaksi) as year'),
            DB::raw('MONTH(tanggal_transaksi) as month'),
            DB::raw('SUM(harga) as count')
        )
            ->whereYear('tanggal_transaksi',$year)
            ->groupBy(DB::raw('YEAR(tanggal_transaksi)'), DB::raw('MONTH(tanggal_transaksi)'))
            ->orderBy(DB::raw('YEAR(tanggal_transaksi)'), 'asc')
            ->orderBy(DB::raw('MONTH(tanggal_transaksi)'), 'asc')
            ->get();

        $result = $monthlyTransaksi->map(function ($item) {
            $monthName = Carbon::createFromDate($item->year, $item->month)->translatedFormat('F');
            return [
                'year' => $item->year,
                'month' => $monthName,
                'jumlah' => $item->count
            ];
        });

    //     // Create an array to hold the results with all months initialized to 0
    // $results = [];
    // for ($month = 1; $month <= 12; $month++) {
    //     $results[$month] = [
    //         'year' => $year,
    //         'month' => Carbon::createFromDate($year, $month, 1)->translatedFormat('F'),
    //         'jumlah' => 0
    //     ];
    // }

    // // Merge the actual transaction counts into the results array
    // foreach ($monthlyTransaksi as $item) {
    //     $results[$item->month]['jumlah'] = $item->count;
    // }

    // // Convert the results array to a collection
    // $result = collect($results)->values();
        return response()->json($result, 200);
    }

    public function searchTrans(Request $request)
    {
        $keyword = $request->input('keyword');
        $transaksi = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.status_pembayaran', 'transaksis.status_kontrak', 'transaksis.status_pembayaran', 'status_kontrak')
            ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
            ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
            ->where('desas.nama_desa', 'LIKE', "%$keyword%")
            ->orderBy('desas.nama_desa')
            ->paginate($request->data);

        return TransaksiResource::collection($transaksi);
    }


    public function searchTransaksiByDesa($id_desa)
    {
        // Ambil ID desa dari request
        $find = Desa::find($id_desa);

        // Cek jika ID desa telah diberikan
        if ($find) {
            // Mengambil data transaksi yang dilakukan di desa dengan ID tertentu
            $transaksis = Transaksi::select('transaksis.id_transaksi', 'projeks.nama AS nama_projek', 'desas.nama_desa', 'transaksis.harga', 'transaksis.status_pembayaran', 'transaksis.status_kontrak', 'perusahaans.nama_perusahaan', 'desas.nama_kades')
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('perusahaans', 'transaksis.id_perusahaan', '=', 'perusahaans.id')
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
            return $query->select('transaksis.id_transaksi', 'projeks.id_projek', 'projeks.nama AS nama_projek', 'desas.id_desa', 'desas.nama_desa', 'transaksis.harga', 'transaksis.status_pembayaran', 'status_kontrak', 'perusahaans.id AS id_perusahaan', 'perusahaans.nama_perusahaan', 'tanggal_transaksi', 'tanggal_pembayaran')
                ->join('projeks', 'transaksis.id_projek', '=', 'projeks.id_projek')
                ->join('desas', 'transaksis.id_desa', '=', 'desas.id_desa')
                ->join('perusahaans', 'transaksis.id_perusahaan', '=', 'perusahaans.id')
                ->where('transaksis.id_transaksi', $request->id_transaksi);
        });

        return response()->json($query->get(), 200);
    }

    public function pdfKontrak(Request $request)
    {

        $getTrans = (new TransaksiPajakController)->getTransById($request);
        $data = $getTrans->getData();

        // Load view PDF dengan data transaksi
        $pdf = new Dompdf();
        $pdf->loadHtml(view('kontrak', compact('data'))->render());

        // Atur ukuran dan orientasi halaman
        $pdf->setPaper('F4', 'potrait');

        // Render PDF
        $pdf->render();

        // Simpan atau kirimkan PDF kepada pengguna
        return $pdf->stream("invoice-pdf", array("Attachment" => false));
    }
    public function pdfInvoice(Request $request)
    {
        $getTrans = (new TransaksiPajakController)->getTransById($request);
        $data = $getTrans->getData();

        // Load view PDF dengan data transaksi
        $pdf = new Dompdf();
        $pdf->loadHtml(view('invoice-gides-manis', compact('data'))->render());

        // Atur ukuran dan orientasi halaman
        $pdf->setPaper('F4', 'landscape');

        // Render PDF
        $pdf->render();

        // Simpan atau kirimkan PDF kepada pengguna
        return $pdf->stream("invoice-pdf", array("Attachment" => false));
    }
    public function pdfKwitansi(Request $request)
    {

        $getTrans = (new TransaksiPajakController)->getTransById($request);
        $data = $getTrans->getData();

        // Load view PDF dengan data transaksi
        $pdf = new Dompdf();
        $pdf->loadHtml(view('kwitansi', compact('data'))->render());

        // Atur ukuran dan orientasi halaman
        $pdf->setPaper('F4', 'landscape');

        // Render PDF
        $pdf->render();

        // Simpan atau kirimkan PDF kepada pengguna
        return $pdf->stream("invoice-pdf", array("Attachment" => false));
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
                'status_kontrak' => $request->status_kontrak,
                'status_pembayaran' => $request->status_pembayaran,
                'tanggal_pembayaran' => $request->input('tanggal_pembayaran'),
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'id_perusahaan' => $request->id_perusahaan
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
            $update->status_kontrak = $request->status_kontrak;
            $update->status_pembayaran = $request->status_pembayaran;
            $update->tanggal_pembayaran = $request->tanggal_pembayaran;
            $update->tanggal_transaksi = $request->tanggal_transaksi;
            $update->id_perusahaan = $request->id_perusahaan;

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
            Transaksi_Pajak::where('id_transaksi', $id)->delete();

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
