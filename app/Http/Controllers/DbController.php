<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DbController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $db = $request->input('db');
            $connection = $db === 'kedua' ? 'mysql_second' : 'mysql';

            // Ambil data dari database yang dipilih
            $data = DB::connection($connection)->table('mstock')->get();

            return view('pages.DB.index', [
                'data' => $data,
                'db_yang_dipakai' => $connection
            ]);
        }

        // Jika GET, hanya tampilkan form
        return view('pages.DB.index');
    }
}
