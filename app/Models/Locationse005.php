<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Locationse005 extends Model
{
    protected $connection = 'mysql_third';
    protected $table = 'bo';
    public $incrementing = false; // karena ID-nya bukan auto increment integer
    protected $fillable = [
        'BO',
        'CABANG',
    ];
}