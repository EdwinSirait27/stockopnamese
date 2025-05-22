<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    public function index()
    {
        return view('pages.auth-login');
    }
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required|regex:/^[0-9]+$/|exists:users,username',
    //         'password' => ['required','regex:/^\S+$/','max:255'],
    //     ]);
    //     $credentials = $request->only('username', 'password');
    //     // Coba login
    //     if (Auth::attempt($credentials)) {
    //         // Regenerasi session untuk keamanan
    //         $request->session()->regenerate();
    //         return redirect()->intended('/dashboard');
    //     }
    //     // Jika gagal
    //     return back()->withErrors([
    //         // 'name' => 'Login Failed. Double check your name and password.',
    //     ]);
    // }
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|regex:/^[0-9]+$/|exists:users,username',
        'password' => ['required', 'regex:/^\S+$/', 'max:255'],
    ]);

    $credentials = $request->only('username', 'password');

    // Coba login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        Log::info("Login berhasil untuk username: {$request->username}, IP: {$request->ip()}");
        return redirect()->intended('/dashboard');
    }

    // Jika gagal
    Log::warning("Login gagal untuk username: {$request->username}, IP: {$request->ip()}");

    return back()->withErrors([
        'username' => 'Login gagal. Periksa kembali username dan password.',
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