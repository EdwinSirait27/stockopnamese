<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Mtokosoglo;
use App\Models\Mtokodetsoglo;
use Illuminate\Support\Facades\Log;

class dashboardController extends Controller
{
    public function getMtokosoglo()
    {
        $mtokosoglos = Mtokosoglo::select(['kdtoko', 'kettoko', 'personil', 'inpmasuk',])
            ->get()
            ->map(function ($mtokosoglo) {
                $mtokosoglo->action = '
                <a href="' . route('pages.editdashboard', $mtokosoglo->kdtoko) . '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit mtokosoglo" title="Edit mtokosoglo: ' . e($mtokosoglo->kdtoko) . '">
                    <i class="fas fa-user-edit text-secondary"></i>
                </a>
                <a href="' . route('pages.showdashboard', $mtokosoglo->kdtoko) . '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit mtokosoglo" title="Show mtokosoglo: ' . e($mtokosoglo->kdtoko) . '">
                    <i class="fas fa-user-edit text-dark"></i>
                </a>
                 <button type="submit" class="btn btn-sm btn-outline-secondary mx-1" data-bs-toggle="tooltip" title="Edit mtokosoglo: {{ e($mtokosoglo->kdtoko) }}">
        Edit
    </button>
                ';
                return $mtokosoglo;
            });
        return DataTables::of($mtokosoglos)
            ->rawColumns(['action'])
            ->make(true);
    }
    public function getMtokodetsoglo()
    {
        $mtokodetsoglos = Mtokodetsoglo::select(['KDTOKO', 'BARA', 'NOURUT', 'FISIK','BARCODE','ID'])
            ->get()
            ->map(function ($mtokodetsoglo) {
                $mtokodetsoglo->action = '
               
                 <button type="submit" class="btn btn-sm btn-outline-secondary mx-1" data-bs-toggle="tooltip" title="Scan mtokodetsoglo: {{ e($mtokodetsoglo->KDTOKO) }}">
        Scan
    </button>
                ';
                return $mtokodetsoglo;
            });
        return DataTables::of($mtokodetsoglos)
            ->rawColumns(['action'])
            ->make(true);
    }
    public function edit($kdtoko)
    {
        Log::info('Masuk ke method editRole', ['kdtoko' => $kdtoko]);
        $mtokosoglo = Mtokosoglo::find($kdtoko);
        if (!$mtokosoglo) {
            Log::warning('Data tidak ditemukan di method edit', ['kdtoko' => $kdtoko]);
            abort(404, 'Data not found.');
        }
        $userName = Auth::user()->name;
        return view('pages.editdashboard', compact('mtokosoglo', 'kdtoko', 'userName'));
    }
    public function show($kdtoko)
    {
        Log::info('Masuk ke method editRole', ['kdtoko' => $kdtoko]);
        $mtokosoglo = Mtokosoglo::find($kdtoko);
        if (!$mtokosoglo) {
            Log::warning('Data tidak ditemukan di method show', ['kdtoko' => $kdtoko]);
            abort(404, 'Data not found.');
        }
        return view('pages.showdashboard', compact('mtokosoglo', 'kdtoko'));
    }
    public function update(Request $request, $kdtoko)
    {
        Log::info('Masuk ke method update', ['kdtoko' => $kdtoko]);
        // Validasi data input
        $validated = $request->validate([
            'kdtoko' => 'required|string|max:255',
            'kettoko' => 'required|string|max:255',
            'personil' => 'required|string|max:255',
            // tambahkan field lainnya sesuai kebutuhan tabel mtokosoglo
        ]);
        // Cari data berdasarkan primary key kdtoko
        $mtokosoglo = Mtokosoglo::find($kdtoko);
        if (!$mtokosoglo) {
            Log::warning('Data tidak ditemukan di method update', ['kdtoko' => $kdtoko]);
            abort(404, 'Data not found.');
        }
        $validated['inpmasuk'] = Auth::user()->name;
        // Update sekaligus
        $mtokosoglo->update($validated);
        return view('pages.dashboard', [
            'success' => 'Data berhasil diperbarui.',
        ]);
    }

}