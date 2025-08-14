<?php
namespace App\Http\Controllers;
use App\Models\Posopname;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Posopnameitem;
use App\Models\Positemmaster;
use Illuminate\Support\Facades\Auth;
use App\Models\Posopnamesublocation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
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

// public function scan($opname_sub_location_id)
// {
//     Log::info('Masuk ke method scan', ['opname_sub_location_id' => $opname_sub_location_id]);

//     // Ambil Posopnamesublocation beserta relasi yang diperlukan
//     $posopnamesublocation = Posopnamesublocation::with([
//             'opname',
//             'sublocation.location',
//             'users',
//             'sublocation',
//             'opname.ambildarisublocation',
//             'posopnameitems.item' // relasi ke posopnameitems + item
//         ])
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->firstOrFail();

//     $opname_id = $posopnamesublocation->opname_id;

//     // Ambil data opname untuk informasi tambahan
//     $posopname = Posopname::with(['ambildarisublocation', 'location'])
//         ->where('opname_id', $opname_id)
//         ->first();

//     // Ambil semua Posopnameitem berdasarkan opname_sub_location_id
//     // $posopnameitems_by_location = $posopnamesublocation->posopnameitems;

//     return view('pages.scan', compact(
//         'posopnamesublocation',
//         'opname_id',
//         'posopname'
//     ));
// }
// public function getPosopnameItems($opname_sub_location_id)
// {
//     // Ambil Posopnamesublocation dulu, agar validasi jika id tidak ada
//     $posopnamesublocation = Posopnamesublocation::findOrFail($opname_sub_location_id);

//     // Ambil semua posopnameitems beserta relasi item untuk opname_sub_location_id tersebut
//     $posopnameitems = Posopnameitem::with('item')
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->get();

//     // Return JSON untuk datatables
//     return response()->json([
//         'data' => $posopnameitems
//     ]);
// }
public function scan($opname_sub_location_id)
{
    Log::info('Masuk ke method scan', [
        'opname_sub_location_id' => $opname_sub_location_id
    ]);

    // Ambil Posopnamesublocation + relasi yang dibutuhkan untuk header halaman
    $posopnamesublocation = Posopnamesublocation::with([
            'opname',
            'sublocation.location',
            'users',
            'opname.ambildarisublocation'
        ])
        ->where('opname_sub_location_id', $opname_sub_location_id)
        ->firstOrFail();
    // Ambil data opname terkait (opsional kalau dibutuhkan di view)
    $posopname = Posopname::with(['ambildarisublocation', 'location'])
        ->where('opname_id', $posopnamesublocation->opname_id)
        ->first();

    return view('pages.scan', [
        'posopnamesublocation' => $posopnamesublocation,
        'opname_id'            => $posopnamesublocation->opname_id,
        'posopname'            => $posopname
    ]);
}
public function reqPrint($id)
{
    $posopnamesublocation = Posopnamesublocation::findOrFail($id);
    $posopnamesublocation->status = 'REQ PRINT';
      $posopnamesublocation->user_id = Auth::id();
    $posopnamesublocation->save();

    return redirect()->back()->with('success', 'Status berhasil diubah menjadi REQ PRINT');
}

// public function scanBarcodePreview(Request $request, $opname_sub_location_id)
// {
//     $request->validate([
//         'barcode' => 'required|string|max:255'
//     ]);

//     $barcode = trim($request->barcode);
//     Log::info('Barcode yang dicari', ['input' => $barcode]);

//     $item = Positemmaster::where('barcode', 'LIKE', "%{$barcode}%")
//         ->orWhere('code', 'LIKE', "%{$barcode}%")
//         ->orWhere('barcode_2', 'LIKE', "%{$barcode}%")
//         ->orWhere('barcode_3', 'LIKE', "%{$barcode}%")
//         ->first();

//     if (!$item) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Barcode tidak ditemukan'
//         ], 404);
//     }

