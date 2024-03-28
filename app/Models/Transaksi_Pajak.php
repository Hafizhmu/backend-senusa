<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi_Pajak extends Model
{
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $table = 'transaksi_pajaks';
}
