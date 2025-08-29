<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Mtokodetsoglose001 extends Model
{
  use HasFactory;
    protected $connection = 'mysql_third';
        protected $table = 'mtoko_det_soglo'; 
    protected $fillable = [
        'KDTOKO',
        'BARA',
        'NOURUT',
        'FISIK',
        'BARCODE',
        'ID',
        'BO',
    ];
    public function Mtokodetsoglos()
{
    return $this->belongsTo(MtokoSoglo::class, 'KDTOKO', 'kdtoko');
}
}