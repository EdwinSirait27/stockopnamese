<?php

namespace App\Http\Controllers;
use App\Imports\SoImport;
use App\Models\Posopname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Mtokosoglo;
use App\Models\Posopnameitem;

use App\Models\Posopnamesublocation;
use Illuminate\Support\Facades\Log;

class dashboardAdminController extends Controller
{
    public function index()
    {
        return view('pages.dashboardadmin');
    }
    // public function getPosopnamesadmin(Request $request)
    // {
    //     $query = Posopname::select([
    //         'opname_id',
    //         'date',
    //         'status',
    //         'location_id',
    //         'note',
    //         'counter',
    //         'number',
    //         'approval_1',
    //         'approval_2',
    //         'approval_3',
    //         'user_id',
    //         'prefix_number',
    //         'approval_1_date',
    //         'approval_2_date',
    //         'approval_3_date',
    //         'type',
    //         'company_id',
    //         'type_opname'
    //     ])
    //         ->with('location', 'ambildarisublocation', 'ambildarisublocation.location');
    //     if ($search = $request->input('search.value')) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('opname_id', 'like', "%{$search}%")
    //                 ->orWhere('status', 'like', "%{$search}%")
    //                 ->orWhereHas('location', function ($q2) use ($search) {
    //                     $q2->where('name', 'like', "%{$search}%");
    //                 });
    //         });
    //     }

    //     return DataTables::of($query)
    //         ->orderColumn('location.name', function ($query, $order) {
    //             $query->join('locations', 'locations.id', '=', 'posopnames.location_id')
    //                 ->orderBy('locations.name', $order);
    //         })
    //         ->addColumn('action', function ($posopname) {
    //             if ($posopname->status === 'CANCELED') {
    //                 return '
    //         <button class="btn btn-sm btn-outline-secondary mx-1" disabled>
    //             <i class="fas fa-eye-slash"></i> Locked
    //         </button>
    //     ';
    //             }
    //             return '
    //     <a href="' . route('pages.showdashboardadmin', $posopname->opname_id) . '" 
    //        class="btn btn-sm btn-outline-info mx-1" 
    //        data-bs-toggle="tooltip" 
    //        title="Show opname: ' . e($posopname->opname_id) . '">
    //         <i class="fas fa-eye"></i> Show
    //     </a>
    // ';
    //         })

    //         ->editColumn('type', function ($posopname) {
    //             switch ($posopname->type) {
    //                 case 0:
    //                     return 'Global';
    //                 case 1:
    //                     return 'Partial';
    //                 case 2:
    //                     return 'Per Item';
    //                 default:
    //                     return 'Unknown';
    //             }
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }
    public function getPosopnamesadmin(Request $request)
{
    $userLocationId = auth()->user()->location_id;

    $query = Posopname::select([
        'opname_id',
        'date',
        'status',
        'location_id',
        'note',
        'counter',
        'number',
        'approval_1',
        'approval_2',
        'approval_3',
        'user_id',
        'prefix_number',
        'approval_1_date',
        'approval_2_date',
        'approval_3_date',
        'type',
        'company_id',
        'type_opname'
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
        ->editColumn('type', function ($posopname) {
            switch ($posopname->type) {
                case 0:
                    return 'Global';
                case 1:
                    return 'Partial';
                case 2:
                    return 'Per Item';
                default:
                    return 'Unknown';
            }
        })
        ->rawColumns(['action'])
        ->make(true);
}


    public function showadmin($opname_id)
    {
        Log::info('Masuk ke method show', ['opname_id' => $opname_id]);
        $posopnamesublocation = Posopnamesublocation::with('opname', 'sublocation.location', 'users', 'sublocation', 'opname.ambildarisublocation')
            ->where('opname_id', $opname_id)
            ->get();
        $posopname = Posopname::with('ambildarisublocation', 'location')
            ->where('opname_id', $opname_id)
            ->get();
        return view('pages.showdashboardadmin', compact('posopnamesublocation', 'opname_id', 'posopname'));
    }
    public function getPosopnamesublocationsadmin(Request $request)
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
        
        // ✅ Filter berdasarkan location_id milik user
        ->whereHas('opname.location', function ($q) use ($userLocationId) {
            $q->where('location_id', $userLocationId);
        });

    // ✅ Filter berdasarkan opname_id dari request (opsional)
    if ($request->has('opname_id')) {
        $query->where('opname_id', $request->opname_id);
    }

    // ✅ Pencarian tambahan
    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('opname_sub_location_id', 'like', "%{$search}%")
                ->orWhere('opname_id', 'like', "%{$search}%")
                ->orWhere('sub_location_id', 'like', "%{$search}%")
                ->orWhere('sub_location_name', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('form_number', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhere('user_id', 'like', "%{$search}%");
        });
    }

  return DataTables::of($query)
    ->addColumn('action', function ($row) {
        return '
            <a href="' . route('opname.showitemadmin', $row->form_number) . '" 
               class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i> Show
            </a>
            <a href="' . route('opname.printitemadmin', $row->form_number) . '" 
               class="btn btn-sm btn-success" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
        ';
    })
    ->rawColumns(['action']) // Supaya HTML button tidak di-escape
    ->make(true);
}
 public function showitemadmin($form_number)
{
    Log::info('Masuk ke method showitem', ['form_number' => $form_number]);

    // Ambil data sublocation
    $posopnamesublocation = Posopnamesublocation::with(
        'opname',
        'sublocation.location',
        'users',
        'sublocation',
        'opname.ambildarisublocation'
    )
    ->where('form_number', $form_number)
    ->get();

    // Ambil opname_id dari hasil di atas
    $opname_id = optional($posopnamesublocation->first())->opname_id;

    // Ambil data opname berdasarkan opname_id
    $posopname = Posopname::with('ambildarisublocation', 'location')
        ->where('opname_id', $opname_id)
        ->get();

    return view('pages.showitemadmin', compact('posopnamesublocation', 'form_number', 'posopname','opname_id'));
}
   public function getshowitemadmin(Request $request)
{
    $query = Posopnameitem::select([
        'opname_item_id',
        'opname_id',
        'item_master_id',
        'qty_system',
        'qty_real',
        'note',
        'type',
        'company_id',
        'sub_location_id',
        'opname_sub_location_id'
    ])
    ->with('sublocation', 'opname', 'posopnamesublocation','opname.location','item','item.posunit');

    // Filter berdasarkan form_number di relasi posopnamesublocation
    if ($request->has('form_number')) {
        $query->whereHas('posopnamesublocation', function ($q) use ($request) {
            $q->where('form_number', $request->form_number);
        });
    }

    // Search global untuk DataTables
    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('opname_item_id', 'like', "%{$search}%")
                ->orWhere('opname_id', 'like', "%{$search}%")
                ->orWhere('item_master_id', 'like', "%{$search}%")
                ->orWhere('qty_system', 'like', "%{$search}%")
                ->orWhere('qty_real', 'like', "%{$search}%")
                ->orWhere('note', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('company_id', 'like', "%{$search}%")
                ->orWhere('sub_location_id', 'like', "%{$search}%")
                ->orWhere('opname_sub_location_id', 'like', "%{$search}%");
        });
    }

    return DataTables::of($query)->make(true);
}
public function printitemadmin(Request $request, $form_number)
{
    Log::info('Masuk ke method showitem', ['form_number' => $form_number]);

    // Ambil data sublocation
    $posopnamesublocation = Posopnamesublocation::with(
        'opname',
        'sublocation.location',
        'users',
        'sublocation',
        'opname.ambildarisublocation'
    )
    ->where('form_number', $form_number)
    ->get();

    $opname_id = optional($posopnamesublocation->first())->opname_id;

    // Ambil opname
    $posopname = Posopname::with('ambildarisublocation', 'location')
        ->where('opname_id', $opname_id)
        ->first();

    // Ambil item terkait
    $posopnameitems = Posopnameitem::select([
            'opname_item_id',
            'opname_id',
            'item_master_id',
            'qty_system',
            'qty_real',
            'note',
            'type',
            'company_id',
            'sub_location_id',
            'opname_sub_location_id'
        ])
        ->with('sublocation', 'opname', 'posopnamesublocation', 'opname.location', 'item','item.posunit')
        ->whereHas('posopnamesublocation', function ($q) use ($form_number) {
            $q->where('form_number', $form_number);
        })
        ->get();
       $totalQtyReal = $posopnameitems->reduce(function ($carry, $item) {
    return bcadd($carry, $item->qty_real, 3); // 3 = jumlah desimal
}, '0');

    return view('pages.printitemadmin', compact('posopnamesublocation', 'form_number', 'posopname', 'posopnameitems','totalQtyReal'));
}


    public function indexsoadmin($opname_id)
    {
        $files = Storage::disk('public')->files('templateso');

        $posopname = Posopname::select('opname_id', 'location_id', 'date')
            ->with('ambildarisublocation', 'location')
            ->where('opname_id', $opname_id)
            ->firstOrFail();
        return view('pages.Importsoadmin.Importsoadmin', compact('files', 'posopname'));
    }

    public function Importsoadmin(Request $request, $opname_id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        $errors = [];

        $sublocation = Posopname::with('ambildarisublocation')->where('opname_id', $opname_id)->first();

        if (!$sublocation) {
            return back()->with('failures', ['Gagal menemukan data SO berdasarkan Opname ID']);
        }

        $import = new SoImport(
            $errors,
            $sublocation->opname_id,
            $sublocation->ambildarisublocation->sub_location_id,
            $sublocation->date,
            $sublocation->status ?? 'DRAFT'
        );

        $import->import($request->file('file'));

        // if ($import->failures()->isNotEmpty() || !empty($errors)) {
        //     return back()->with([
        //         'failures' => $import->failures(),
        //         'errors'   => $errors,
        //     ]);
        // }
        $allFailures = [];
        foreach ($import->failures() as $failure) {
            $allFailures[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }

        if (!empty($errors) || !empty($allFailures)) {
            return back()->withErrors(array_merge($errors, $allFailures));
        }



        return back()->with('success', 'SO import berhasil!');
    }

    // public function indexso($opname_id)
// {
//     // Ambil file dari folder templateso
//     $files = Storage::disk('public')->files('templateso');

    //     // Ambil opname_id dan location_id dari Posopname (first)
//     $posopname = Posopname::select('opname_id', 'location_id')
//         ->with('ambildarisublocation','location')
//         ->where('opname_id', $opname_id)
//         ->first(); // hanya ambil 1 record
//     return view('pages.Importso.Importso', compact('files', 'posopnamesublocation', 'posopname'));
// }
// public function Importso(Request $request, $opname_id)
// {
//     $request->validate([
//         'file' => 'required|mimes:xlsx,csv,xls'
//     ]);

    //     $errors = [];

    //     // Ambil data patokan dari opname_id yang dikirim
//     $sublocation = Posopnamesublocation::where('opname_id', $opname_id)->first();

    //     if (!$sublocation) {
//         return back()->with('failures', ['Gagal menemukan data SO berdasarkan Opname ID']);
//     }

    //     $import = new SoImport(
//         $errors,
//         $sublocation->opname_id,
//         $sublocation->sub_location_id,
//         $sublocation->date,
//         $sublocation->status ?? 'DRAFT'
//     );

    //     $import->import($request->file('file'));

    //     if ($import->failures()->isNotEmpty()) {
//         return back()->with([
//             'failures' => $import->failures(),
//             'errors' => $errors,
//         ]);
//     }


    //     if (!empty($errors)) {
//     return back()->with([
//         'failures' => collect($errors),
//         'errors' => $errors,   
//     ]);
// }

    //     return back()->with('success', 'SO import berhasil!');
// }
//      public function indexso($opname_id)
// {
//     $files = Storage::disk('public')->files('templateso');
//     $posopnamesublocation = Posopnamesublocation::select([
//             'opname_sub_location_id',
//             'opname_id',
//             'sub_location_id',
//             'sub_location_name',
//             'status',
//             'user_id',
//             'form_number',
//             'date'
//         ])
//         ->with('sublocation', 'opname.location', 'users')
//         ->where('opname_id', $opname_id)
//         ->first();
//          $posopname = Posopname::with('ambildarisublocation','location')
//     ->where('opname_id', $opname_id)
//         ->get();

    //     return view('pages.Importso.Importso', compact('files', 'posopnamesublocation','posopname'));
// }

    public function edit($opname_id)
    {
        Log::info('Masuk ke method editRole', ['opname_id' => $opname_id]);
        $posopnamesublocation = Posopnamesublocation::with('opname')
            ->where('opname_id', $opname_id)
            ->get();

        if (!$posopnamesublocation) {
            Log::warning('Data tidak ditemukan di method edit', ['opname_id' => $opname_id]);
            abort(404, 'Data not found.');
        }
        $userName = Auth::user()->name;
        return view('pages.editdashboard', compact('posopnamesublocation', 'opname_id', 'userName'));
    }
    public function update(Request $request, $kdtoko)
    {
        Log::info('Masuk ke method update', ['kdtoko' => $kdtoko]);
        $validated = $request->validate([
            'kdtoko' => 'required|string|max:255',
            'kettoko' => 'required|string|max:255',
            'personil' => 'required|string|max:255',
        ]);
        $mtokosoglo = Mtokosoglo::find($kdtoko);
        if (!$mtokosoglo) {
            Log::warning('Data tidak ditemukan di method update', ['kdtoko' => $kdtoko]);
            abort(404, 'Data not found.');
        }
        $validated['inpmasuk'] = Auth::user()->name;
        $mtokosoglo->update($validated);

        return redirect()->route('dashboardadmin')->with('success', 'Data berhasil diperbarui.');
    }


    public function downloadso($filename)
    {
        $path = 'templateso/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }
        abort(404);
    }
    //      public function Importso(Request $request)
// {
//         ini_set('max_execution_time', 180);
//     $request->validate([
//         'file' => 'required|mimes:xlsx,csv,xls'
//     ]);
//     $errors = [];
//     $import = new SoImport($errors);
//     $import->import($request->file('file'));
//     if ($import->failures()->isNotEmpty()) {
//     return back()->with([
//         'failures' => $import->failures(), // INI YANG WAJIB
//         'errors' => $errors, // opsional
//     ]);
// }
//     if (!empty($errors)) {
//         return back()->with('failures', $errors);
//     }
//     return back()->with('success', 'SO import successfully!');
// }



}
