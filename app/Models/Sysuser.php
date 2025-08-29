<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sysuser extends Model
{
    use HasFactory;
       protected $connection = 'mysql_second';
    protected $table = 'sysuser';
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $fillable = ['login_id', 'fullname','password'];
}