//     // Return tanpa simpan ke DB
//     return response()->json([
//         'success' => true,
//         'message' => 'Item ditemukan',
//         'data' => [
//             'item_master_id' => $item->item_master_id,
//             'barcode'        => $item->barcode,
//             'name'           => $item->name,
//             'qty'            => 0
//         ]
//     ]);
// }
public function scanBarcodePreview(Request $request, $opname_sub_location_id)
{
    $request->validate([
        'barcode' => 'required|string|max:255'
    ]);

    $barcode = trim($request->barcode);
    Log::info('Barcode yang dicari', ['input' => $barcode]);

    $item = Positemmaster::where('barcode', 'LIKE', "%{$barcode}%")
        ->orWhere('code', 'LIKE', "%{$barcode}%")
        ->orWhere('barcode_2', 'LIKE', "%{$barcode}%")
        ->orWhere('barcode_3', 'LIKE', "%{$barcode}%")
        ->first();

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Barcode tidak ditemukan'
        ], 404);
    }

    // Cek apakah item sudah masuk di opname_sub_location_id ini
    $exists = Posopnameitem::where('opname_sub_location_id', $opname_sub_location_id)
        ->where('item_master_id', $item->item_master_id)
        ->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Item sudah pernah masuk sebelumnya',
            'data' => [
                'item_master_id' => $item->item_master_id,
                'barcode'        => $item->barcode,
                'name'           => $item->name,
                'qty'            => 1
            ]
        ], 409); // kode 409 Conflict sebagai tanda warning
    }

    // Jika belum ada, return data item seperti biasa tanpa simpan
    return response()->json([
        'success' => true,
        'message' => 'Item ditemukan',
        'data' => [
            'item_master_id' => $item->item_master_id,
            'barcode'        => $item->barcode,
            'name'           => $item->name,
            'qty'            => 0
        ]
    ]);
}
// public function getPosopnameItems($opname_sub_location_id)
// {
//     // Validasi apakah ID sub-location ada
//     if (!Posopnamesublocation::where('opname_sub_location_id', $opname_sub_location_id)->exists()) {
//         abort(404, 'Opname Sub Location tidak ditemukan');
//     }

//     // Ambil semua item terkait + relasi item
//     $posopnameitems = Posopnameitem::with('item')
//         ->where('opname_sub_location_id', $opname_sub_location_id)
//         ->get();

//     return response()->json([
//         'data' => $posopnameitems
//     ]);
// }
public function getPosopnameItems($opname_sub_location_id)
{
    // Validasi apakah ID sub-location ada
    if (!Posopnamesublocation::where('opname_sub_location_id', $opname_sub_location_id)->exists()) {
        abort(404, 'Opname Sub Location tidak ditemukan');
    }

    // Ambil semua item terkait + relasi item
    $posopnameitems = Posopnameitem::with('item')
        ->where('opname_sub_location_id', $opname_sub_location_id)
        ->get();

   
    $posopnameitems = $posopnameitems->map(function ($item) {
    $item->action = '
               <button class="btn btn-sm btn-primary" onclick="editItem('.$item->opname_item_id.')">Edit</button>
    ';
    return $item;
});


    return response()->json([
        'data' => $posopnameitems
    ]);
}







public function saveScannedItem(Request $request, $opname_sub_location_id)
{
    $validated = $request->validate([
        'item_master_id' => 'required|integer',
        'qty'            => 'required|numeric|min:0.001',
        'barcode'        => 'nullable|string|max:255' // untuk simpan di note
    ]);

    // Ambil data sub location yang sedang diakses
    $subLocation = Posopnamesublocation::with('opname')
        ->findOrFail($opname_sub_location_id);

    // Generate opname_item_id unik
   $opnameItemId = (int) (microtime(true) * 10000);; // bisa juga pakai format lain jika perlu
    // Buat data baru
    Posopnameitem::create([
        'opname_item_id'         => $opnameItemId,
        'opname_id'              => $subLocation->opname_id, // dari relasi
        'item_master_id'         => $validated['item_master_id'],
        'qty_real'               => (float) $validated['qty'],
        'note'                   => $validated['barcode'] ?? '', // simpan barcode hasil scan
        'sub_location_id'        => $subLocation->sub_location_id,
        'opname_sub_location_id' => $opname_sub_location_id,
        'date'                   => Carbon::now(), // timestamp sekarang
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Item berhasil disimpan'
    ]);
}

