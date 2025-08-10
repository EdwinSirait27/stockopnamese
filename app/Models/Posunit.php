<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posunit extends Model
{
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'pos_unit';
    protected $primaryKey = 'uom_id';
    protected $fillable = [
        'uom_id',
        'unit',
        'company',
    ];
    protected $casts = [
        'uom_id' => 'string',
    ];
}
