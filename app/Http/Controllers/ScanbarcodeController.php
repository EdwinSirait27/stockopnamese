<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mstocksoglo;
use App\Models\Mstockitem;

use Illuminate\Support\Facades\Log;
class ScanbarcodeController extends Controller
{
    public function index()
    {
        return view('pages.scanbarcode');
    }
  


public function scanBarcode(Request $request)
{
    \Log::info('=== [START SCAN BARCODE] ===');
    \Log::info('Input barcode: ' . $request->BARA2);

    $request->validate(['BARA2' => 'required|string']);
    $barcode = $request->BARA2;

    // 1. Cari di Mstocksoglo.BARA2
    \Log::info('[STEP 1] Mencari di Mstocksoglo.BARA2...');
    $product = Mstocksoglo::where('BARA2', $barcode)->first();
    if ($product) {
        \Log::info('✅ Ditemukan di Mstocksoglo.BARA2: ' . $barcode);
        return response()->json($product);
    }

    // 2. Cari di Mstocksoglo.BARA
    \Log::info('[STEP 2] Mencari di Mstocksoglo.BARA...');
    $product = Mstocksoglo::where('BARA', $barcode)->first();
    if ($product) {
        \Log::info('✅ Ditemukan di Mstocksoglo.BARA: ' . $barcode);
        return response()->json($product);
    }
    // 3. Cari di Mstockitem.BARA1
    \Log::info('[STEP 3] Mencari di Mstockitem.BARA1...');
    $productItem = Mstockitem::where('BARA1', $barcode)->first();
    if ($productItem) {
        $itemBARA = $productItem->BARA;
        \Log::info('📦 Ditemukan di Mstockitem.BARA1: ' . $barcode . ', dengan BARA: ' . $itemBARA);

        $inSoglo = Mstocksoglo::where('BARA', $itemBARA)->first();

        if ($inSoglo) {
            \Log::info('✅ Ditemukan di Mstocksoglo via Mstockitem.BARA1: ' . $itemBARA);
            return response()->json($inSoglo);
        } else {
            \Log::info('⚠️ Item ditemukan di Mstockitem.BARA1 tapi belum masuk SO: ' . $barcode);
            return response()->json([
                'message' => 'Item ditemukan, tetapi tidak untuk di stock opname',
                'status' => 'not_for_so',
                'item' => $productItem
            ], 200);
        }
    }

    // 4. Cari di Mstockitem.BARA
    \Log::info('[STEP 4] Mencari di Mstockitem.BARA...');
    $productItem = Mstockitem::where('BARA', $barcode)->first();
    if ($productItem) {
        \Log::info('📦 Ditemukan di Mstockitem.BARA: ' . $barcode);

        $inSoglo = Mstocksoglo::where('BARA', $barcode)->first();
        if ($inSoglo) {
            \Log::info('✅ Ditemukan di Mstocksoglo via Mstockitem.BARA: ' . $barcode);
            return response()->json($inSoglo);
        } else {
            \Log::info('⚠️ Item ditemukan di Mstockitem.BARA tapi belum masuk SO: ' . $barcode);
            return response()->json([
                'message' => 'Item ditemukan, tetapi untuk di stock opname',
                'status' => 'not_for_so',
                'item' => $productItem
            ], 200);
        }
    }

    // Tidak ditemukan di semua tempat
    \Log::info('❌ Produk tidak ditemukan di semua tabel: ' . $barcode);
    \Log::info('=== [END SCAN BARCODE] ===');
    return response()->json(['error' => 'Produk tidak ditemukan'], 404);
}

}
