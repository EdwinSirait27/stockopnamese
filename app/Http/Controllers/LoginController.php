<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class LoginController extends Controller
{
    public function index()
    {
        return view('pages.auth-login');
    }
    public function login(Request $request)
    {
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
            'name' => 'Login Failed. Double check your name and password.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}