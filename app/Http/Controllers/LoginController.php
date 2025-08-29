<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Buttons;
use App\Models\User;
use App\Models\Sysuser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class LoginController extends Controller
{
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
public function login(Request $request)
{
    $request->validate([
        'login_id' => 'required|exists:mysql_second.sysuser,login_id',
        'password' => ['required', 'max:255'],
    ]);
    $sysuser = SysUser::where('login_id', $request->login_id)->first();
    if (!$sysuser) {
        Log::warning("Login gagal (user tidak ada di sysuser): {$request->login_id}, IP: {$request->ip()}");
        return back()->withErrors([
            'login_id' => 'User tidak ditemukan di sysuser.',
        ]);
    }
   if ($request->password !== $sysuser->password) {
    Log::warning("Login gagal (password salah): {$request->login_id}, IP: {$request->ip()}");
    return back()->withErrors([
        'password' => 'Password salah.',
    ]);
}
    $user = User::updateOrCreate(
        ['login_id' => $sysuser->login_id],
        [
            'name'  => $sysuser->full_name,
            'username'  => $sysuser->login_id,
            'password' => bcrypt($sysuser->password),
        ]
    );
    Auth::login($user);
    $request->session()->regenerate();

    Log::info("Login berhasil untuk login_id: {$request->login_id}, IP: {$request->ip()}");

    // 5. Cek role
    if ($user->hasRole('Bos')) {
        return redirect()->intended('/dashboard')->with('success', 'Login sebagai Bos');
    } elseif ($user->hasRole('Admin')) {
        return redirect()->intended('/dashboardadmin')->with('success', 'Login sebagai Admin');
    } else {
        Auth::logout();
        return redirect('/')->withErrors([
            'login_id' => 'Role tidak diizinkan mengakses sistem ini.',
        ]);
    }
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}