<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Role | ',
            'datarole' => Role::all(),
        ];
        return view('backend.role.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add Role | ',
        ];
        return view('backend.role.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255',
            'kode_role' => 'required|string|max:255|regex:/^[a-z0-9]+$/',
        ]);

        role::create($request->all());
        Alert::success('Success', 'role created successfully.')->autoClose(2000);
        return redirect()->route('role.index');
    }

    public function show(Role $role)
    {
        $data = [
            'title' => 'View Role | ',
            'role' => $role,
        ];
        return view('backend.role.show', $data);
    }

    public function edit(Role $role)
    {
        $data = [
            'title' => 'Edit Role | ',
            'role' => $role,
        ];
        return view('backend.role.edit', $data);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255',
            'kode_role' => 'required|string|max:255|regex:/^[a-z0-9]+$/',
        ]);

        $role->update($request->all());
        Alert::success('Success', 'role updated successfully.')->autoClose(2000);

        return redirect()->route('role.index');
    }

    public function destroy(role $role)
    {
        $role->delete();
        return response()->json(['success' => 'role deleted successfully.']);
        // Alert::success('Success', 'role deleted successfully.');

        // return redirect()->route('role.index');
    }
}
