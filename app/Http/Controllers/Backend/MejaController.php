<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\Lantai;
use App\Models\Status;
use RealRashid\SweetAlert\Facades\Alert;

class MejaController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'meja | ',
            'datameja' => Meja::all(),
        ];
        return view('backend.meja.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add meja | ',
        ];
        return view('backend.meja.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_meja' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);

        Meja::create($request->all());
        Alert::success('Success', 'meja created successfully.')->autoClose(2000);
        return redirect()->route('meja.index');
    }

    public function show(Meja $meja)
    {
        $data = [
            'title' => 'View meja | ',
            'meja' => $meja,
        ];
        return view('backend.meja.show', $data);
    }

    public function edit(Meja $meja)
    {
        $data = [
            'title' => 'Edit meja | ',
            'meja' => $meja,
        ];
        return view('backend.meja.edit', $data);
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            'nama_meja' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $meja->update($request->all());
        Alert::success('Success', 'meja updated successfully.')->autoClose(2000);

        return redirect()->route('meja.index');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();
        return response()->json(['success' => 'meja deleted successfully.']);
        // Alert::success('Success', 'meja deleted successfully.');

        // return redirect()->route('meja.index');
    }
}
