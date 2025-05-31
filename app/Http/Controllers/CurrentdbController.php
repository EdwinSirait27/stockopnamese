<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

use App\Models\Mstock;
use App\Models\Branch;

class CurrentdbController extends Controller
{
     public function index() {
          $listCabang = Branch::select('id', 'CABANG')->distinct()->get();
        return view('Currentdb.index',compact('listCabang'));
    }
//  public function getCurrentdb()
//     {
//         // dd(Role::with('permissions')->get()->toArray());
//         $currentdbs = Mstock::with('branch')
//             ->select(['BARA', 'BARA2','NAMA','AVER','AWAL','MASUK','KELUAR','SATUAN','bo_id'])
//             ->get()
//             ->map(function ($currentdb) {
         
//                 return $currentdb;
//             });
//         return DataTables::of($currentdbs)
//             ->addIndexColumn()
           
//           ->addColumn('CABANG', function ($currentdb) {
//                 return !empty($currentdb->branch) && !empty($currentdb->branch->CABANG)
//                     ? $currentdb->branch->CABANG
//                     : 'Empty';
//             })
//               ->addColumn('SALDO', function ($row) {
//                         $awal = (double) $row->AWAL;
//                         $masuk = (double) $row->MASUK;
//                         $keluar = (double) $row->KELUAR;
//                         return $awal + $masuk - $keluar;
//                     })
//                     ->rawColumns(['SALDO'])
//             ->make(true);
//     }
// public function getCurrentdb(Request $request)
// {
//     $query = Mstock::with('branch')
//         ->select(['BARA', 'BARA2','NAMA','AVER','AWAL','MASUK','KELUAR','SATUAN','bo_id']);

//      if ($request->filled('CABANG')) {
//         $query->whereHas('branch', function ($q) use ($request) {
//             $q->where('CABANG', $request->CABANG);
//         });
//     }

//     $currentdbs = $query->get();

//     return DataTables::of($currentdbs)
//         ->addIndexColumn()
//         ->addColumn('CABANG', function ($currentdb) {
//             return $currentdb->branch->CABANG ?? 'Empty';
//         })
//         ->addColumn('SALDO', function ($row) {
//             $awal = (double) $row->AWAL;
//             $masuk = (double) $row->MASUK;
//             $keluar = (double) $row->KELUAR;
//             return $awal + $masuk - $keluar;
//         })
//         ->rawColumns(['SALDO'])
//         ->make(true);
// }
public function getCurrentdb(Request $request)
{
    // Tambahkan cache untuk data yang jarang berubah
    $cacheKey = 'currentdb_' . ($request->CABANG ?? 'all') . '_' . md5(serialize($request->all()));
    
    $query = Mstock::with(['branch:id,BO,CABANG']) // Eager load hanya kolom yang diperlukan
        ->select([
            'mstock.BARA', // Tambahkan primary key untuk DataTables
            'BARA2', 
            'NAMA', 
            'AVER', 
            'AWAL', 
            'MASUK', 
            'KELUAR', 
            'SATUAN', 
            'bo_id',
            // Hitung SALDO langsung di database
            DB::raw('(CAST(AWAL AS DECIMAL(15,2)) + CAST(MASUK AS DECIMAL(15,2)) - CAST(KELUAR AS DECIMAL(15,2))) as SALDO')
        ]);

    // Filter berdasarkan cabang jika ada
    if ($request->filled('CABANG')) {
        $query->whereHas('branch', function ($q) use ($request) {
            $q->where('CABANG', $request->CABANG);
        });
    }

    // Tambahkan index untuk sorting jika diperlukan
    $query->orderBy('BARA');

    return DataTables::eloquent($query) // Gunakan eloquent() untuk lazy loading
        ->addIndexColumn()
        ->addColumn('CABANG', function ($currentdb) {
            return $currentdb->branch->CABANG ?? 'Empty';
        })
        ->editColumn('SALDO', function ($row) {
            // SALDO sudah dihitung di database, tinggal format jika perlu
            return number_format($row->SALDO, 2);
        })
        ->filterColumn('CABANG', function($query, $keyword) {
            // Optimasi untuk filter CABANG
            $query->whereHas('branch', function($q) use ($keyword) {
                $q->where('CABANG', 'like', "%{$keyword}%");
            });
        })
        ->orderColumn('CABANG', function ($query, $order) {
            // Optimasi untuk sorting CABANG
            $query->join('branches', 'mstocks.bo_id', '=', 'branches.bo_id')
                  ->orderBy('branches.CABANG', $order);
        })
        ->rawColumns(['SALDO'])
        ->make(true);
}

}
