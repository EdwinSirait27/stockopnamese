<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Mstock;
use App\Models\Branch;
use App\Jobs\ImportMstockDataJob;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
class DbController extends Controller
{
    public function index(Request $request)
{
    $allowedConnections = ['mysql', 'mysql2', 'mysql3', 'mysql4', 'mysql5'];
    $connection = in_array($request->input('db'), $allowedConnections) ? $request->input('db') : 'mysql';
    
    try {
        $namaDB = DB::connection($connection)->getDatabaseName();
        Log::info('Nama database: ' . $namaDB);
    } catch (\Exception $e) {
        Log::error('Gagal mendapatkan nama database: ' . $e->getMessage());
        abort(500, 'Database connection failed.');
    }

    if ($request->ajax()) {
        try {
            $mstock = (new Mstock)->setConnection($connection);
            $query = $mstock->newQuery();
            
            // Hanya load relasi branch jika connection adalah mysql
            if ($connection === 'mysql') {
                $query->with('branch');
            }

            $length = $request->input('length');

            if ($length == -1) {
                $data = $query->get();
            } else {
                $data = $query;
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('SALDO', function ($row) {
                    $awal = (double) $row->AWAL;
                    $masuk = (double) $row->MASUK;
                    $keluar = (double) $row->KELUAR;
                    return $awal + $masuk - $keluar;
                })
                ->addColumn('CABANG', function ($row) use ($connection) {
                    // Hanya tampilkan cabang jika connection mysql dan relasi ada
                    if ($connection === 'mysql' && isset($row->branch)) {
                        return $row->branch->CABANG ?? '-';
                    }
                    return '-'; // atau bisa return field lain yang ada di semua DB
                })
                ->rawColumns(['SALDO'])
                ->make(true);
                
        } catch (\Exception $e) {
            Log::error('DataTables error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    // Branch data hanya dari mysql
    try {
        $branch = Branch::on('mysql')->orderBy('BO')->get();
    } catch (\Exception $e) {
        $branch = collect();
    }
    
    return view('pages.DB.index', [
        'koneksi' => $connection,
        'db_yang_dipakai' => $namaDB,
        'branch' => $branch,
    ]);
}
// public function index(Request $request)
// {
//     $allowedConnections = ['mysql', 'mysql2', 'mysql3', 'mysql4', 'mysql5'];
//     $connection = in_array($request->input('db'), $allowedConnections) ? $request->input('db') : 'mysql';
//     try {
//         $namaDB = DB::connection($connection)->getDatabaseName();
//         Log::info('Nama database: ' . $namaDB);
//     } catch (\Exception $e) {
//         Log::error('Gagal mendapatkan nama database: ' . $e->getMessage());
//         abort(500, 'Database connection failed.');
//     }
//     if ($request->ajax()) {

//         try {
//             $mstock = (new Mstock)->setConnection($connection);
//             $query = $mstock->newQuery()->with('branch');

//             $length = $request->input('length');

//             if ($length == -1) {
//                 $data = $query->get();

//                 return DataTables::of($data)
//                     ->addIndexColumn()
//                     ->addColumn('SALDO', function ($row) {
//                         $awal = (double) $row->AWAL;
//                         $masuk = (double) $row->MASUK;
//                         $keluar = (double) $row->KELUAR;
//                         return $awal + $masuk - $keluar;
//                     })
//                     ->rawColumns(['SALDO'])
//                     ->make(true);
//             }

//             return DataTables::of($query)
//                 ->addIndexColumn()
//                 ->addColumn('SALDO', function ($row) {
//                     $awal = (double) $row->AWAL;
//                     $masuk = (double) $row->MASUK;
//                     $keluar = (double) $row->KELUAR;
//                     return $awal + $masuk - $keluar;
//                 })
//                 ->addColumn('CABANG', function ($row) {
//     return $row->branch->CABANG ?? '-';
// })

//                 ->rawColumns(['SALDO'])
//                 ->make(true);
//         } catch (\Exception $e) {
            
//             return response()->json(['error' => 'Server error'], 500);
//         }
//     }

 
//             $branch = Branch::orderBy('BO')->get();
//     return view('pages.DB.index', [
//         'koneksi' => $connection,
//         'db_yang_dipakai' => $namaDB,
//         'branch' => $branch,
//     ]);
// }
// public function import(Request $request)
// {
//     $allowedConnections = ['mysql', 'mysql2', 'mysql3', 'mysql4', 'mysql5'];
//     $sourceConnection = in_array($request->input('db'), $allowedConnections) ? $request->input('db') : 'mysql2';
//     $targetConnection = 'mysql'; // Tujuan akhir
//     $sourceModel = (new Mstock)->setConnectionNameDynamic($sourceConnection);
//     $targetModel = (new Mstock)->setConnectionNameDynamic($targetConnection);
//     $totalImported = 0;
//     $sourceModel->newQuery()->chunk(500, function ($rows) use ($targetModel, &$totalImported) {
//         foreach ($rows as $row) {
//             $targetModel->create([
//                 'BARA'    => $row->BARA,
//                 'BARA2'   => $row->BARA2,
//                 'NAMA'    => $row->NAMA,
//                 'AVER'    => $row->AVER,
//                 'AWAL'    => $row->AWAL,
//                 'MASUK'   => $row->MASUK,
//                 'KELUAR'  => $row->KELUAR,
//                 'SATUAN'  => $row->SATUAN,
//             ]);
//             $totalImported++;
//         }
//     });

//     Log::info("Import selesai dari [$sourceConnection] ke [$targetConnection] | Total: $totalImported");

//    return redirect()->route('DB.index')->with('success', "Import selesai dari [$sourceConnection] ke [$targetConnection]. Jumlah data: $totalImported");

// }
public function import(Request $request)
{
    $allowedConnections = ['mysql', 'mysql2', 'mysql3', 'mysql4', 'mysql5'];
    $sourceConnection = in_array($request->input('db'), $allowedConnections) ? $request->input('db') : 'mysql2';
    $targetConnection = 'mysql'; // Tujuan akhir
    
    $branchId = $request->input('bo_id'); // Ambil branch_id dari input form
    
    $sourceModel = (new Mstock)->setConnectionNameDynamic($sourceConnection);
    $targetModel = (new Mstock)->setConnectionNameDynamic($targetConnection);
    
    $totalImported = 0;

    $sourceModel->newQuery()->chunk(500, function ($rows) use ($targetModel, &$totalImported, $branchId) {
        foreach ($rows as $row) {
            $targetModel->create([
                'BARA'     => $row->BARA,
                'BARA2'    => $row->BARA2,
                'NAMA'     => $row->NAMA,
                'AVER'     => $row->AVER,
                'AWAL'     => $row->AWAL,
                'MASUK'    => $row->MASUK,
                'KELUAR'   => $row->KELUAR,
                'SATUAN'   => $row->SATUAN,
                'bo_id'=> $branchId,    
            ]);
            $totalImported++;
        }
    });

    Log::info("Import selesai dari [$sourceConnection] ke [$targetConnection] | Total: $totalImported");

    return redirect()->route('DB.index')->with('success', "Import selesai dari [$sourceConnection] ke [$targetConnection]. Jumlah data: $totalImported");
}

   public function getMstock(Request $request)
{
    // Ambil parameter DB dari request (default ke mysql)
    // $db = $request->input('db', 'utama'); // default 'utama' jika tidak ada input
    // $connection = $db === 'kedua' ? 'mysql2' : 'mysql';
    

    // // Gunakan koneksi dinamis
    // $mstock = new Mstock();
    // $mstock->setConnection($connection);

    // // Ambil data dengan query Eloquent
    // $mstocks = $mstock->newQuery()
    //     ->select(['BARA', 'BARA2', 'NAMA','AVER','SATUAN','SALDO'])
    //     ->get()
    //     ->map(function ($mstock) {
    //         return $mstock;
    //     });

    // // Return DataTables response
    // return DataTables::of($mstocks)->make(true);
}
}
//    public function index(Request $request)
//     {
//         if ($request->isMethod('post')) {
//             $db = $request->input('db');
//             $connection = $db === 'kedua' ? 'mysql2' : 'mysql';
//             // Pakai model dengan koneksi dinamis
//             $mstock = new Mstock();
//             $mstock->setConnection($connection);

//             $data = $mstock->newQuery()->get();

//             return view('pages.DB.index', [
//                 'data' => $data,
//                 'db_yang_dipakai' => $connection,
//             ]);
//         }
//         // GET method: tampilkan form saja
//         return view('pages.DB.index');
//     }
// public function index(Request $request)
// {
//     if ($request->isMethod('post')) {
//         $db = $request->input('db'); // nilai input: 'mysql', 'mysql2', 'mysql3', dst.
//         $allowedConnections = ['mysql', 'mysql2', 'mysql3', 'mysql4'];
//         $connection = in_array($db, $allowedConnections) ? $db : 'mysql';
// $databaseName = DB::connection($connection)->getDatabaseName();
//         $mstock = new Mstock();
//         $mstock->setConnection($connection);

//         $data = $mstock->newQuery()->get();

//         return view('pages.DB.index', [
//             'data' => $data,
//             'db_yang_dipakai' => $databaseName,
//         ]);
//     }

//     return view('pages.DB.index');
// }
// public function index(Request $request)
// {
//     if ($request->isMethod('post')) {
//         $db = $request->input('db'); // 'utama' atau 'kedua'
//         $connection = $db === 'kedua' ? 'mysql2' : 'mysql';

//         // Ambil nama database aktif
//         $namaDB = DB::connection($connection)->getDatabaseName();

//         // Inisialisasi model dengan koneksi dinamis
//         $mstock = (new Mstock)->setConnectionNameDynamic($connection);
//         $data = $mstock->newQuery()->get();

//         return view('pages.DB.index', [
//             'data' => $data,
//             'koneksi' => $connection,
//             'db_yang_dipakai' => $namaDB,
//         ]);
//     }

//     return view('pages.DB.index');
// }
// public function index(Request $request)
// {
//     if ($request->isMethod('post')) {
//         $db = $request->input('db'); // nama alias koneksi, contoh: 'utama', 'kedua', 'ketiga', dst

//         // Mapping alias input ke koneksi Laravel
//         $dbMap = [
//             'utama'   => 'mysql',
//             'kedua'   => 'mysql2',
//             'ketiga'  => 'mysql3',
//             // 'keempat' => 'mysql4',
//             // 'kelima'  => 'mysql5',
//         ];

//         $connection = $dbMap[$db] ?? 'mysql'; // fallback ke 'mysql' kalau tidak dikenali

//         // Ambil nama database aktif
//         $namaDB = DB::connection($connection)->getDatabaseName();

//         // Panggil model dengan koneksi dinamis
//         $mstock = (new Mstock)->setConnectionNameDynamic($connection);
//         $data = $mstock->newQuery()->get();

//         return view('pages.DB.index', [
//             'data' => $data,
//             'koneksi' => $connection,
//             'db_yang_dipakai' => $namaDB,
//         ]);
//     }

//     return view('pages.DB.index');
// }
// Controller example