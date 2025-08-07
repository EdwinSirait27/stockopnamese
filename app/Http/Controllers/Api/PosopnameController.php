<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Posopname;
use Illuminate\Http\Request;

class PosopnameController extends Controller
{
    
    public function index()
    {
         return response()->json(Posopname::all());
    }

 
    public function store(Request $request)
    {
        //
    }

 
    public function show($id)
    {
        //
    }

 
    public function update(Request $request, $id)
    {
        //
    }

 
    public function destroy($id)
    {
        //
    }
}
