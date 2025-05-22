<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mstockitem extends Model
{
    use HasFactory;
        protected $table = 'mstock_item'; 
    protected $fillable = [
        'BARA',
        'BARA1',
        
    ];
}
