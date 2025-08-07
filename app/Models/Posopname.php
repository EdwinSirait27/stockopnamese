<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posopname extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'pos_opname';   
    protected $primaryKey = 'opname_id';
     protected $fillable = [
        'opname_id',
        'date',
        'status',
        'location_id',
        'note',
        'status',
        'coutner',
        'number',
        'approval_1',
        'approval_2',
        'approval_3',
        'user_id',
        'prefix_number',
        'approval_1_date',
        'approval_2_date',
        'approval_3_date',
        'type',
        'company_id',
        'type_opname',
    ];
    protected $casts = [
    'opname_id' => 'string',
    'location_id' => 'string',
];

     public function location()
{
    return $this->belongsTo(Location::class, 'location_id', 'location_id');
}
}
