<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
class RegisterController extends Controller
{
    public function index()
    {
        return view('pages.auth-register');
    }
    // public function register(Request $request)
    // {
    //     // Validasi input register
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'password' => ['required', 'confirmed', Password::defaults()], // Password harus dikonfirmasi
    //     ]);
    //     // Buat user baru dengan password yang di-hash
    //     $user = User::create([
    //         'name' => $request->name,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     // Login otomatis user yang baru dibuat
    //     Auth::login($user);
    //     // Regenerasi session untuk keamanan
    //     $request->session()->regenerate();
    //     // Redirect ke halaman dashboard
    //     return redirect()->intended('/features-profile');
    // }
    public function register(Request $request)
    {
        Log::info('Register method called');
        Log::info('Validating request', $request->all());
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s]+$/', // hanya huruf dan spasi
                Rule::unique('users', 'name')->where(function ($query) use ($request) {
                    return $query->whereRaw('LOWER(name) = ?', [strtolower($request->input('name'))]);
                }),
            ],
            'username' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^\d+$/',
                Rule::unique('users', 'username'),
            ],
            'password' => ['required','regex:/^\S+$/','min:8', 'max:255'],
        ]);
        Log::info('Validation passed');
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        Log::info('User created', ['user_id' => $user->id]);
        // Login otomatis user yang baru dibuat
        Auth::login($user);
        Log::info('User logged in', ['user_id' => $user->id]);
        // Regenerasi session untuk keamanan
        $request->session()->regenerate();
        Log::info('Session regenerated');
        // Redirect ke halaman dashboard
        Log::info('Redirecting to /features-profile');
        return redirect()->intended('/features-profile')->with('success', 'User Created Successfully!');
    }
}
