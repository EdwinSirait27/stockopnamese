<?php

namespace App\Http\Controllers;


use App\Models\Location;
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
        $users = User::with('roles','location')
            ->select(['id', 'username','location_id', 'name', 'created_at'])
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
                    ->addColumn('location_name', function ($user) {
                return !empty($user->Location) && !empty($user->Location->name)
                    ? $user->Location->name
                    : 'Empty';
            })
            ->rawColumns(['roles','location_name', 'action'])
            ->make(true);
    }
    public function edit($hashedId)
    {
        $user = User::with('roles.permissions','location')->get()->first(function ($u) use ($hashedId) {
            $expectedHash = substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8);
            return $expectedHash === $hashedId;
        });
        if (!$user) {
            abort(404, 'User not found.');
        }
        $roles = Role::pluck('name', 'name')->all();
        $locations = Location::pluck('name', 'location_id');

        // Change selectedRole to use name instead of id
        $selectedRole = old('role', optional($user->roles->first())->name ?? '');
        return view('users.edit', [
            'user' => $user,
            'hashedId' => $hashedId,
            'roles' => $roles,
            'locations' => $locations,
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
    $user = User::with('roles.permissions','location')->get()->first(function ($u) use ($hashedId) {
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
        'password' => ['nullable', 'regex:/^\S+$/', 'min:8', 'max:255'],
        'role' => ['required', 'string', 'exists:roles,name'],
       'location_id' => [
        'required',
        function ($attribute, $value, $fail) {
            $exists = DB::connection('mysql_second') // ganti dengan nama koneksi kedua kamu
                ->table('pos_location')
                ->where('location_id', $value)
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
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
         $user->location_id = $validatedData['location_id'];

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