// public function saveScannedItem(Request $request, $opname_sub_location_id)
// {
//     $validated = $request->validate([
//         'item_master_id' => 'required|integer',
//         'qty'            => 'required|numeric|min:0.001' // biar cocok sama decimal:3
//     ]);

//     Posopnameitem::create([
//         'opname_sub_location_id' => $opname_sub_location_id,
//         'item_master_id'         => $validated['item_master_id'],
//         'qty_real'               => (float) $validated['qty'] // simpan sebagai float
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Item berhasil disimpan'
//     ]);
// }




// PosopnameItemController.php
public function showposopnameitem($id)
{
    $item = Posopnameitem::with('item')->findOrFail($id);

    return response()->json([
        'data' => $item
    ]);
}

public function update(Request $request, $id)
{
    $request->validate([
        'qty_real' => 'required|numeric|min:0.001'
    ]);

    $item = Posopnameitem::findOrFail($id);
    $item->qty_real = $request->qty_real;
    $item->save();

    return response()->json([
        'message' => 'Data berhasil diperbarui'
    ]);
}


}

// public function scanPost(Request $request, $opname_sub_location_id)
// {
//     $barcodeInput = $request->input('barcode');
//     if (!$barcodeInput) {
//         return redirect()->back()->with('error', 'Barcode harus diisi');
//     }

//     // Fungsi bantu cari item master di kolom barcode, code, barcode2, barcode3 berturut-turut
//     $itemMaster = Positemmaster::where('barcode', $barcodeInput)
//         ->orWhere('code', $barcodeInput)
//         ->orWhere('barcode_2', $barcodeInput)
//         ->orWhere('barcode_3', $barcodeInput)
//         ->first();

//     if (!$itemMaster) {
//         return redirect()->back()->with('error', 'Item tidak ditemukan di semua kolom barcode.');
//     }

//     // Cari semua posopnameitem yang pakai item_master_id ini
//     $posopnameitems = Posopnameitem::where('item_master_id', $itemMaster->item_master_id)
//         ->with('item')
//         ->get();

//     // Ambil data Posopnamesublocation dan Posopname untuk konteks lokasi
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
//         // Belum ada item di opname manapun, kirim info dan itemMaster
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

// public function scanBarcode(Request $request, $opname_sub_location_id)
// {
//     $request->validate([
//         'barcode' => 'required|string|max:255'
//     ]);

//     // Pastikan sub-location ada
//     $posopnamesublocation = Posopnamesublocation::findOrFail($opname_sub_location_id);

//     $barcode = $request->barcode;

//     // Cari item di kolom barcode -> code -> barcode_2 -> barcode_3
//     $item = Positemmaster::where('barcode', $barcode)
//         ->orWhere('code', $barcode)
//         ->orWhere('barcode_2', $barcode)
//         ->orWhere('barcode_3', $barcode)
//         ->first();

//     if (!$item) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Barcode tidak ditemukan di semua kolom'
//         ], 404);
//     }

//     // Cek apakah item ini sudah ada di opname_sub_location
//     $existing = Posopnameitem::where('opname_sub_location_id', $posopnamesublocation->opname_sub_location_id)
//         ->where('item_id', $item->item_master_id) // pakai primary key Positemmaster
//         ->first();

//     if ($existing) {
//         // Kalau sudah ada → tambah qty_real
//         $existing->increment('qty_real', 1);
//         $posopnameitem = $existing;
//     } else {
//         // Kalau belum ada → buat baru
//         $posopnameitem = Posopnameitem::create([
//             'opname_sub_location_id' => $posopnamesublocation->opname_sub_location_id,
//             'item_id' => $item->item_master_id,
//             'qty_real' => 1
//         ]);
//     }

//     return response()->json([
//         'success' => true,
//         'message' => 'Item berhasil ditambahkan / diperbarui',
//         'data'    => $posopnameitem->load('item')
//     ]);
// }
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