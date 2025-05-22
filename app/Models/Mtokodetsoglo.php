<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mtokodetsoglo extends Model
{
    use HasFactory;
        protected $table = 'mstock_soglo'; 
    protected $fillable = [
        'KDTOKO',
        'BARA',
        'NOURUT',
        'FISIK',
        'BARCODE',
        'ID',
        
    ];
    public function Mtokodetsoglos()
{
    return $this->belongsTo(MtokoSoglo::class, 'KDTOKO', 'kdtoko');
}
// berarti di scan barcode itu cari bara2 mstock_soglo dlu abistu cari  
}
