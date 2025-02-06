<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StatusBooking;
use RealRashid\SweetAlert\Facades\Alert;

class StatusBookingController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Status Booking | ',
            'datastatusbooking' => StatusBooking::all(),
        ];
        return view('backend.statusbooking.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add Status Booking | ',
        ];
        return view('backend.statusbooking.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id', // Adjust as necessary
        ]);

        StatusBooking::create($request->all());
        Alert::success('Success', 'statusbooking created successfully.')->autoClose(2000);
        return redirect()->route('statusbooking.index');
    }

    public function show(StatusBooking $statusbooking)
    {
        $data = [
            'title' => 'View Status Booking | ',
            'statusbooking' => $statusbooking,
        ];
        return view('backend.statusbooking.show', $data);
    }

    public function edit(StatusBooking $statusbooking)
    {
        $data = [
            'title' => 'Edit Status Booking | ',
            'statusbooking' => $statusbooking,
        ];
        return view('backend.statusbooking.edit', $data);
    }

    public function update(Request $request, StatusBooking $statusbooking)
    {
        $request->validate([
            'nama_status' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $statusbooking->update($request->all());
        Alert::success('Success', 'statusbooking updated successfully.')->autoClose(2000);

        return redirect()->route('statusbooking.index');
    }

    public function destroy(StatusBooking $statusbooking)
    {
        $statusbooking->delete();
        return response()->json(['success' => 'statusbooking deleted successfully.']);
        // Alert::success('Success', 'statusbooking deleted successfully.');

        // return redirect()->route('statusbooking.index');
    }
}
