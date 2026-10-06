<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    // View for Site Manager (Request Materials & Check Status)
    public function managerIndex()
    {
        $materials = Material::all();
        $requests = MaterialRequest::with('material')
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->get();

        return view('manager.requests', compact('materials', 'requests'));
    }

    // Site Manager creates a new request
    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity'    => 'required|integer|min:1',
            'notes'       => 'nullable|string',
        ]);

        MaterialRequest::create([
            'user_id'     => auth()->id(),
            'material_id' => $request->material_id,
            'quantity'    => $request->quantity,
            'notes'       => $request->notes,
            'status'      => 'Pending',
        ]);

        return back()->with('success', 'Material request submitted successfully to Storekeeper!');
    }

    // View for Storekeeper (Incoming Requests & Delivery Logs)
    public function storekeeperIndex()
    {
        $pendingRequests = MaterialRequest::with(['user', 'material'])
                            ->where('status', 'Pending')
                            ->latest()
                            ->get();

        // Renamed variable to $deliveryRecords to match the view's @forelse($deliveryRecords as $record)
        $deliveryRecords = MaterialRequest::with(['user', 'material'])
                            ->whereIn('status', ['Approved', 'Delivered'])
                            ->latest()
                            ->get();

        return view('storekeeper.requests', compact('pendingRequests', 'deliveryRecords'));
    }

    // Storekeeper Updates Status (Approve & Send / Reject) + Log to Audit Trail
    public function updateStatus(Request $request, MaterialRequest $materialRequest)
    {
        $request->validate([
            'status' => 'required|in:Approved,Delivered,Rejected',
        ]);

        $previousStatus = $materialRequest->status;

        // If request is newly approved from 'Pending' status
        if (($request->status === 'Approved' || $request->status === 'Delivered') && $previousStatus === 'Pending') {
            $material = $materialRequest->material;
            
            // Check available stock
            if ($material->quantity_in_stock < $materialRequest->quantity) {
                return back()->with('error', 'Insufficient material stock for this delivery!');
            }

            // Deduct stock quantity
            $material->decrement('quantity_in_stock', $materialRequest->quantity);

            // Record outgoing stock activity in Audit Trail
            AuditTrail::create([
                'user_id'      => auth()->id(),
                'material_id'  => $material->id,
                'action'       => 'Material Sent to Site (' . $request->status . ')',
                'stock_change' => '-' . $materialRequest->quantity,
                'remarks'      => 'Sent to site for Request ID #' . $materialRequest->id . ' (Requested by: ' . ($materialRequest->user->name ?? 'User') . ')',
            ]);
        } 
        // If status is changed from Approved to Delivered (update status record only)
        elseif ($request->status === 'Delivered' && $previousStatus === 'Approved') {
            AuditTrail::create([
                'user_id'      => auth()->id(),
                'material_id'  => $materialRequest->material_id,
                'action'       => 'Delivery Marked as Delivered',
                'stock_change' => '0',
                'remarks'      => 'Delivery status completed for Request ID #' . $materialRequest->id,
            ]);
        }

        // Update request status
        $materialRequest->update(['status' => $request->status]);

        return back()->with('success', 'Request status updated and recorded in Audit Trail!');
    }
}