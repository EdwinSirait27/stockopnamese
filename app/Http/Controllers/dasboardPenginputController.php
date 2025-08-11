<?php

namespace App\Http\Controllers;

use App\Models\Posopname;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Posopnameitem;
use App\Models\Posopnamesublocation;
use Illuminate\Support\Facades\Log;
class dasboardPenginputController extends Controller
{
    

    public function show($opname_id)
    {
        Log::info('Masuk ke method show', ['opname_id' => $opname_id]);
        $posopnamesublocation = Posopnamesublocation::with('opname', 'sublocation.location', 'users', 'sublocation', 'opname.ambildarisublocation')
            ->where('opname_id', $opname_id)
            ->get();
        $posopname = Posopname::with('ambildarisublocation.location', 'location')->get();
// dd([
//     'opname_id' => $opname_id,
//     'posopname_count' => $posopname->count(),
//     'posopname_first' => $posopname->first(),
// ]);


        return view('pages.dashboardpenginput', compact('posopnamesublocation', 'opname_id', 'posopname'));
    }
 
    public function getPosopnamesublocationspenginput(Request $request)
{
    $userLocationId = auth()->user()->location_id;

    $query = Posopnamesublocation::select([
        'opname_sub_location_id',
        'opname_id',
        'sub_location_id',
        'sub_location_name',
        'status',
        'user_id',
        'form_number',
        'date'
    ])
        ->with('sublocation', 'opname.location', 'users', 'opname')
        ->whereHas('opname.location', function ($q) use ($userLocationId) {
            $q->where('location_id', $userLocationId);
        });
    if ($request->filled('opname_id')) {
        $query->where('opname_id', $request->opname_id);
    }
    $searchValue = $request->input('search.value');
    if (!empty($searchValue)) {
        $query->where('form_number', 'like', "%{$searchValue}%");
    }
    return DataTables::of($query)->make(true);
}
    public function scan($opname_sub_location_id)
{
    Log::info('Masuk ke method scan', ['opname_sub_location_id' => $opname_sub_location_id]);
    $posopnamesublocation = Posopnamesublocation::with([
            'opname',
            'sublocation.location',
            'users',
            'sublocation',
            'opname.ambildarisublocation'
        ])
        ->where('opname_sub_location_id', $opname_sub_location_id)
        ->firstOrFail();
    $opname_id = $posopnamesublocation->opname_id;
    $posopname = Posopname::with(['ambildarisublocation', 'location'])
        ->where('opname_id', $opname_id)
        ->first();

    return view('pages.scan', compact('posopnamesublocation', 'opname_id', 'posopname'));
}

}
