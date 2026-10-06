@extends('layouts.app')

@section('breadcrumb', 'Admin / Audit Trail Log')
@section('title', 'Audit Trail Log')

@section('content')

    <div class="card-section-header" style="background-color: #e91e63; color: white; padding: 12px 15px; font-weight: bold; border-radius: 6px 6px 0 0;">
        📜 INVENTORY UPDATE LOG (AUDIT TRAIL)
    </div>
    <div class="card-body-custom" style="background: white; padding: 20px; border-radius: 0 0 6px 6px; overflow-x: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #edf2f7; font-size: 0.85rem; background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Date & Time</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Operator</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Material</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Action</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Stock Change</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.9rem;">
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->user->name ?? 'System' }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>{{ $log->material->name ?? '-' }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->action }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if(str_contains((string)$log->stock_change, '-'))
                            <span style="background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold;">{{ $log->stock_change }}</span>
                        @else
                            <span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold;">+{{ $log->stock_change }}</span>
                        @endif
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->remarks ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px; border: 1px solid #ddd;">No audit trail logs available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection