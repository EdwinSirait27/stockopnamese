<?php

namespace App\Http\Controllers;
use App\Imports\SoImport;
use App\Models\Posopname;
use App\Models\Location;
use App\Models\Posopnameitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Mtokosoglo;
use App\Models\Posopnamesublocation;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class dashboardController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('pages.dashboard', compact('locations'));
    }
    public function getPosopnames(Request $request)
    {
        $query = Posopname::select('pos_opname.*')
            ->with('location', 'ambildarisublocation', 'ambildarisublocation.location');

        if ($request->filled('location_name')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->location_name . '%');
            });
        }
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
    public function show($opname_id)
    {
        Log::info('Masuk ke method show', ['opname_id' => $opname_id]);
        $posopnamesublocation = Posopnamesublocation::with('opname', 'sublocation.location', 'users', 'sublocation', 'opname.ambildarisublocation')
            ->where('opname_id', $opname_id)
            ->get();
        $posopname = Posopname::with('ambildarisublocation', 'location')
            ->where('opname_id', $opname_id)
            ->get();
        return view('pages.showdashboard', compact('posopnamesublocation', 'opname_id', 'posopname'));
    }
    public function approveAll($opname_id)
    {
        $updated = Posopnamesublocation::where('opname_id', $opname_id)
            ->where('status', 'PRINTED')
            ->update(['status' => 'APPROVED']);
        if ($updated > 0) {
            return redirect()->back()->with('success', "$updated data berhasil di-approve.");
        }
        return redirect()->back()->with('error', 'Tidak ada data dengan status PRINTED yang bisa di-approve.');
    }
    public function getPosopnamesublocations(Request $request)
    {
        $query = Posopnamesublocation::select('pos_opname_sub_location.*')
            ->with('sublocation', 'opname.location', 'users', 'oxy');
        if ($request->has('opname_id')) {
            $query->where('opname_id', $request->opname_id);
        }
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
                $showBtn = '
        <a href="' . route('opname.showitem', $row->opname_sub_location_id) . '"
           class="btn btn-sm btn-primary">
            <i class="fas fa-eye"></i> Show
        </a>
    ';
                if ($row->status === 'DRAFT') {
                    $printBtn = '
            <button class="btn btn-sm btn-secondary" disabled>
                <i class="fas fa-print"></i> Print
            </button>
        ';
                } elseif (in_array($row->status, ['REQ PRINT', 'PRINTED'])) {
                    $printBtn = '
            <a href="' . route('opname.printitem', $row->opname_sub_location_id) . '"
               class="btn btn-sm btn-success" target="_blank">
                <i class="fas fa-print"></i> Print
            </a>
        ';
                } else {
                    $printBtn = '
            <button class="btn btn-sm btn-secondary" disabled>
                <i class="fas fa-print"></i> Print
            </button>
        ';
                }
                return $showBtn . ' ' . $printBtn;
            })
            ->rawColumns(['action'])
            ->make(true);

    }
    public function showitem($opname_sub_location_id)
    {
        Log::info('Masuk ke method showitem', ['opname_sub_location_id' => $opname_sub_location_id]);
        $posopnamesublocation = Posopnamesublocation::with(
            'opname',
            'sublocation.location',
            'users',
            'sublocation',
            'opname.ambildarisublocation'
        )
            ->where('opname_sub_location_id', $opname_sub_location_id)
            ->get();
        $firstSublocation = $posopnamesublocation->first();
        $opname_id = optional($firstSublocation)->opname_id;
        $form_number = optional($firstSublocation)->form_number;
        $posopname = Posopname::with('ambildarisublocation', 'location')
            ->where('opname_id', $opname_id)
            ->get();
        return view('pages.showitem', compact('posopnamesublocation', 'opname_sub_location_id', 'posopname', 'opname_id', 'form_number'));
    }
    public function getshowitem(Request $request)
    {
        $query = Posopnameitem::select('pos_opname_item.*')
            ->with('sublocation', 'opname', 'posopnamesublocation', 'opname.location', 'item', 'item.posunit');

        if ($request->filled('opname_sub_location_id')) {
            $query->whereHas('posopnamesublocation', function ($q) use ($request) {
                $q->where('opname_sub_location_id', $request->opname_sub_location_id);
            });
        }

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('pos_opname_item.opname_item_id', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.opname_id', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.item_master_id', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.qty_system', 'like', "%{$search}%")
                    ->orWhereRaw("CAST(qty_real AS DECIMAL(22,1)) LIKE ?", ["%{$search}%"])
                    ->orWhere('pos_opname_item.note', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.type', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.company_id', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.sub_location_id', 'like', "%{$search}%")
                    ->orWhere('pos_opname_item.opname_sub_location_id', 'like', "%{$search}%")
                    ->orWhereHas('posopnamesublocation', function ($sub) use ($search) {
                        $sub->where('opname_sub_location_id', 'like', "%{$search}%");
                    })

                    ->orWhereHas('item', function ($item) use ($search) {
                        $item->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%");
                    });
            });
        }
        return DataTables::of($query)->make(true);
    }

    public function printitem(Request $request, $opname_sub_location_id)
    {
        Log::info('Masuk ke method printitem', ['opname_sub_location_id' => $opname_sub_location_id]);

        $posopnamesublocation = Posopnamesublocation::with(
            'opname',
            'sublocation.location',
            'users',
            'sublocation',
            'opname.ambildarisublocation'
        )
            ->where('opname_sub_location_id', $opname_sub_location_id)
            ->firstOrFail();

        if ($posopnamesublocation->status === 'REQ PRINT') {
            $posopnamesublocation->update(['status' => 'PRINTED']);
        }

        $posopname = Posopname::with('ambildarisublocation', 'location')
            ->where('opname_id', $posopnamesublocation->opname_id)
            ->first();

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
            ->with(
                'sublocation',
                'opname',
                'posopnamesublocation',
                'opname.location',
                'item',
                'item.posunit'
            )
            ->where('opname_sub_location_id', $opname_sub_location_id)
            ->get();

        $totalQtyReal = $posopnameitems->reduce(function ($carry, $item) {
            return bcadd($carry, $item->qty_real, 3);
        }, '0');
        $codeCounts = $posopnameitems
            ->groupBy(function ($item) {
                return $item->item->code ?? '-';
            })
            ->map(function ($group) {
                return $group->count();
            });
        return view('pages.printitem', [
            'posopnamesublocation' => collect([$posopnamesublocation]),
            'form_number' => $posopnamesublocation->form_number,
            'posopname' => $posopname,
            'posopnameitems' => $posopnameitems,
            'totalQtyReal' => $totalQtyReal,
            'codeCounts' => $codeCounts
        ]);
    }

// public function printitem(Request $request, $opname_sub_location_id)
// {
//     $posopnamesublocation = Posopnamesublocation::with(
//         'opname',
//         'sublocation.location',
//         'users',
//         'sublocation',
//         'opname.ambildarisublocation'
//     )->where('opname_sub_location_id', $opname_sub_location_id)->firstOrFail();

//     if ($posopnamesublocation->status === 'REQ PRINT') {
//         $posopnamesublocation->update(['status' => 'PRINTED']);
//     }

//     $posopname = Posopname::with('ambildarisublocation', 'location')
//         ->where('opname_id', $posopnamesublocation->opname_id)
//         ->first();

//     $posopnameitems = Posopnameitem::with(
//         'sublocation',
//         'opname',
//         'posopnamesublocation',
//         'opname.location',
//         'item',
//         'item.posunit'
//     )
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->get();

//     $pdf = Pdf::loadView('pages.printitem_pdf', [
//         'posopnamesublocation' => $posopnamesublocation,
//         'posopname' => $posopname,
//         'posopnameitems' => $posopnameitems,
//     ]);

//     // Simpan PDF sementara
//     $filePath = storage_path('app/public/print_'.$opname_sub_location_id.'.pdf');
//     $pdf->save($filePath);

//     // Kirim ke printer server (pakai shell_exec lp/lpr di Linux)
//     shell_exec("lp -d PRINTER_NAME " . escapeshellarg($filePath));

//     return response()->json(['success' => true, 'message' => 'Dokumen sudah dikirim ke printer']);
// }


    public function indexso($opname_id)
    {
        $files = Storage::disk('public')->files('templateso');
        $posopname = Posopname::select('opname_id', 'location_id', 'date')
            ->with('ambildarisublocation', 'location')
            ->where('opname_id', $opname_id)
            ->firstOrFail();
        return view('pages.Importso.Importso', compact('files', 'posopname'));
    }
    public function Importso(Request $request, $opname_id)
    {
        ini_set('max_execution_time', 180);

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
        $allFailures = [];
        foreach ($import->failures() as $failure) {
            $allFailures[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
        if (!empty($errors) || !empty($allFailures)) {
            return back()->withErrors(array_merge($errors, $allFailures));
        }
        return back()->with('success', 'SO import berhasil!');
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
}