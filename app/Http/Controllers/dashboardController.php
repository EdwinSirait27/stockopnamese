<?php

namespace App\Http\Controllers;
use App\Imports\SoImport;
use App\Models\Posopname;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Mtokosoglo;
use App\Models\Posopnamesublocation;
use App\Models\Buttons;
use App\Models\Mtokodetsoglo;
use Illuminate\Support\Facades\Log;
class dashboardController extends Controller
{
    public function index(){
        
 
    $locations = Location::all();
        return view('pages.dashboard',compact('locations'));
 
}
 
public function getPosopnames(Request $request)
{
    $query = Posopname::select([
        'opname_id', 'date', 'status', 'location_id', 'note', 'counter', 'number',
        'approval_1', 'approval_2', 'approval_3', 'user_id', 'prefix_number',
        'approval_1_date', 'approval_2_date', 'approval_3_date',
        'type', 'company_id', 'type_opname'
    ])
    ->with('location','ambildarisublocation','ambildarisublocation.location');

    // Filter lokasi jika dipilih dari dropdown
   if ($request->filled('location_name')) {
    $query->whereHas('location', function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->location_name . '%');
    });
}
    // Filter pencarian umum (search bar)
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
        <a href="' . route('pages.showdashboard', $posopname->opname_id) . '" 
           class="btn btn-sm btn-outline-info mx-1" 
           data-bs-toggle="tooltip" 
           title="Show opname: ' . e($posopname->opname_id) . '">
            <i class="fas fa-eye"></i> Show
        </a>
    ';
})

        ->editColumn('type', function ($posopname) {
            switch ($posopname->type) {
                case 0: return 'Global';
                case 1: return 'Partial';
                case 2: return 'Per Item';
                default: return 'Unknown';
            }
        })
        ->rawColumns(['action'])
        ->make(true);
}

    public function getPosopnamesublocations(Request $request)
    {
        $query = Posopnamesublocation::select(['opname_sub_location_id', 'opname_id', 'sub_location_id', 'sub_location_name', 'status', 'user_id','form_number','date'])->with('sublocation','opname.location','users');
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

            ->make(true);
    }
public function indexso($opname_id)
{
    $files = Storage::disk('public')->files('templateso');

    // Ambil opname dari Posopname
    $posopname = Posopname::select('opname_id', 'location_id', 'date')
        ->with('ambildarisublocation','location')
        ->where('opname_id', $opname_id)
        ->firstOrFail();

    // Ambil atau buat sublocation default
    $posopnamesublocation = Posopnamesublocation::firstOrCreate(
        ['opname_id' => $posopname->opname_id],
        [
            'sub_location_id' => $posopname->location_id, // kalau memang mappingnya begitu
            'status'          => 'DRAFT',
            'user_id'         => auth()->id(),
            'form_number'     => 'AUTO-' . now()->format('YmdHis'),
            'date'            => $posopname->date ?? now()->toDateString(),
        ]
    );

    return view('pages.Importso.Importso', compact('files', 'posopnamesublocation', 'posopname'));
}

public function Importso(Request $request, $opname_id)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,xls'
    ]);

    $errors = [];

    // Ambil data patokan
    $sublocation = Posopnamesublocation::where('opname_id', $opname_id)->first();

    if (!$sublocation) {
        return back()->with('failures', ['Gagal menemukan data SO berdasarkan Opname ID']);
    }

    $import = new SoImport(
        $errors,
        $sublocation->opname_id,
        $sublocation->sub_location_id,
        $sublocation->date,
        $sublocation->status ?? 'DRAFT'
    );

    $import->import($request->file('file'));

    if ($import->failures()->isNotEmpty() || !empty($errors)) {
        return back()->with([
            'failures' => $import->failures(),
            'errors'   => $errors,
        ]);
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
    public function show($opname_id)
    {
    Log::info('Masuk ke method show', ['opname_id' => $opname_id]);
    $posopnamesublocation = Posopnamesublocation::with('opname','sublocation.location','users','sublocation','opname.ambildarisublocation')
    ->where('opname_id', $opname_id)
        ->get();
    $posopname = Posopname::with('ambildarisublocation','location')
    ->where('opname_id', $opname_id)
        ->get();
        return view('pages.showdashboard', compact('posopnamesublocation', 'opname_id','posopname'));
    }
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
      
        return redirect()->route('dashboard')->with('success', 'Data berhasil diperbarui.');
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