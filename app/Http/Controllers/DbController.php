<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Mstock;
use Yajra\DataTables\Facades\DataTables;



class DbController extends Controller
{
   public function index(Request $request)
    {
        // if ($request->isMethod('post')) {
        //     $db = $request->input('db');
        //     $connection = $db === 'kedua' ? 'mysql2' : 'mysql';

        //     // Pakai model dengan koneksi dinamis
        //     $mstock = new Mstock();
        //     $mstock->setConnection($connection);

        //     $data = $mstock->newQuery()->get();

        //     return view('pages.DB.index', [
        //         'data' => $data,
        //         'db_yang_dipakai' => $connection,
        //     ]);
        // }

        // GET method: tampilkan form saja
        return view('pages.DB.index');
    }
   public function getMstock(Request $request)
{
    // Ambil parameter DB dari request (default ke mysql)
    $db = $request->input('db', 'utama'); // default 'utama' jika tidak ada input
    $connection = $db === 'kedua' ? 'mysql2' : 'mysql';
    

    // Gunakan koneksi dinamis
    $mstock = new Mstock();
    $mstock->setConnection($connection);

    // Ambil data dengan query Eloquent
    $mstocks = $mstock->newQuery()
        ->select(['BARA', 'BARA2', 'NAMA','AVER','SATUAN','SALDO'])
        ->get()
        ->map(function ($mstock) {
            return $mstock;
        });

    // Return DataTables response
    return DataTables::of($mstocks)->make(true);
}
}
