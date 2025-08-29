<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mtokosoglo extends Model
{
    use HasFactory;
        protected $table = 'mtoko_soglo'; 
          protected $primaryKey = 'kdtoko';
          protected $keyType = 'string';
          public $timestamps = false;

    protected $fillable = [
        'kdtoko',
        'kettoko',
        'personil',
        'inpmasuk',
    ];
    public function Mtokodetsoglos()
    {
        return $this->hasMany(Mtokodetsoglo::class, 'KDTOKO', 'kdtoko');
    }
    // cari dulu berdasarkan bara2 terus bara1 di mstock_soglo lalu setelah itu kalau tidak ada cari di 
}