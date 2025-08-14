<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posopnameitem extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second'; // koneksi ke database kedua
    protected $table = 'pos_opname_item';
    protected $primaryKey = 'opname_item_id';
    public $incrementing = false;
      public $timestamps = false;
      protected $keyType = 'int';
    protected $fillable = [
        'opname_item_id',
        'opname_id',
        'item_master_id',
        'qty_system',
        'qty_real',
        'note',
        'type',
        'company_id',
        'sub_location_id',
        'opname_sub_location_id',
        'date',
    ];
    protected $casts = [
        'opname_id' => 'string',
        'location_id' => 'string',
        'item_master_id' => 'string',
        'sub_location_id' => 'string',
        'opname_sub_location_id' => 'string',
         'qty_real' => 'decimal:1',
    ];


    public function opname()
    {
        return $this->belongsTo(Posopname::class, 'opname_id', 'opname_id');
    }
    public function item()
    {
        return $this->belongsTo(Positemmaster::class, 'item_master_id', 'item_master_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_id');
    }
    public function sublocation()
    {
        return $this->belongsTo(Sublocation::class, 'sub_location_id', 'sub_location_id');
    }
    public function posopnamesublocation()
    {
        return $this->belongsTo(Posopnamesublocation::class, 'opname_sub_location_id', 'opname_sub_location_id');
    }
}
