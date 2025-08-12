<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Positemmaster extends Model
{
     use HasFactory;
    protected $connection = 'mysql_second'; // koneksi dke database kedua
    protected $table = 'pos_item_master';   
    protected $primaryKey = 'item_master_id';
     protected $fillable = [
        'item_master_id',
        'code',
        'barcode',
        'name',
        'barcode_2',
        'barcode_3',
        'uom_stock_id',
    ];
  public function posunit()
{
    return $this->belongsTo(Posunit::class, 'uom_stock_id', 'uom_id');
}

}
