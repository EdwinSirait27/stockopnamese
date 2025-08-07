<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sublocation extends Model
{
       use HasFactory;
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'pos_sub_location';   
     protected $fillable = [
        'sub_location_id',
        'location_id',
        'name',
        'description',
    ];
      protected $casts = [
    'sub_location_id' => 'string',
    
];
         public function location()
{
    return $this->belongsTo(Location::class, 'location_id', 'location_id');
}
}
