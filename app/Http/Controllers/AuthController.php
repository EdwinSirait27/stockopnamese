<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
     public function username()
    {
        return 'username'; 
    }
    public function register(Request $request) {
        $request->validate([
    'username' => 'required|string|unique:users,username',
    'password' => 'required|string|min:8',
]);

        $user = User::create([
            'username' => $request->username ?? '',
            'password' => Hash::make($request->password),
        ]);
        return response()->json($user);
    }

//  public function login(Request $request)
// {
//     $credentials = $request->only($this->username(), 'password');

//     $request->validate([
//         $this->username() => 'required|string',
//         'password' => 'required|string',
//     ]);
//     try {
//         if (! $token = JWTAuth::attempt($credentials)) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid credentials'
//             ], 401);
//         }
        

//         try {
//             $user = Auth::user();
//         } catch (\Exception $e) {
//             Log::error('Error ambil user: '.$e->getMessage());
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Token created but failed to retrieve user.',
//                 'token' => $token,
//             ], 500);
//         }
//         return response()->json([
//             'success' => true,
//             'message' => 'Login berhasil',
//             'user' => $user,
//             'token' => $token,
//         ]);
//     } catch (JWTException $e) {
//         Log::error('JWT Error: '.$e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Could not create token',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }
public function login(Request $request)
{
    $credentials = $request->only($this->username(), 'password');

    $request->validate([
        $this->username() => 'required|string',
        'password' => 'required|string',
    ]);

    try {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        // Cek role hanya Bos atau Admin yang boleh login
        // if (! $user->hasRole('Bos')) {
        if (! $user->hasAnyRole(['Bos', 'Admin'])) {
            // Logout paksa, invalidate token
            JWTAuth::invalidate($token);

            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk login.'
            ], 403);
        }
        // Ambil role & permission kalau perlu
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'token' => $token,
        ]);

    } catch (JWTException $e) {
        Log::error('JWT Error: '.$e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Could not create token',
            'error' => $e->getMessage()
        ], 500);
    }
}

  public function profile()
{
    try {
        // $user = Auth::user(); // Ambil user dari token JWT
        $user = Auth::user()->load('roles');
        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }
}


   public function logout(Request $request)
{
    try {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    } catch (JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal logout, token tidak valid atau sudah kadaluarsa'
        ], 500);
    }
}
}
