<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meja;
use App\Models\Lantai;
use App\Models\StatusBooking;
use RealRashid\SweetAlert\Facades\Alert;

class MejaController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Meja | ',
            'datameja' => Meja::with('user', 'lantai', 'status')
                                ->get()
                                ->groupBy('lantai_id'),
        ];
        // dd($data['datameja']);
        return view('backend.meja.index', $data);
    }

    public function create()
    {
        $lantai = Lantai::all();
        $status = StatusBooking::all();
        $data = [
            'title' => 'Add Meja | ',
            'datalantai' => $lantai,
            'datastatus' => $status,
        ];
        return view('backend.meja.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_meja' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'lantai_id' => 'required|exists:lantai,id',
            'status_id' => 'required|exists:status_booking,id',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);
        dd($request->all());
        // Meja::create($request->all());
        // Alert::success('Success', 'meja created successfully.')->autoClose(2000);
        // return redirect()->route('meja.index');
    }

    public function show(Meja $meja)
    {
        $data = [
            'title' => 'View Meja | ',
            'meja' => $meja,
        ];
        return view('backend.meja.show', $data);
    }

    public function edit(Meja $meja)
    {
        $lantai = Lantai::all();
        $status = StatusBooking::all();
        $data = [
            'title' => 'Edit Meja | ',
            'datalantai' => $lantai,
            'datastatus' => $status,
            'meja' => $meja,
        ];
        return view('backend.meja.edit', $data);
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            'nama_meja' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'lantai_id' => 'required|exists:lantai,id',
            'status_id' => 'required|exists:status_booking,id',
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

    public function checkNamaMeja(Request $request)
    {
        $exists = Meja::where('lantai_id', $request->lantai_id)
                    ->where('nama_meja', $request->nama_meja)
                    ->exists();

        return response()->json(!$exists);  // Return true if not exists (valid), false if it exists
    }
}
