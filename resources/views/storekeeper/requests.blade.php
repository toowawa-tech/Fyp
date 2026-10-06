@extends('layouts.app')

@section('breadcrumb', 'Storekeeper / Requests')
@section('title', 'Material Request & Delivery Management')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Incoming Pending Requests Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-section-header">
        📍 INCOMING REQUESTS FROM SITE MANAGER
    </div>
    <div class="card-body-custom p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Requester</th>
                        <th>Material</th>
                        <th>Quantity Requested</th>
                        <th>Current Stock</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequests as $req)
                        <tr>
                            <td>{{ $req->user->name ?? 'N/A' }}</td>
                            <td>{{ $req->material->name ?? 'N/A' }}</td>
                            <td>{{ $req->quantity }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $req->material->quantity_in_stock ?? 0 }}</span>
                            </td>
                            <td>{{ $req->notes ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <!-- Approve Form -->
                                    <form action="{{ url('/storekeeper/requests/' . $req->id . '/status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="btn btn-sm btn-success fw-semibold">Approve</button>
                                    </form>

                                    <!-- Reject Form -->
                                    <form action="{{ url('/storekeeper/requests/' . $req->id . '/status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="btn btn-sm btn-danger fw-semibold">Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No new requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delivered Records Table -->
<div class="card border-0 shadow-sm">
    <div class="card-section-header">
        🚚 RECORDS OF MATERIALS DELIVERED TO SITE
    </div>
    <div class="card-body-custom p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Requester</th>
                        <th>Material</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Delivery Status Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryRecords as $record)
                        <tr>
                            <td>{{ $record->user->name ?? 'N/A' }}</td>
                            <td>{{ $record->material->name ?? 'N/A' }}</td>
                            <td>{{ $record->quantity }}</td>
                            <td>
                                <span class="badge bg-{{ $record->status === 'Delivered' ? 'success' : 'info' }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td>
                                @if($record->status === 'Approved')
                                    <form action="{{ url('/storekeeper/requests/' . $record->id . '/status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Delivered">
                                        <button type="submit" class="btn btn-sm btn-primary fw-semibold">
                                            Mark as Delivered
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted fs-7">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No delivery records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection