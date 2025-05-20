<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    public function index()
{
    return view('pages.auth-login');
}

    public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string',
        'password' => 'required|string',
    ]);
    
    $credentials = $request->only('name', 'password');
    // Coba login
    if (Auth::attempt($credentials)) {
        // Regenerasi session untuk keamanan
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    // Jika gagal
    return back()->withErrors([
        'name' => 'Login gagal. Periksa kembali name dan password.',
    ]);
}

public function logout(Request $request)
{
    Auth::logout();

    // Invalidate session & regenerate token untuk keamanan
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}

}
