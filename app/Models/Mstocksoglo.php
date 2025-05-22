<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Mstocksoglo extends Model
{
    use HasFactory;
    protected $table = 'mstock_soglo'; 
    protected $fillable = [
        'BARA',
        'BARA2',
        'NAMA',
        'AWAL',
        'MASUK',
        'SALDO',
        'AVER',
        'HBELI',
        'HJUAL',
        'STATUS',
        'KDGOL',
        'KDTOKO',
        'HPP',
        'SATUAN',
    ];
}
