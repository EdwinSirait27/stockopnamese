<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oxy extends Model
{
   use HasFactory;
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'sysuser';   
    protected $primaryKey = 'user_id';
     protected $fillable = [
        'user_id',
        'full_name',  
    ];
     public function location()
{
    return $this->belongsTo(Location::class, 'location_id', 'location_id');
}
     public function ambildarisublocation()
{
    return $this->belongsTo(Sublocation::class, 'location_id', 'location_id');
}

}
