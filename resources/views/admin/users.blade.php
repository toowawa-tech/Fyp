@extends('layouts.app')

@section('breadcrumb', 'Admin / User Management')
@section('title', 'User Management')

@section('content')

    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add New User -->
    <div class="card-section-header" style="background-color: #e91e63; color: white; padding: 12px 15px; font-weight: bold; border-radius: 6px 6px 0 0;">
        👤 ADD NEW USER
    </div>
    <div class="card-body-custom" style="background: white; padding: 20px; border-radius: 0 0 6px 6px; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <form action="/admin/users" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end;">
            @csrf
            <div>
                <label style="font-weight: bold; font-size: 0.85rem;">Full Name</label>
                <input type="text" name="name" placeholder="e.g. Ali Ahmad" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="font-weight: bold; font-size: 0.85rem;">Email</label>
                <input type="email" name="email" placeholder="storekeeper@test.com" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="font-weight: bold; font-size: 0.85rem;">Password</label>
                <input type="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <label style="font-weight: bold; font-size: 0.85rem;">Assign Role</label>
                <select name="role" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="Storekeeper">Storekeeper</option>
                    <option value="Manager">Site Manager</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div>
                <button type="submit" style="background-color: #28a745; color: white; border: none; padding: 9px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%;">
                    Register User
                </button>
            </div>
        </form>
    </div>

    <!-- User List -->
    <div class="card-section-header" style="background-color: #e91e63; color: white; padding: 12px 15px; font-weight: bold; border-radius: 6px 6px 0 0;">
        👥 USER LIST & ROLE CONTROL
    </div>
    <div class="card-body-custom" style="background: white; padding: 20px; border-radius: 0 0 6px 6px; overflow-x: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #edf2f7; font-size: 0.85rem; background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Email</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Current Role</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Change Role</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.9rem;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><strong>{{ $user->name }}</strong></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $user->email }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd; text-transform: uppercase;">{{ $user->role }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <form action="/admin/users/{{ $user->id }}/role" method="POST" style="display: flex; gap: 5px;">
                            @csrf
                            @method('PATCH')
                            <select name="role" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                                <option value="Manager" {{ strtolower($user->role) == 'manager' ? 'selected' : '' }}>Site Manager</option>
                                <option value="Storekeeper" {{ strtolower($user->role) == 'storekeeper' ? 'selected' : '' }}>Storekeeper</option>
                                <option value="Admin" {{ strtolower($user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <button type="submit" style="background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Save</button>
                        </form>
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if($user->id !== auth()->id())
                        <div style="display: flex; gap: 5px; align-items: center;">
                            <!-- Edit Button -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" style="background-color: #ffc107; color: #000; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                                Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="/admin/users/{{ $user->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this user?')" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete ✕</button>
                            </form>
                        </div>

                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="/admin/users/{{ $user->id }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header" style="background-color: #e91e63; color: white;">
                                            <h5 class="modal-title fs-6 fw-bold" id="editUserModalLabel{{ $user->id }}">Edit User Details</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start" style="padding: 20px;">
                                            <div style="margin-bottom: 15px;">
                                                <label style="font-weight: bold; font-size: 0.85rem; display: block; margin-bottom: 5px;">Full Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                            </div>
                                            <div style="margin-bottom: 15px;">
                                                <label style="font-weight: bold; font-size: 0.85rem; display: block; margin-bottom: 5px;">Email Address</label>
                                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="background-color: #f8f9fa;">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-sm" style="background-color: #28a745; color: white; font-weight: bold;">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                            <small style="color: gray;">(Your Account)</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection