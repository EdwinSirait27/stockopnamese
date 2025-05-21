<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserRoleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.features-profile', compact('user'));
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = Auth::user();
        // Cek apakah current_password cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with('status', 'Password berhasil diperbarui');
    }
}
