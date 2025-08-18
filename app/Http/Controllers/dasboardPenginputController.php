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
    public function scan($opname_sub_location_id)
    {
        Log::info('Masuk ke method scan', [
            'opname_sub_location_id' => $opname_sub_location_id
        ]);
        $posopnamesublocation = Posopnamesublocation::with([
            'opname',
            'sublocation.location',
            'users',
            'opname.ambildarisublocation'
        ])
            ->where('opname_sub_location_id', $opname_sub_location_id)
            ->firstOrFail();
        $posopname = Posopname::with(['ambildarisublocation', 'location'])
            ->where('opname_id', $posopnamesublocation->opname_id)
            ->first();

        return view('pages.scan', [
            'posopnamesublocation' => $posopnamesublocation,
            'opname_id' => $posopnamesublocation->opname_id,
            'posopname' => $posopname
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
        $exists = Posopnameitem::where('opname_sub_location_id', $opname_sub_location_id)
            ->where('item_master_id', $item->item_master_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Item sudah pernah masuk sebelumnya',
                'data' => [
                    'item_master_id' => $item->item_master_id,
                    'barcode' => $item->barcode,
                    'name' => $item->name,
                    'qty' => 1
                ]
            ], 409); 
        }
        return response()->json([
            'success' => true,
            'message' => 'Item ditemukan',
            'data' => [
                'item_master_id' => $item->item_master_id,
                'barcode' => $item->barcode,
                'name' => $item->name,
                'qty' => 0
            ]
        ]);
    }
    public function getPosopnameItems($opname_sub_location_id)
    {
        if (!Posopnamesublocation::where('opname_sub_location_id', $opname_sub_location_id)->exists()) {
            abort(404, 'Opname Sub Location tidak ditemukan');
        }
        $posopnameitems = Posopnameitem::with('item')
            ->where('opname_sub_location_id', $opname_sub_location_id)
            ->get();
        $posopnameitems = $posopnameitems->map(function ($item) {
            $item->action = '
               <button class="btn btn-sm btn-primary" onclick="editItem(' . $item->opname_item_id . ')">Edit</button>
    ';
            return $item;
        });
        return response()->json([
            'data' => $posopnameitems
        ]);
    }
    public function saveScannedItem(Request $request, $opname_sub_location_id)
    {
        // Log semua request yang masuk
        \Log::info('Payload saveScannedItem:', $request->all());

        $validated = $request->validate([
            'item_master_id' => 'required|integer',
            'qty' => 'nullable',
            'barcode' => 'nullable|string|max:255'
        ]);

        $subLocation = Posopnamesublocation::with('opname')
            ->findOrFail($opname_sub_location_id);

        $opnameItemId = (int) (microtime(true) * 10000);

        Posopnameitem::create([
            'opname_item_id' => $opnameItemId,
            'opname_id' => $subLocation->opname_id,
            'item_master_id' => $validated['item_master_id'],
            'qty_real' => (float) $validated['qty'],
            'note' => $validated['barcode'] ?? '',
            'sub_location_id' => $subLocation->sub_location_id,
            'opname_sub_location_id' => $opname_sub_location_id,
            'date' => Carbon::now(),
        ]);

        \Log::info('Item berhasil disimpan', [
            'opname_item_id' => $opnameItemId,
            'sub_location_id' => $subLocation->sub_location_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil disimpan'
        ]);
    }
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
            'qty_real' => 'required|numeric|min:0'
        ]);

        $item = Posopnameitem::findOrFail($id);
        $item->qty_real = $request->qty_real;
        $item->save();

        return response()->json([
            'message' => 'Data berhasil diperbarui'
        ]);
    }
}