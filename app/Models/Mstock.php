<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Mstock extends Model
{
    use HasFactory;
    protected $connection = 'mysql'; // default
    protected $table = 'mstock';
    protected $primaryKey = 'BARA';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'BARA',
        'BARA2',
        'NAMA',
        'AVER',
        'AWAL',
        'MASUK',
        'KELUAR',
        'SATUAN',
        // 'SALDO',
    ];
// RUMUS SALDO ITU AWAL TAMBAH MASUK KURANG KELUAR =SALDO
    // ✅ Tambahkan method ini:
    public function setConnectionNameDynamic($connection)
    {
        $this->setConnection($connection);
        return $this;
    }
}