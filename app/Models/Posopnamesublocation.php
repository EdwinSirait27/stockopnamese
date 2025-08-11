<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posopnamesublocation extends Model
{
       protected $connection = 'mysql_second'; 
    protected $table = 'pos_opname_sub_location';   
    protected $primaryKey = 'opname_sub_location_id';  
    public $timestamps = false;

     protected $fillable = [
        'opname_sub_location_id',
        'opname_id',
        'sub_location_id',
        'sub_location_name',
        'status',
        'user_id',
        'form_number',
        'date',
    ];
       protected $casts = [
    'opname_sub_location_id' => 'string',
    'opname_id' => 'string',
    'sub_location_id' => 'string',
    // 'date' => 'date',

];
     public function opname()
{
    return $this->belongsTo(Posopname::class, 'opname_id', 'opname_id');
}
     public function sublocation()
{
    return $this->belongsTo(Sublocation::class, 'sub_location_id', 'sub_location_id');
}
     public function users()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}
public function posopnameitems()
{
    return $this->hasMany(Posopnameitem::class, 'opname_sub_location_id', 'opname_sub_location_id');
}

}
