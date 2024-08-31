<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penomoran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'current_number',
        'last_number',
        'format_penomoran',
        'id_kabupaten',
        'id_kecamatan',
        'id_projek',
        'id_perusahaan',
        'id_dokumen',
        'bulan',
        'tahun'
    ];
}
