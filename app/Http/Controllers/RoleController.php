<?php
namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    //  public function index() {
    //     $roles = Role::all();
    //     return view('roles.index', compact('roles'));
    // }
    // public function create() {
    //     $permissions = Permission::all();
    //     return view('roles.create', compact('permissions'));
    // }
    // public function store(Request $request) {
    //     $role = Role::create(['name' => $request->name]);
    //     $role->syncPermissions($request->permissions);
    //     return redirect()->route('roles.index');
    // }
     public function index() {
        
        return view('roles.index');
    }
 public function getRoles()
    {
        // dd(Role::with('permissions')->get()->toArray());
        $roles = Role::with('permissions')
            ->select(['id', 'name'])
            ->get()
            ->map(function ($role) {
                $role->id_hashed = substr(hash('sha256', $role->id . env('APP_KEY')), 0, 8);
                $role->action = '
                <a href="' . route('roles.edit', $role->id_hashed) . '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit role" title="Edit Role: ' . e($role->name) . '">
                    <i class="fas fa-user-edit text-secondary"></i>
                </a>
            ';
                return $role;
            });
        return DataTables::of($roles)
            ->addIndexColumn()
           
            ->addColumn('permissions', function ($role) {
                return $role->permissions->count()
                    ? $role->permissions->pluck('name')->implode(', ')
                    : 'Empty';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
    public function create() {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    // public function edit($id) {
    //     $role = Role::findOrFail($id);
    //     $permissions = Permission::all();
    //     $rolePermissions = $role->permissions->pluck('name')->toArray();

    //     return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    // }
    public function edit($id)
    {
        
        $role = Role::with('permissions')->get()->first(function ($role) use ($id) {
            $expectedHash = substr(hash('sha256', $role->id . env('APP_KEY')), 0, 8);
            return $expectedHash === $id;
        });
    
        if (!$role) {
            abort(404, 'Role not found.');
        }
    
        
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
    
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions', 'id'));
    }
    // public function update(Request $request, $id) {
    //     $request->validate([
    //         'name' => 'required|unique:roles,name,' . $id,
    //         'permissions' => 'array|required',
    //     ]);

    //     $role = Role::findOrFail($id);
    //     $role->name = $request->name;
    //     $role->save();

    //     $role->syncPermissions($request->permissions);

    //     return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    // }
    public function update(Request $request, $id)
{

    // Cari role dengan matching hash (seperti di method edit)
    $role = Role::all()->first(function ($role) use ($id) {
        $expectedHash = substr(hash('sha256', $role->id . env('APP_KEY')), 0, 8);
        return $expectedHash === $id;
    });

    if (!$role) {
        return redirect()->route('roles.index')->with('error', 'Role tidak ditemukan.');
    }

    try {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => [
                'required',
                'exists:permissions,id',
                'distinct'
            ]
        ], [
            'permissions.*.exists' => 'Permission yang dipilih tidak ditemukan.',
            'permissions.*.distinct' => 'Permission duplikat tidak diperbolehkan.'
        ]);


        // Update nama role
        $role->update(['name' => $validatedData['name']]);

        // Ambil permission berdasarkan ID UUID
        $permissions = Permission::whereIn('id', $validatedData['permissions'])->get();

        $before = $role->permissions->pluck('id')->toArray();

        $role->syncPermissions($permissions);

        $after = $role->permissions()->pluck('id')->toArray();

        // Bersihkan cache permission (Spatie)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Role berhasil diupdate.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate role.')->withInput();
    }
}
}
