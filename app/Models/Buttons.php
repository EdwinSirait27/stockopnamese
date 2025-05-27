<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buttons extends Model
{
    use HasFactory;
    protected $table = 'buttons';
    protected $primaryKey = 'id';
    protected $fillable = [
        'url',
        'start_date',
        'end_date',
        'ket',
    ];
    // public function Mtokodetsoglos()
    // {
    //     return $this->hasMany(Mtokodetsoglo::class, 'KDTOKO', 'kdtoko');
    // }
    // cari dulu berdasarkan bara2 terus bara1 di mstock_soglo lalu setelah itu kalau tidak ada cari di 
}