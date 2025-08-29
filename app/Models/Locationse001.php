<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Locationse001 extends Model
{
    use HasFactory;
    protected $connection = 'mysql_third';
    protected $table = 'bo';
    public $incrementing = false; 
    protected $primaryKey = 'BO';
    protected $keyType = 'string';
    protected $fillable = [
        'BO',
        'CABANG',
    ];
}