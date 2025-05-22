<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class UserRoleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.features-profile', compact('user'));
    }
    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['nullable','max:255'],
    //         'current_password' => ['nullable'],
    //         'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
    //     ]);
    //     $user = Auth::user();
    //     // Cek apakah current_password cocok
    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return back()->withErrors(['current_password' => 'Password lama salah']);
    //     }
    //     // Update password
    //     $user->password = Hash::make($request->new_password);
    //     $user->name = $request->name;
    //     $user->save();
    //     return back()->with('status', 'Berhasil diperbarui');
    // }
   public function updatePassword(Request $request)
{
    $request->validate([
        'name' => ['required', 'max:255'],
        'new_password' => ['nullable', 'string', 'min:8'],
    ]);

    $user = Auth::user();

    // Update password hanya jika new_password ada isinya
    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->new_password);
    }

    // Update name jika diisi (harus ada karena required)
    $user->name = $request->name;

    if ($user->save()) {
        Log::info("Data berhasil diperbarui untuk user ID {$user->id}");
        return back()->with('status', 'Success');
    } else {
        Log::error("Gagal menyimpan data untuk user ID {$user->id}");
        return back()->withErrors(['update' => 'Gagal menyimpan perubahan']);
    }
}


}
