<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Lantai;
use App\Models\Meja;
use App\Models\TransaksiHeader;
use App\Models\TransaksiDetail;
use App\Models\Privilage;
use RealRashid\SweetAlert\Facades\Alert;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $role = Privilage::getRoleKodeForAuthenticatedUser();

        // Fetch the filters from the request
        $checkin = $request->input('checkin');
        $status = $request->input('status_transaksi');
        $customerName = $request->input('customer_name');

        // Query for transactions
        $query = TransaksiHeader::with(['detail', 'detail.lantai', 'detail.meja', 'user']);

        if ($role == 'customer') {
            $query->where('user_id', auth()->id());
        }

        // Apply filters if they exist
        if (!empty($checkin)) {
            $query->whereDate('checkin', $checkin);
        }

        if (!empty($status)) {
            $query->where('status_transaksi', $status);
        }

        if (!empty($customerName)) {
            $query->whereHas('user', function ($q) use ($customerName) {
                $q->where('name', 'like', '%' . $customerName . '%');
            });
        }

        $trans = $query->orderBy('created_at', 'desc')->get();

        $data = [
            'title' => 'Histori Transaksi | ',
            'datatransaksi' => $trans,
            'filters' => compact('checkin', 'status', 'customerName')
        ];

        return view('backend.transaksi.index', $data);
    }


    public function approval()
    {
        $trans = TransaksiHeader::with(['detail', 'detail.lantai', 'detail.meja', 'user'])
                                ->where('status_transaksi', 1)
                                ->where(function ($query) {
                                    $query->whereNull('approveby') // For NULL values
                                          ->orWhere('approveby', ''); // For empty string values
                                })
                                ->orderBy('created_at', 'desc')
                                ->get();
        $data = [
            'title' => 'Approval | ',
            'datatransaksi' => $trans,
        ];
        // dd($data['datatransaksi']);
        return view('backend.transaksi.approval', $data);
    }

    private function generateInvoiceNumber()
    {
        $year = date('y'); // Last two digits of the year (e.g., 25 for 2025)
        $monthLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L']; 
        $month = $monthLetters[date('n') - 1]; // Convert numeric month to letter (1 = A, 2 = B, ..., 12 = L)
        $day = date('d'); // Day of the month (e.g., 16)
        
        $datePrefix = "{$year}{$month}{$day}"; // Construct the date prefix (e.g., 25B16)

        // Find the latest invoice for this user and today's date
        $latestInvoice = TransaksiHeader::whereDate('created_at', today()) // Filter by today's date
                        ->latest('id') // Order by newest entry (assuming `id` is auto-incrementing)
                        ->first();

        if ($latestInvoice) {
            $lastSequence = (int) substr($latestInvoice->invoice_number, -3); // Get last 3 digits
            $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT); // Increment
        } else {
            $newSequence = '001'; // Start from 001 if no invoice exists for today
        }

        return "{$datePrefix}{$newSequence}"; // Example: 25B16001
    }

    public function create()
    {
        $invoiceNumber = $this->generateInvoiceNumber();
        $lantais = Lantai::all(); // Get all barang for selection
        $mejas = Meja::where('status_id' , 1)->get();
        $data = [
            'title' => 'Booking | ',
            'lantais' => $lantais,
            'mejas' => $mejas,
            'invoiceNumber' => $invoiceNumber,
        ];
        return view('backend.transaksi.create', $data);
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'invoice_number' => 'required|unique:transaksi_header,invoice_number',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
            'items' => 'required|array|min:1',
            'items.*.lantai_id' => 'required|exists:lantai,id',
            'items.*.meja_id' => 'required|exists:meja,id',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $checkin = Carbon::createFromFormat('m/d/Y H:i', $request->checkin)->format('Y-m-d H:i:s');
        $checkout = Carbon::createFromFormat('m/d/Y H:i', $request->checkout)->format('Y-m-d H:i:s');

        // Prepare the transaction data
        $transaksiData = [
            'user_id' => auth()->id(),
            'invoice_number' => $this->generateInvoiceNumber(),
            'checkin' => $checkin,
            'checkout' => $checkout,
            'status_transaksi' => 1,
        ];

        // Prepare the items data
        $itemsData = [];
        foreach ($request->items as $item) {
            $itemsData[] = [
                'lantai_id' => $item['lantai_id'],
                'meja_id' => $item['meja_id'],
                'harga' => $item['harga'],
            ];
        }

        // Debugging: Dump data and stop execution before inserting into the database
        // dd([
        //     'transaksi' => $transaksiData,
        //     'items' => $itemsData,
        // ]);

        // If dd() is removed, insert data into the database
        $transaksi = TransaksiHeader::create($transaksiData);

        // Insert items
        foreach ($request->items as $item) {
            TransaksiDetail::create([
                'transaksi_header_id' => $transaksi->id,
                'lantai_id' => $item['lantai_id'],
                'meja_id' => $item['meja_id'],
                'harga' => $item['harga'],
            ]);

            Meja::where('id', $item['meja_id'])->update(['status_id' => 2]);
        }

        Alert::success('Success', 'Transaksi created successfully.')->autoClose(2000);
        return redirect()->route('transaksi.index');
    }

    public function destroy(TransaksiHeader $transaksi)
    {
        // Update transaksi_header status
        $transaksi->update(['status_transaksi' => 3]);

        // Get all related transaksi_detail records
        $details = $transaksi->detail;

        // Update status_id in meja table
        foreach ($details as $detail) {
            if ($detail->meja) {
                $detail->meja->update(['status_id' => 1]); // Change 0 to the desired status
            }
        }

        return response()->json(['success' => true]);
    }

    public function approve(TransaksiHeader $transaksi, Request $request)
    {
        // Update transaksi_header status
        $transaksi->update([
            'approveby' => auth()->id(),
            'approve_time' => date('Y-m-d H:i:s'),
            'dp' => $request->dp,
        ]);

        return response()->json(['success' => true]);
    }

    public function finish(TransaksiHeader $transaksi)
    {
        // Update transaksi_header status
        $transaksi->update([
            'status_transaksi' => 2,
            'finish_time' => date('Y-m-d H:i:s'),
        ]);
        
        $details = $transaksi->detail;

        // Update status_id in meja table
        foreach ($details as $detail) {
            if ($detail->meja) {
                $detail->meja->update(['status_id' => 1]); // Change 0 to the desired status
            }
        }

        return response()->json(['success' => true]);
    }

    public function getMejaByLantai(Request $request)
    {
        $mejas = Meja::where('lantai_id', $request->lantai_id)
                    ->where('status_id', 1)
                    ->get();
        return response()->json($mejas);
    }

}
