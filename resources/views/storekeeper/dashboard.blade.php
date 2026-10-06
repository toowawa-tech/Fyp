@extends('layouts.app')

@section('breadcrumb', 'Storekeeper / Dashboard')
@section('title', 'Storekeeper Dashboard')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-section-header">
        📦 STOREKEEPER SUMMARY & MODULES
    </div>
    <div class="card-body-custom">
        <h5 class="fw-bold text-dark mb-2">
            Welcome, {{ auth()->user()->name }} (Storekeeper)!
        </h5>
        <p class="text-muted mb-4">
            Please select a module below to manage piling material stock or review requests from the Site Manager:
        </p>

        <div class="d-flex gap-2">
            <a href="/materials" class="btn btn-primary fw-semibold px-4 py-2">
                📦 Manage Piling Material Stock
            </a>
            <a href="/storekeeper/requests" class="btn btn-success fw-semibold px-4 py-2">
                📋 Review & Approve Requests
            </a>
        </div>
    </div>
</div>
@endsection