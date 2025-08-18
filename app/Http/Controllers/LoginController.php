<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Buttons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{
    // public function index()
    // {
    //     // return view('pages.auth-login');
    //       $buttons = Buttons::where('url', '/')->first();
    //     if (!$buttons) {
    //        return view('pages.error');
    //     }
    //     $start_date = Carbon::parse($buttons->start_date);
    //     $end_date = Carbon::parse($buttons->end_date);
    //     if (Carbon::now()->between($start_date, $end_date)) {
    //         return view('pages.auth-login');
    //     }
    //   return view('pages.error');
    // }
    public function index()
{
    $buttons = Buttons::where('url', '/')->first();

    if (!$buttons || !$buttons->start_date || !$buttons->end_date) {
        return view('pages.error');
    }

    $start_date = Carbon::parse($buttons->start_date);
    $end_date = Carbon::parse($buttons->end_date);

    if (Carbon::now()->between($start_date, $end_date)) {
        return view('pages.auth-login');
    }

    return view('pages.error');
}

//     public function login(Request $request)
// {
//     $request->validate([
//         'username' => 'required|regex:/^[0-9]+$/|exists:users,username',
//         'password' => ['required', 'regex:/^\S+$/', 'max:255'],
//     ]);

//     $credentials = $request->only('username', 'password');

//     // Coba login
//     if (Auth::attempt($credentials)) {
//         $request->session()->regenerate();
//         Log::info("Login berhasil untuk username: {$request->username}, IP: {$request->ip()}");
//         // return redirect()->intended('/dashboard');
//         return redirect()->intended('/dashboard')->with('success', 'Login Success!');
//     }

//     // Jika gagal
//     Log::warning("Login gagal untuk username: {$request->username}, IP: {$request->ip()}");

//     return back()->withErrors([
//         'username' => 'Login gagal. Periksa kembali username dan password.',
//     ]);
// }
// public function login(Request $request)
// {
//     $request->validate([
//         'username' => 'required|regex:/^[0-9]+$/|exists:users,username',
//         'password' => ['required', 'regex:/^\S+$/', 'max:255'],
//     ]);

//     $credentials = $request->only('username', 'password');

//     if (Auth::attempt($credentials)) {
//         $request->session()->regenerate();
//         $user = Auth::user();
//         Log::info("Login berhasil untuk username: {$request->username}, IP: {$request->ip()}");

//         // Arahkan berdasarkan role
//         if ($user->hasRole('Bos')) {
//             return redirect()->intended('/dashboard')->with('success', 'Login sebagai Bos');
//         } elseif ($user->hasRole('Admin')) {
//             return redirect()->intended('/dashboardadmin')->with('success', 'Login sebagai Admin');
//         }
//         elseif ($user->hasRole('Penginput')) {
//             // return redirect()->intended('/dashboardpenginput')->with('success', 'Login sebagai Penginput');
//             return redirect()->route('dashboardpenginput', ['opname_id' => $someOpnameId])->with('success', 'Login sebagai Penginput');

//         } 
//         else {
//             Auth::logout();
//             return redirect('/')->withErrors([
//                 'username' => 'Role tidak diizinkan mengakses sistem ini.',
//             ]);
//         }
//     }

//     Log::warning("Login gagal untuk username: {$request->username}, IP: {$request->ip()}");

//     return back()->withErrors([
//         'username' => 'Login gagal. Periksa kembali username dan password.',
//     ]);
// }
public function login(Request $request)
{
    $request->validate([
        'username' => 'required|exists:users,username',
        'password' => ['required', 'regex:/^\S+$/', 'max:255'],
    ]);

    $credentials = $request->only('username', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        Log::info("Login berhasil untuk username: {$request->username}, IP: {$request->ip()}");

        if ($user->hasRole('Bos')) {
            return redirect()->intended('/dashboard')->with('success', 'Login sebagai Bos');
        } elseif ($user->hasRole('Admin')) {
            return redirect()->intended('/dashboardadmin')->with('success', 'Login sebagai Admin');
        } elseif ($user->hasRole('Penginput')) {
            $locationId = $user->location_id;

            if (!$locationId) {
                return redirect('/')->withErrors(['username' => 'Location ID tidak ditemukan.']);
            }

            return redirect()->route('dashboardpenginput', ['opname_id' => $locationId])->with('success', 'Login sebagai Penginput');
        } else {
            Auth::logout();
            return redirect('/')->withErrors([
                'username' => 'Role tidak diizinkan mengakses sistem ini.',
            ]);
        }
    }

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