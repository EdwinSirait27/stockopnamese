<?php

namespace App\Http\Controllers;
use App\Models\Buttons;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

use Spatie\Permission\PermissionRegistrar;

class ButtonsController extends Controller
{
    public function index()
    {
        return view('buttons.index');
    }
    public function getButtons()
    {
        // dd(Role::with('permissions')->get()->toArray());
        $buttons = Buttons::select(['id', 'url','start_date','end_date','ket'])
            ->get()
            ->map(function ($button) {
                $button->id_hashed = substr(hash('sha256', $button->id . env('APP_KEY')), 0, 8);
                $button->action = '
                <a href="' . route('buttons.edit', $button->id_hashed) . '" class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit button" title="Edit Button: ' . e($button->url) . '">
                    <i class="fas fa-user-edit text-secondary"></i>
                </a>
            ';
                return $button;
            });
        return DataTables::of($buttons)
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
 
    public function edit($id)
    {

        $button = Buttons::get()->first(function ($button) use ($id) {
            $expectedHash = substr(hash('sha256', $button->id . env('APP_KEY')), 0, 8);
            return $expectedHash === $id;
        });

        if (!$button) {
            abort(404, 'Button not found.');
        }

        return view('buttons.edit', compact('button','id'));
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
        $validatedData = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date','after:start_date'],
            'ket' => ['nullable', 'max:50'],

        ]);
        $button = Buttons::get()->first(function ($u) use ($id) {
            $expectedHash = substr(hash('sha256', $u->id . env('APP_KEY')), 0, 8);
            return $expectedHash === $id;
        });
        if (!$button) {
            return redirect()->route('buttons.index')->with('error', 'ID tidak valid.');
        }
        $buttonData = [
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'ket' => $validatedData['ket'],
            
        ];
        $button->update($buttonData);
        return redirect()->route('buttons.index')->with('success', 'Button Berhasil Diupdate.');
    }
}
