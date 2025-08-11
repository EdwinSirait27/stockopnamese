<?php

namespace App\Http\Controllers;

use App\Models\Posopname;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Posopnameitem;
use App\Models\Positemmaster;
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
//     public function scan($opname_sub_location_id)
// {
//     Log::info('Masuk ke method scan', ['opname_sub_location_id' => $opname_sub_location_id]);
//     $posopnamesublocation = Posopnamesublocation::with([
//             'opname',
//             'sublocation.location',
//             'users',
//             'sublocation',
//             'opname.ambildarisublocation'
//         ])
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->firstOrFail();
//     $opname_id = $posopnamesublocation->opname_id;
//     $posopname = Posopname::with(['ambildarisublocation', 'location'])
//         ->where('opname_id', $opname_id)
//         ->first();

//     return view('pages.scan', compact('posopnamesublocation', 'opname_id', 'posopname'));
// }
public function scan($opname_sub_location_id)
{
    Log::info('Masuk ke method scan', ['opname_sub_location_id' => $opname_sub_location_id]);

    // Ambil Posopnamesublocation beserta relasi yang diperlukan
    $posopnamesublocation = Posopnamesublocation::with([
            'opname',
            'sublocation.location',
            'users',
            'sublocation',
            'opname.ambildarisublocation',
            'posopnameitems.item' // relasi ke posopnameitems + item
        ])
        ->where('opname_sub_location_id', $opname_sub_location_id)
        ->firstOrFail();

    $opname_id = $posopnamesublocation->opname_id;

    // Ambil data opname untuk informasi tambahan
    $posopname = Posopname::with(['ambildarisublocation', 'location'])
        ->where('opname_id', $opname_id)
        ->first();

    // Ambil semua Posopnameitem berdasarkan opname_sub_location_id
    $posopnameitems_by_location = $posopnamesublocation->posopnameitems;

    return view('pages.scan', compact(
        'posopnamesublocation',
        'opname_id',
        'posopname',
        'posopnameitems_by_location'
    ));
}

public function scanPost(Request $request, $opname_sub_location_id)
{
    $barcodeInput = $request->input('barcode');
    if (!$barcodeInput) {
        return redirect()->back()->with('error', 'Barcode harus diisi');
    }

    // Fungsi bantu cari item master di kolom barcode, code, barcode2, barcode3 berturut-turut
    $itemMaster = Positemmaster::where('barcode', $barcodeInput)
        ->orWhere('code', $barcodeInput)
        ->orWhere('barcode_2', $barcodeInput)
        ->orWhere('barcode_3', $barcodeInput)
        ->first();

    if (!$itemMaster) {
        return redirect()->back()->with('error', 'Item tidak ditemukan di semua kolom barcode.');
    }

    // Cari semua posopnameitem yang pakai item_master_id ini
    $posopnameitems = Posopnameitem::where('item_master_id', $itemMaster->item_master_id)
        ->with('item')
        ->get();

    // Ambil data Posopnamesublocation dan Posopname untuk konteks lokasi
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

    if ($posopnameitems->isEmpty()) {
        // Belum ada item di opname manapun, kirim info dan itemMaster
        return view('pages.scan', compact(
            'posopnamesublocation',
            'opname_id',
            'posopname',
            'itemMaster'
        ))->with('info', 'Item belum masuk dalam opname manapun.');
    }

    // Kirim semua posopnameitems ke view
    return view('pages.scan', compact(
        'posopnamesublocation',
        'opname_id',
        'posopname',
        'posopnameitems'
    ));
}







}

// public function scan($opname_sub_location_id)
// {
//     $posopnamesublocation = Posopnamesublocation::with([
//             'opname',
//             'sublocation.location',
//             'users',
//             'sublocation',
//             'opname.ambildarisublocation'
//         ])
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->firstOrFail();
//     $opname_id = $posopnamesublocation->opname_id;
//     $posopname = Posopname::with(['ambildarisublocation', 'location'])
//         ->where('opname_id', $opname_id)
//         ->first();

//     // Tampilkan halaman scan dengan form input barcode
//     return view('pages.scan', compact('posopnamesublocation', 'opname_id', 'posopname', 'posopnameitem' ));
// }


// public function scanPost(Request $request, $opname_sub_location_id)
// {
//     $barcode = $request->input('barcode');
//     if (!$barcode) {
//         return redirect()->back()->with('error', 'Barcode harus diisi');
//     }

//     // Cari Positemmaster berdasarkan barcode
//     $itemMaster = Positemmaster::where('barcode', $barcode)->first();
//     if (!$itemMaster) {
//         return redirect()->back()->with('error', 'Barcode tidak ditemukan di item master.');
//     }

//     // Cari Posopnameitem berdasarkan item_master_id dan opname_sub_location_id
//     $posopnameitem = Posopnameitem::where('item_master_id', $itemMaster->item_master_id)
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->with('item')  // eager load relasi item (Positemmaster)
//         ->first();

//     if (!$posopnameitem) {
//         return redirect()->back()->with('error', 'Item tidak ditemukan pada opname sub location ini.');
//     }

//     // Ambil data Posopnamesublocation dan Posopname
//     $posopnamesublocation = Posopnamesublocation::with([
//             'opname',
//             'sublocation.location',
//             'users',
//             'sublocation',
//             'opname.ambildarisublocation'
//         ])
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->firstOrFail();

//     $opname_id = $posopnamesublocation->opname_id;
//     $posopname = Posopname::with(['ambildarisublocation', 'location'])
//         ->where('opname_id', $opname_id)
//         ->first();

//     return view('pages.scan', compact('posopnamesublocation', 'opname_id', 'posopname', 'posopnameitem'));
// }
// public function scanPost(Request $request, $opname_sub_location_id)
// {
//     $barcode = $request->input('barcode');
//     if (!$barcode) {
//         return redirect()->back()->with('error', 'Barcode harus diisi');
//     }

//     // Cari Positemmaster berdasarkan barcode
//     $itemMaster = Positemmaster::where('barcode', $barcode)->first();
//     if (!$itemMaster) {
//         return redirect()->back()->with('error', 'Barcode tidak ditemukan di item master.');
//     }

//     // Cari Posopnameitem berdasarkan item_master_id tanpa filter opname_sub_location_id
//     $posopnameitems = Posopnameitem::where('item_master_id', $itemMaster->item_master_id)
//         ->with('item')  // eager load relasi item (Positemmaster)
//         ->get();

//     // Ambil data Posopnamesublocation dan Posopname untuk konteks lokasi scan
//     $posopnamesublocation = Posopnamesublocation::with([
//             'opname',
//             'sublocation.location',
//             'users',
//             'sublocation',
//             'opname.ambildarisublocation'
//         ])
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->firstOrFail();

//     $opname_id = $posopnamesublocation->opname_id;

//     $posopname = Posopname::with(['ambildarisublocation', 'location'])
//         ->where('opname_id', $opname_id)
//         ->first();

//     if ($posopnameitems->isEmpty()) {
//         // Kalau tidak ada item di opname manapun, tetap kirim itemMaster saja
//         return view('pages.scan', compact(
//             'posopnamesublocation',
//             'opname_id',
//             'posopname',
//             'itemMaster'
//         ))->with('info', 'Item belum masuk dalam opname manapun.');
//     }

//     // Kirim semua posopnameitems ke view
//     return view('pages.scan', compact(
//         'posopnamesublocation',
//         'opname_id',
//         'posopname',
//         'posopnameitems'
//     ));
// }