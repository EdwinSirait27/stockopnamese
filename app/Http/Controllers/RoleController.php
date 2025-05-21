<?php
namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
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
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create() {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array|required',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'array|required',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
}
