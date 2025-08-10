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
      public function index()
    {
        return view('pages.dashboardpenginput');
    }
    public function getPosopnamepenginput(Request $request)
{
    $userLocationId = auth()->user()->location_id;

    $query = Posopname::select([
        'opname_id',
        'date',
        'status',
        'location_id',
        'note',
        'type'
        
    ])
    ->where('location_id', $userLocationId) 
    ->with('location', 'ambildarisublocation', 'ambildarisublocation.location');

    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('opname_id', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhereHas('location', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%");
              });
        });
    }

    return DataTables::of($query)
        ->orderColumn('location.name', function ($query, $order) {
            $query->join('locations', 'locations.id', '=', 'posopnames.location_id')
                  ->orderBy('locations.name', $order);
        })
        ->addColumn('action', function ($posopname) {
            if ($posopname->status === 'CANCELED') {
                return '
                    <button class="btn btn-sm btn-outline-secondary mx-1" disabled>
                        <i class="fas fa-eye-slash"></i> Locked
                    </button>
                ';
            }
            return '
                <a href="' . route('pages.showdashboardadmin', $posopname->opname_id) . '" 
                   class="btn btn-sm btn-outline-info mx-1" 
                   data-bs-toggle="tooltip" 
                   title="Show opname: ' . e($posopname->opname_id) . '">
                    <i class="fas fa-eye"></i> Show
                </a>
            ';
        })
        // ->editColumn('type', function ($posopname) {
        //     switch ($posopname->type) {
        //         case 0:
        //             return 'Global';
        //         case 1:
        //             return 'Partial';
        //         case 2:
        //             return 'Per Item';
        //         default:
        //             return 'Unknown';
        //     }
        // })
        ->rawColumns(['action'])
        ->make(true);
}
}
