<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lantai;
use RealRashid\SweetAlert\Facades\Alert;

class LantaiController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Lantai | ',
            'datalantai' => Lantai::all(),
        ];
        return view('backend.lantai.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add Lantai | ',
        ];
        return view('backend.lantai.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lantai' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);

        Lantai::create($request->all());
        Alert::success('Success', 'lantai created successfully.')->autoClose(2000);
        return redirect()->route('lantai.index');
    }

    public function show(Lantai $lantai)
    {
        $data = [
            'title' => 'View Lantai | ',
            'lantai' => $lantai,
        ];
        return view('backend.lantai.show', $data);
    }

    public function edit(Lantai $lantai)
    {
        $data = [
            'title' => 'Edit Lantai | ',
            'lantai' => $lantai,
        ];
        return view('backend.lantai.edit', $data);
    }

    public function update(Request $request, Lantai $lantai)
    {
        $request->validate([
            'nama_lantai' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $lantai->update($request->all());
        Alert::success('Success', 'lantai updated successfully.')->autoClose(2000);

        return redirect()->route('lantai.index');
    }

    public function destroy(Lantai $lantai)
    {
        $lantai->delete();
        return response()->json(['success' => 'lantai deleted successfully.']);
        // Alert::success('Success', 'lantai deleted successfully.');

        // return redirect()->route('lantai.index');
    }
}
