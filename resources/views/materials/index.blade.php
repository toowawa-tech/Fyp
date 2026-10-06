@extends('layouts.app')

@section('breadcrumb', 'Pages / Inventory / Materials')
@section('title', 'Piling Materials & Inventory List')

@section('content')

    {{-- Notification Messages --}}
    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #842029; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Add New Piling Material Form -->
    <div class="card-section-header" style="background-color: #e91e63; color: white; padding: 12px 15px; font-weight: bold; border-radius: 6px 6px 0 0; text-transform: uppercase;">
        📦 ADD NEW PILING MATERIAL
    </div>
    <div class="card-body-custom" style="background: white; padding: 20px; border-radius: 0 0 6px 6px; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <form action="/materials" method="POST" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            @csrf
            <input type="text" name="item_code" placeholder="Item Code (eg: PIL-500)" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 150px;">
            <input type="text" name="name" placeholder="Material Name" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; flex: 1.5; min-width: 180px;">
            <input type="text" name="category" placeholder="Category (eg: Steel/Concrete)" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 150px;">
            <input type="number" name="quantity_in_stock" placeholder="0" min="0" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 80px;">
            <input type="text" name="unit" placeholder="Unit (Pcs/Meter)" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; flex: 1; min-width: 120px;">
            <input type="number" name="min_stock_level" placeholder="10" min="1" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 80px;">
            
            <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 9px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                + Add Material
            </button>
        </form>
    </div>

    <!-- Material List & Inventory Status Table -->
    <div class="card-section-header" style="background-color: #e91e63; color: white; padding: 12px 15px; font-weight: bold; border-radius: 6px 6px 0 0; text-transform: uppercase;">
        📋 MATERIAL LIST & INVENTORY STATUS
    </div>
    <div class="card-body-custom" style="background: white; padding: 20px; border-radius: 0 0 6px 6px; overflow-x: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background-color: #007bff; color: white; font-size: 0.9rem;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Item Code</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Material Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Category</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Stock Balance</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Status</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Update Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.9rem;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>{{ $material->item_code }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $material->name }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $material->category }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>{{ $material->quantity_in_stock }} {{ $material->unit }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if($material->quantity_in_stock <= $material->min_stock_level)
                            <span style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">Low Stock!</span>
                        @else
                            <span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold;">Sufficient</span>
                        @endif
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <form action="/materials/{{ $material->id }}/update-stock" method="POST" style="display: flex; gap: 5px;">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $material->quantity_in_stock }}" min="0" required style="width: 70px; padding: 4px; border: 1px solid #ccc; border-radius: 4px;">
                            <button type="submit" style="background-color: #ffc107; color: #000; border: none; padding: 4px 10px; border-radius: 4px; font-weight: bold; cursor: pointer;">Save</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px; border: 1px solid #ddd;">No materials registered yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection