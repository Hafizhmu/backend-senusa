<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDokumenRequest;
use App\Http\Requests\UpdateDokumenRequest;
use Carbon\Carbon;
use NumberFormatter;
use Rmunate\Utilities\SpellNumber;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function getName(Request $request)
    {
        $dokumen = Dokumen::select('nama_dokumen')
            ->where('id', $request->input('id'))
            ->get();

        if (!$dokumen) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($dokumen, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDokumenRequest $request)
    {
        try {
            $dokumen = $request->file('nama_dokumen');
            var_dump($dokumen);
            $nama_dokumen = $request->input('nama_file') . '.' . $request->file('nama_dokumen')->getClientOriginalExtension();
            $dokumen->move('docs/', $nama_dokumen);
            Dokumen::create([
                'nama_dokumen' => $nama_dokumen
            ]);
            return response()->json([
                'message' => 'Data Berhasil ditambahkan'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Terjadi Kesalahan " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        Carbon::setLocale('id'); // Atur lokal ke bahasa Indonesia
        try {
            $getTrans = (new DesaController)->getDoc($request);
            $getName = (new DokumenController)->getName($request);
            $dataDokumen = $getName->getData();
            // var_dump($dataDokumen[0]->nama_dokumen);
            $data = json_decode($getTrans->getContent()); // Mendekode JSON menjadi objek
            $nama_desa = $data[0]->nama_desa; // Mengakses properti 'nama_desa' dari objek dalam array
            $nama_kades = $data[0]->nama_kades; // Mengakses properti 'nama_desa' dari objek dalam array
            $nama_direktur = $data[0]->nama_direktur; // Mengakses properti 'nama_desa' dari objek dalam array
            $nama_perusahaan = $data[0]->nama_perusahaan; // Mengakses properti 'nama_desa' dari objek dalam array
            $kecamatan = $data[0]->kecamatan; // Mengakses properti 'nama_desa' dari objek dalam array
            $kabupaten = $data[0]->kabupaten; // Mengakses properti 'nama_desa' dari objek dalam array
            $harga = $data[0]->harga; // Mengakses properti 'nama_desa' dari objek dalam array
            $date = Carbon::parse($data[0]->tanggal_transaksi);
            $format_date = $date->translatedFormat('d F Y');
            $format_m = $date->translatedFormat(' F ');
            $format_y = $date->translatedFormat(' Y');
            $formatter = new NumberFormatter("id", NumberFormatter::SPELLOUT);
            $format_harga = Str::title($formatter->format($data[0]->harga));
            $format_day = Str::title($formatter->format($date->day));
            $format_day = $format_day . ' bulan' . $format_m . 'tahun' . $format_y;
            // var_dump($nama_desa);
            $phpword = new \PhpOffice\PhpWord\TemplateProcessor('docs/'.$dataDokumen[0]->nama_dokumen);
            // var_dump($format_harga);
            // var_dump($format_day);
            // var_dump($format_date);
            $phpword->setValues([
                'nama_desa' => Str::upper($nama_desa),
                'nama_kades' => Str::upper($nama_kades),
                'nama_direktur' => Str::upper($nama_direktur),
                'nama_perusahaan' => Str::upper($nama_perusahaan),
                'kecamatan' => Str::upper($kecamatan),
                'kabupaten' => Str::upper($kabupaten),
                'nama_desa_tittle' => Str::title($nama_desa),
                'nama_kades_tittle' => Str::title($nama_kades),
                'nama_direktur_tittle' => Str::title($nama_direktur),
                'nama_perusahaan_tittle' => Str::title($nama_perusahaan),
                'kecamatan_tittle' => Str::title($kecamatan),
                'kabupaten_tittle' => Str::title($kabupaten),
                'harga' => Str::upper($harga),
                'format_harga' => $format_harga,
                'format_date' => $format_date,
                'format_day' => $format_day
            ]);

            $phpword->saveAs('hasilDokumen/' . $request->input('nama_file') .  '.' . 'docx');
            return response()->json([
                'message' => 'Berkas berhasil disimpan'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => "Terjadi Kesalahan " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumen $dokumen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDokumenRequest $request, Dokumen $dokumen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumen $dokumen)
    {
        //
    }
}
