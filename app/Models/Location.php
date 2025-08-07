<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'pos_location';   
     protected $fillable = [
        'location_id',
        'type',
        'name',
        'code',
    ];
    

}