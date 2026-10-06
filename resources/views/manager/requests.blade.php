@extends('layouts.app')

@section('breadcrumb', 'Site Manager / Material Request')
@section('title', 'Site Piling Material Request')

@section('content')
<!-- Form Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-section-header">
        📝 PILING MATERIAL REQUEST FORM
    </div>
    <div class="card-body-custom">
        <form action="{{ url('/manager/requests') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Piling Material:</label>
                <select name="material_id" class="form-select" required>
                    <option value="" disabled selected>-- Select Material --</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}">
                            {{ $material->item_code }} - {{ $material->name }} (Available: {{ $material->quantity_in_stock }} {{ $material->unit }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Quantity Required:</label>
                <input type="number" name="quantity" class="form-control" placeholder="e.g. 10" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Notes / Site Location:</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="e.g. For Piling Zone A"></textarea>
            </div>

            <button type="submit" class="btn btn-success px-4 fw-semibold">
                🚀 Submit Request
            </button>
        </form>
    </div>
</div>

<!-- Status Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-section-header">
        📦 YOUR REQUEST & DELIVERY STATUS
    </div>
    <div class="card-body-custom p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Date</th>
                        <th>Material</th>
                        <th>Quantity</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $req->material->name ?? 'N/A' }}</td>
                            <td>{{ $req->quantity }}</td>
                            <td>{{ $req->notes ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $req->status === 'Approved' ? 'success' : ($req->status === 'Rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No request records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection