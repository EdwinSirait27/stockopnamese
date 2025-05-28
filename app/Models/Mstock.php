<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Mstock extends Model
{
    protected $connection = 'mysql2';
    use HasFactory;
    protected $table = 'mstock';
    protected $primaryKey = 'BARA';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'BARA',
        'BARA2',
        'NAMA',
        'AVER',
        'SATUAN',
        'SALDO',
    ];
    // public function Mtokodetsoglos()
    // {
    //     return $this->hasMany(Mtokodetsoglo::class, 'KDTOKO', 'kdtoko');
    // }
    // cari dulu berdasarkan bara2 terus bara1 di mstock_soglo lalu setelah itu kalau tidak ada cari di 
}