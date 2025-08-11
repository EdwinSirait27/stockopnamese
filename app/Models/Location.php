<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'pos_location';   
    public $incrementing = false; // karena ID-nya bukan auto increment integer
protected $keyType = 'string';
     protected $fillable = [
        'location_id',
        'type',
        'name',
        'code',
    ];
    

}