@extends('layouts.app')

@section('breadcrumb', 'Admin / Dashboard')
@section('title', 'Admin Dashboard & Audit Trail')

@section('content')

    <!-- Mesej Notifikasi Status -->
    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; padding: 12px 15px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Section 1: Borang Tambah Pengguna -->
    <div class="card-section-header">
        👤 Tambah Pengguna Baharu
    </div>
    <div class="card-body-custom">
        <form action="/admin/users" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
            @csrf
            <div>
                <label style="font-size: 0.85rem; font-weight: bold; display: block; margin-bottom: 5px;">Nama Full</label>
                <input type="text" name="name" required placeholder="Contoh: Ali Ahmad" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="font-size: 0.85rem; font-weight: bold; display: block; margin-bottom: 5px;">Email</label>
                <input type="email" name="email" required placeholder="ali@test.com" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="font-size: 0.85rem; font-weight: bold; display: block; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" required placeholder="******" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="font-size: 0.85rem; font-weight: bold; display: block; margin-bottom: 5px;">Assign Role</label>
                <select name="role" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: white;">
                    <option value="storekeeper">Storekeeper</option>
                    <option value="manager">Site Manager</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">
                    Daftar Pengguna
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Senarai Pengguna & Kawalan Role -->
    <div class="card-section-header">
        👥 Senarai Pengguna & Kawalan Role
    </div>
    <div class="card-body-custom" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #edf2f7; font-size: 0.85rem; color: #718096; background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Nama</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Email</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Role Semasa</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Tukar Role</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.9rem;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>{{ $user->name }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $user->email }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><code>{{ strtoupper($user->role) }}</code></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <form action="/admin/users/{{ $user->id }}/role" method="POST" style="display: flex; gap: 5px; align-items: center;">
                            @csrf
                            @method('PATCH')
                            <select name="role" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc; background: white;">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="storekeeper" {{ $user->role == 'storekeeper' ? 'selected' : '' }}>Storekeeper</option>
                                <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Site Manager</option>
                            </select>
                            <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Simpan</button>
                        </form>
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if($user->id !== auth()->id())
                        <form action="/admin/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Adakah anda pasti mahu padam pengguna ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Padam ❌</button>
                        </form>
                        @else
                            <small style="color: gray;">(Akaun Anda)</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Section 3: Audit Log Inventory -->
    <div class="card-section-header">
        📜 Log Kemaskini Inventory (Audit Trail)
    </div>
    <div class="card-body-custom" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #edf2f7; font-size: 0.85rem; color: #718096; background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Tarikh & Masa</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Pengendali</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Bahan</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Tindakan</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Perubahan Stok</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.9rem;">
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->user->name ?? 'Sistem' }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>{{ $log->material->name ?? 'N/A' }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->action }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if($log->quantity_change > 0)
                            <span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.8rem;">+{{ $log->quantity_change }}</span>
                        @else
                            <span style="background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 0.8rem;">{{ $log->quantity_change }}</span>
                        @endif
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $log->remarks ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px; border: 1px solid #ddd;">Tiada rekod log pergerakan stok setakat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection