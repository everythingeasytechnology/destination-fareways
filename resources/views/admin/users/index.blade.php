@extends('layouts.admin')

@section('title', 'Users & Roles Manager')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Users Management</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Administrators & Roles</h2>
        <p class="text-muted mb-0">Configure security permissions levels, monitor last login timestamps, and manage user profile credentials.</p>
    </div>
    
    <a href="{{ route('admin.users.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Add Administrator
    </a>
</div>

<!-- Users Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="users-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Profile User</th>
                    <th>Email Address</th>
                    <th>Permissions Role</th>
                    <th>Last Active Login</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2.5">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle object-fit-cover shadow-sm border border-light" width="40" height="40">
                            @else
                                <div class="rounded-circle bg-navy text-gold border d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-navy mb-0.5">{{ $user->name }}</div>
                                @if(Auth::id() === $user->id)
                                    <span class="badge bg-royal rounded-pill text-white" style="font-size: 0.62rem;">You (Current Session)</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-muted font-monospace small">{{ $user->email }}</span>
                    </td>
                    <td>
                        @if($user->role === 'superadmin')
                            <span class="badge bg-navy text-gold px-2.5 py-1 text-uppercase font-monospace" style="font-size: 0.7rem; border: 1px solid var(--gold);"><i class="fa-solid fa-crown me-1"></i>Superadmin</span>
                        @elseif($user->role === 'admin')
                            <span class="badge bg-royal bg-opacity-10 text-royal px-2.5 py-1 text-uppercase font-monospace" style="font-size: 0.7rem;"><i class="fa-solid fa-user-shield me-1"></i>Admin</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2.5 py-1 text-uppercase font-monospace" style="font-size: 0.7rem;"><i class="fa-solid fa-user me-1"></i>Editor</span>
                        @endif
                    </td>
                    <td>
                        @if($user->last_login_at)
                            <div class="small text-navy" style="font-size: 0.8rem;"><i class="fa-regular fa-clock me-1 text-muted"></i>{{ $user->last_login_at->format('M d, Y h:i A') }}</div>
                        @else
                            <div class="small text-muted" style="font-size: 0.8rem;"><i class="fa-regular fa-clock me-1"></i>Never logged in</div>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Suspended</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Admin Settings">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            @if(Auth::id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline delete-form-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete User">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-sm btn-light border text-muted" disabled title="Self-deletion is locked" style="cursor: not-allowed;">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#users-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search admins..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this administrator? This action is permanent!")) {
                form.submit();
            }
        });
    });
</script>
@endsection
