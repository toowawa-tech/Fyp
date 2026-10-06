@extends('layouts.app')

@section('breadcrumb', 'Site Manager / Dashboard')
@section('title', 'Site Manager Dashboard')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-section-header">
        🏗️ SITE MANAGER DASHBOARD
    </div>
    <div class="card-body-custom">
        <h5 class="fw-bold text-dark mb-2">
            Welcome, {{ auth()->user()->name }} (Site Manager)!
        </h5>
        <p class="text-muted mb-4">
            <strong>Email:</strong> {{ auth()->user()->email }}
        </p>

        <a href="/manager/requests" class="btn btn-success fw-semibold px-4 py-2">
            📋 Request Materials from Storekeeper & Check Status
        </a>
    </div>
</div>
@endsection