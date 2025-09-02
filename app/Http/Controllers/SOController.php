<?php

namespace App\Http\Controllers;
use App\Imports\SoImport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class SOController extends Controller
{
public function index(Request $request)
{
    $db = $request->get('db', session('selected_db', 'mysql_third'));
    session(['selected_db' => $db]);
    $dbNames = [
        'mysql_third' => 'SE 001',
        'mysql_fourth' => 'SE 005',
        'mysql_fifth' => 'SE 008',
    ];
    $dbLabel = $dbNames[$db] ?? $db; 
    return view('pages.Stockopname.Stockopname', compact('db', 'dbLabel'));
}
public function refreshStockSoglo(Request $request) 
{
    $db = session('selected_db', 'mysql_third'); // pakai koneksi yg sudah dipilih

    try {
        Log::info("Refresh mstock_soglo dimulai.", ['db' => $db]);

        // Step 1: Hapus semua data dari tabel terkait
        DB::connection($db)->table('mstock_soglo')->truncate();
        Log::info("Tabel mstock_soglo berhasil dikosongkan.", ['db' => $db]);

        DB::connection($db)->table('mtoko_soglo')->truncate();
        Log::info("Tabel mtoko_soglo berhasil dikosongkan.", ['db' => $db]);

        DB::connection($db)->table('mtoko_det_soglo')->truncate();
        Log::info("Tabel mtoko_det_soglo berhasil dikosongkan.", ['db' => $db]);
        $sql = "
            INSERT INTO mstock_soglo (
                BARA, BARA2, NAMA, AWAL, MASUK, KELUAR, SALDO, AVER, HBELI, HJUAL, STATUS, KDGOL, KDTOKO, HPP, SATUAN
            )
            SELECT 
                BARA, BARA2, NAMA, AWAL, MASUK, KELUAR,
                (AWAL + MASUK - KELUAR) AS SALDO,
                AVER, HBELI, HJUAL, STATUS, KDGOL, KDTOKO, HPP, SATUAN
            FROM mstock
            WHERE INV = '1' AND KONSI = '0' AND KDGOL <> 'X0000000'
        ";
        DB::connection($db)->statement($sql);
        Log::info("Insert data baru ke mstock_soglo berhasil.", ['db' => $db]);

        return redirect()->back()->with('success', 'Data mstock_soglo, mtoko_soglo, dan mtoko_det_soglo berhasil di-refresh!');
    } catch (\Exception $e) {
        Log::error("Gagal refresh data.", [
            'db' => $db,
            'error' => $e->getMessage()
        ]);
        return redirect()->back()->with('error', 'Gagal refresh data: ' . $e->getMessage());
    }
}
  public function indexso(Request $request)
{
    $db = $request->get('db', session('selected_db', 'mysql_third'));
    session(['selected_db' => $db]);

    $dbNames = [
        'mysql_third'  => 'SE 001',
        'mysql_fourth' => 'SE 005',
        'mysql_fifth'  => 'SE 008',
    ];
    $dbLabel = $dbNames[$db] ?? $db;
    $files = Storage::disk('public')->files('templateso');
    return view('pages.Importso.Importso', compact('files', 'db', 'dbLabel'));
}
    public function Importso(Request $request)
{
    ini_set('max_execution_time', 180);
    // validasi file
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,xls'
    ]);

    // ambil db pilihan user
    $db = $request->get('db', session('selected_db', 'mysql_third'));
    session(['selected_db' => $db]);
    $dbNames = [
        'mysql_third'  => 'SE 001',
        'mysql_fourth' => 'SE 005',
        'mysql_fifth'  => 'SE 008',
    ];
    $dbLabel = $dbNames[$db] ?? $db;
    $errors = [];
    $import = new SoImport(
        $errors,
    $db
    );
    $import->import($request->file('file'));
    $allFailures = [];
    foreach ($import->failures() as $failure) {
        $allFailures[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
    }
    if (!empty($errors) || !empty($allFailures)) {
        return back()->withErrors(array_merge($errors, $allFailures));
    }
    return back()->with('success', "SO import berhasil ke database {$dbLabel}!");
}
 public function downloadso($filename)
    {
        $path = 'templateso/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }
        abort(404);
    }
public function getDatatables(Request $request)
{
    $db = $request->get('db', 'mysql_third');

    $query = DB::connection($db)
        ->table('mtoko_soglo as s')
        ->leftJoin('mtoko_det_soglo as d', 's.kdtoko', '=', 'd.KDTOKO')
        ->select(
            's.kdtoko',
            's.kettoko',
            's.masuk',
            's.personil',
            's.inpmasuk',
            's.BO',
            DB::raw('COUNT(d.id) as detail_count')
        )
        ->groupBy('s.kdtoko', 's.kettoko', 's.masuk', 's.personil', 's.inpmasuk', 's.BO');
    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('masuk', function($row) {
            switch ($row->masuk) {
                case 0: return 'Draft';
                case 1: return 'Printed';
                case 2: return 'Counting';
                case 3: return 'Req Print';
                default: return $row->masuk; // fallback kalau ada nilai ga jelas wkwkwk
            }
        })
        ->addColumn('action', function($row) use ($db) {
    $urlDetail = route('Stockopname.details', ['db' => $db, 'kdtoko' => $row->kdtoko]);
    $urlPrint  = route('Stockopname.print',   ['db' => $db, 'kdtoko' => $row->kdtoko]);
    return '
        <a href="'.$urlDetail.'" class="btn btn-sm btn-primary">Detail</a>
        <a href="'.$urlPrint.'" target="_blank" class="btn btn-sm btn-success">Print</a>
    ';
})

        ->rawColumns(['action'])
        ->make(true);
}
public function showDetails($db, $kdtoko)
{
    $dbNames = [
        'mysql_third' => 'SE 001',
        'mysql_fourth' => 'SE 005',
        'mysql_fifth' => 'SE 008',
    ];
    $dbLabel = $dbNames[$db] ?? $db; 
    $store = DB::connection($db)
        ->table('mtoko_soglo')
        ->where('kdtoko', $kdtoko)
        ->select('kdtoko', 'kettoko', 'personil', 'masuk', 'inpmasuk', 'balik')
        ->first();

    return view('pages.Stockopname.details', compact('db','dbLabel', 'kdtoko', 'store'));
}
// public function showprint($db, $kdtoko)
// {
//     $dbNames = [
//         'mysql_third' => 'SE 001',
//         'mysql_fourth' => 'SE 005',
//         'mysql_fifth' => 'SE 008',
//     ];
//     $dbLabel = $dbNames[$db] ?? $db; 
//     $store = DB::connection($db)
//         ->table('mtoko_soglo')
//         ->where('kdtoko', $kdtoko)
//         ->select('kdtoko', 'kettoko', 'personil', 'masuk', 'inpmasuk', 'balik')
//         ->first();

//     return view('pages.Stockopname.print', compact('db','dbLabel', 'kdtoko', 'store'));
// }
public function showprint($db, $kdtoko)
{
    $dbNames = [
        'mysql_third' => 'SE 001',
        'mysql_fourth' => 'SE 005',
        'mysql_fifth' => 'SE 008',
    ];
    $dbLabel = $dbNames[$db] ?? $db; 

    // ambil header toko
    $store = DB::connection($db)
        ->table('mtoko_soglo')
        ->where('kdtoko', $kdtoko)
        ->select('kdtoko', 'kettoko', 'personil', 'masuk', 'inpmasuk', 'balik')
        ->first();

    // ambil detail barang
    $details = DB::connection($db)
        ->table('mtoko_det_soglo as d')
        ->leftJoin('mstock as s', 'd.BARA', '=', 's.BARA')
        ->where('d.KDTOKO', $kdtoko)
        ->select(
            'd.KDTOKO',
            'd.BARA',
            'd.NOURUT',
            'd.FISIK',
            'd.BARCODE',
            'd.ID',
            's.NAMA as nama_barang'
        )
        ->orderBy('d.KDTOKO')
        ->get();
        $nameCounts = $details->groupBy('nama_barang')->map->count();
       $totalFisik = DB::connection($db)
    ->table('mtoko_det_soglo')
    ->where('KDTOKO', $kdtoko)
    ->sum('FISIK');

    return view('pages.Stockopname.print', compact('db','totalFisik','dbLabel', 'kdtoko', 'store', 'details','nameCounts'));
}
// public function checkPrint()
// {
//     $dbNames = [
//         'mysql_third' => 'SE 001',
//         'mysql_fourth' => 'SE 005',
//         'mysql_fifth' => 'SE 008',
//     ];
//      $db = session('selected_db',$dbNames);

//     sleep(8);

//     $job = DB::connection($db)
//         ->table('mtoko_soglo')
//         ->where('masuk', 3) // Req Print
//         ->first();

//     if ($job) {
//         $store = DB::connection($db)
//             ->table('mtoko_soglo')
//             ->where('kdtoko', $job->kdtoko)
//             ->select('kdtoko', 'kettoko', 'personil', 'masuk', 'inpmasuk', 'balik')
//             ->first();

//         $details = DB::connection($db)
//             ->table('mtoko_det_soglo as d')
//             ->leftJoin('mstock as s', 'd.BARA', '=', 's.BARA')
//             ->where('d.KDTOKO', $job->kdtoko)
//             ->select(
//                 'd.KDTOKO',
//                 'd.BARA',
//                 'd.NOURUT',
//                 'd.FISIK',
//                 'd.BARCODE',
//                 'd.ID',
//                 's.NAMA as nama_barang'
//             )
//             ->orderBy('d.NOURUT')
//             ->get();

//         $totalFisik = DB::connection($db)
//             ->table('mtoko_det_soglo')
//             ->where('KDTOKO', $job->kdtoko)
//             ->sum('FISIK');

//         $nameCounts = $details->groupBy('nama_barang')->map->count();

//         $html = view('pages.Stockopname.print', [
//             'db' => $db,
//             'dbLabel' => $db,
//             'kdtoko' => $job->kdtoko,
//             'store' => $store,
//             'details' => $details,
//             'totalFisik' => $totalFisik,
//             'nameCounts' => $nameCounts,
//         ])->render();

//         DB::connection($db)
//             ->table('mtoko_soglo')
//             ->where('kdtoko', $job->kdtoko)
//             ->update(['masuk' => 1]); // Printed

//         return response()->json([
//             'status' => 'OK',
//             'kdtoko' => $job->kdtoko,
//             'html' => $html
//         ]);
//     }
//     return response()->json(['status' => 'NO JOB']);
// }
public function checkPrint()
{
    $dbNames = [
        'mysql_third'  => 'SE 001',
        'mysql_fourth' => 'SE 005',
        'mysql_fifth'  => 'SE 008',
    ];

    // Ambil key dari session, default mysql_third
    $dbKey = session('selected_db', 'mysql_third');

    // Ambil label untuk ditampilkan
    $dbLabel = $dbNames[$dbKey] ?? $dbKey;

    sleep(8);

    $job = DB::connection($dbKey)
        ->table('mtoko_soglo')
        ->where('masuk', 3) // Req Print
        ->first();

    if ($job) {
        $store = DB::connection($dbKey)
            ->table('mtoko_soglo')
            ->where('kdtoko', $job->kdtoko)
            ->select('kdtoko', 'kettoko', 'personil', 'masuk', 'inpmasuk', 'balik')
            ->first();

        $details = DB::connection($dbKey)
            ->table('mtoko_det_soglo as d')
            ->leftJoin('mstock as s', 'd.BARA', '=', 's.BARA')
            ->where('d.KDTOKO', $job->kdtoko)
            ->select(
                'd.KDTOKO',
                'd.BARA',
                'd.NOURUT',
                'd.FISIK',
                'd.BARCODE',
                'd.ID',
                's.NAMA as nama_barang'
            )
            ->orderBy('d.NOURUT')
            ->get();

        $totalFisik = DB::connection($dbKey)
            ->table('mtoko_det_soglo')
            ->where('KDTOKO', $job->kdtoko)
            ->sum('FISIK');

        $nameCounts = $details->groupBy('nama_barang')->map->count();

        $html = view('pages.Stockopname.print', [
            'db'         => $dbKey,    // koneksi
            'dbLabel'    => $dbLabel,  // label SE 001 / SE 005 / SE 008
            'kdtoko'     => $job->kdtoko,
            'store'      => $store,
            'details'    => $details,
            'totalFisik' => $totalFisik,
            'nameCounts' => $nameCounts,
        ])->render();

        DB::connection($dbKey)
            ->table('mtoko_soglo')
            ->where('kdtoko', $job->kdtoko)
            ->update(['masuk' => 1]); // Printed

        return response()->json([
            'status' => 'OK',
            'kdtoko' => $job->kdtoko,
            'html'   => $html
        ]);
    }

    return response()->json(['status' => 'NO JOB']);
}




public function getDetailDatatables(Request $request, $db, $kdtoko)
{
    $query = DB::connection($db)
        ->table('mtoko_det_soglo as d')
        ->leftJoin('mstock as s', 'd.BARA', '=', 's.BARA')
        ->where('d.KDTOKO', $kdtoko)
        ->select(
            'd.KDTOKO',
            'd.BARA',
            'd.NOURUT',
            'd.FISIK',
            'd.BARCODE',
            'd.ID',
            's.NAMA as nama_barang'
        );

    return DataTables::of($query)
        ->addIndexColumn()
        ->filterColumn('nama_barang', function($query, $keyword) {
            // biar searching ke tabel mstock juga
            $query->where('s.NAMA', 'like', "%{$keyword}%");
        })
        ->editColumn('KDTOKO', fn($row) => $row->KDTOKO ?? 'empty')
        ->editColumn('BARA', fn($row) => $row->BARA ?? 'empty')
        ->editColumn('NOURUT', fn($row) => $row->NOURUT ?? 'empty')
        ->editColumn('FISIK', fn($row) => $row->FISIK ?? 'empty')
        ->editColumn('BARCODE', fn($row) => $row->BARCODE ?? 'empty')
        ->editColumn('ID', fn($row) => $row->ID ?? 'empty')
        ->editColumn('nama_barang', fn($row) => $row->nama_barang ?? 'empty')
        ->make(true);
}





}
