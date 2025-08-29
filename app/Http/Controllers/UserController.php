<?php

namespace App\Http\Controllers;


use App\Models\Location;
use App\Models\Locationse001;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }
    public function getUsers()
    {
        $users = User::with('roles','location','locationse')
            ->select(['id', 'username','BO', 'name', 'created_at'])
            // ->select(['id', 'username','location_id', 'name', 'created_at'])->byLocation()
            ->get()
            ->map(function ($user) {
                $user->id_hashed = substr(hash('sha256', $user->id . env('APP_KEY')), 0, 8);
                $user->action = '
               
                    <a href="' . route('users.edit', $user->id_hashed) . '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit user"title="Edit User: ' . e($user->username) . '">
                        <i class="fas fa-user-edit text-secondary"></i>
                    </a>';
                return $user;
            });


        return DataTables::of($users)
            ->addColumn('roles', function ($user) {
                if (is_array($user->roles)) {
                    return implode(', ', $user->roles);
                } elseif ($user->roles instanceof \Illuminate\Support\Collection) {
                    return $user->roles->pluck('name')->implode(', ');
                } else {
                    return 'Empty';
                }
            })
                    ->addColumn('BO', function ($user) {
                return !empty($user) && !empty($user->BO)
                    ? $user->BO
                    : 'Empty';
            })
            ->rawColumns(['roles','BO', 'action'])
            ->make(true);
    }
    public function edit($hashedId)
    {
        $user = User::with('roles.permissions','location','locationse')->get()->first(function ($u) use ($hashedId) {
            $expectedHash = substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8);
            return $expectedHash === $hashedId;
        });
        if (!$user) {
            abort(404, 'User not found.');
        }
        $roles = Role::pluck('name', 'name')->all();
        $locations = Location::pluck('name', 'location_id');
        $locationse = Locationse001::pluck('BO', 'BO');

        // Change selectedRole to use name instead of id
        $selectedRole = old('role', optional($user->roles->first())->name ?? '');
        return view('users.edit', [
            'user' => $user,
            'hashedId' => $hashedId,
            'roles' => $roles,
            'locations' => $locations,
            'locationse' => $locationse,
            'selectedRole' => $selectedRole
        ]);
    }
    // public function update(Request $request, $hashedId)
    // {

    //     $user = User::with('roles.permissions')->get()->first(function ($u) use ($hashedId) {
    //         $expectedHash = substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8);
    //         return $expectedHash === $hashedId;
    //     });

    //     if (!$user) {
    //         Log::warning('User tidak ditemukan dengan hashedId', ['hashedId' => $hashedId]);
    //         return redirect()->route('users.index')->with('error', 'ID tidak valid.');
    //     }

    //     Log::info('User ditemukan', ['user_id' => $user->id]);

    //     $validatedData = $request->validate([

    //         'password' => ['nullable', 'required', 'regex:/^\S+$/', 'min:8', 'max:255'],

    //         'role' => ['required', 'string', 'exists:roles,name'],
    //         'permissions' => ['nullable'],
    //     ]);


    //     // Update password hanya jika new_password ada isinya
    // if ($request->filled('password')) {
    //     $user->password = Hash::make($request->password);
    // }

    //     DB::beginTransaction();
    //     try {
    //         $user->update($userData);
    //         $role = Role::findByName($validatedData['role']);
    //         $user->syncRoles($role);

    //         DB::commit();
    //         return redirect()->route('users.index')->with('success', 'User Berhasil Diupdate.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Gagal update user', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return redirect()->route('users.index')->with('error', 'Terjadi kesalahan saat mengupdate user.');
    //     }
    // }
    public function update(Request $request, $hashedId)
{
    // Cari user berdasarkan hashed ID
    $user = User::with('roles.permissions','location','locationse')->get()->first(function ($u) use ($hashedId) {
        $expectedHash = substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8);
        return $expectedHash === $hashedId;
    });

    if (!$user) {
        Log::warning('User tidak ditemukan dengan hashedId', ['hashedId' => $hashedId]);
        return redirect()->route('users.index')->with('error', 'ID tidak valid.');
    }

    Log::info('User ditemukan', ['user_id' => $user->id]);

    // Validasi input
    $validatedData = $request->validate([
    //     'username' => ['required','min:3', 'max:255',Rule::unique('users')->ignore($user->id), // ignore id user yang sedang diupdate
    // ],
    //     'password' => ['nullable', 'regex:/^\S+$/', 'min:3', 'max:255'],
    //     'name' => ['required', 'min:3', 'max:255'],
        'role' => ['required', 'string', 'exists:roles,name'],
       'BO' => [
        'required',
        function ($attribute, $value, $fail) {
            $exists = DB::connection('mysql_third') // ganti dengan nama koneksi kedua kamu
                ->table('bo')
                ->where('BO', $value)
                ->exists();

            if (! $exists) {
                $fail('The selected location is invalid.');
            }
        },
    ],
        'permissions' => ['nullable', 'array'], // validasi jika array
    ]);

    DB::beginTransaction();

    try {
        // Update password jika diisi
        // if ($request->filled('password')) {
        //     $user->password = Hash::make($request->password);
        // }
        //  $user->username = $validatedData['username'];
         $user->BO = $validatedData['BO'];
        //  $user->name = $validatedData['name'];

        // Update role
        $role = Role::findByName($validatedData['role']);
        $user->syncRoles($role);

        // Simpan perubahan user
        $user->save();

        DB::commit();
        return redirect()->route('users.index')->with('success', 'User Berhasil Diupdate.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal update user', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->route('users.index')->with('error', 'Terjadi kesalahan saat mengupdate user.');
    }
}


}
