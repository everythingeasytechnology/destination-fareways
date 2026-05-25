@extends('layouts.admin')

@section('title', 'Add Administrator')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted">Users Management</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection

@section('content')
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light border"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>
    <h2 class="display-font mb-1 text-navy">Add Administrator</h2>
    <p class="text-muted mb-0">Create new administrator profiles, assign permission levels, upload profile pictures, and configure access security.</p>
</div>

<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="user-form">
    @csrf

    <div class="row g-4">
        <!-- Input Form Left Column -->
        <div class="col-lg-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold text-navy small">Full Profile Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold text-navy small">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="e.g. johndoe@destinationfareways.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="col-md-6">
                        <label for="role" class="form-label fw-bold text-navy small">Access Permission Level <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">-- Select Role --</option>
                            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin (Full Access & Settings Control)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (CMS, Leads, & Inquiries Control)</option>
                            <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor (CMS Content Only)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="is_active" class="form-label fw-bold text-navy small">System Access Toggle <span class="text-danger">*</span></label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active (Granted Access)</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Suspended (Revoked Access)</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-bold text-navy small">Account Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-key text-muted"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required minlength="6" placeholder="At least 6 characters">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-bold text-navy small">Confirm Account Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-key text-muted"></i></span>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6">
                        </div>
                        <div id="password-match-feedback" class="form-text small mt-1.5 fw-semibold d-none"></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex align-items-center gap-2">
                <button type="submit" class="btn btn-action rounded-pill px-5 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-circle-check me-2"></i>Create User Account
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">Cancel</a>
            </div>
        </div>

        <!-- Photo Avatar Preview Right Column -->
        <div class="col-lg-4">
            <div class="card premium-card border-0 shadow-sm p-4 text-center">
                <h6 class="fw-bold text-navy small mb-3 border-bottom pb-2 text-start"><i class="fa-regular fa-image me-1.5 text-royal"></i>Account Profile Graphic</h6>
                
                <div class="d-flex flex-column align-items-center mb-3">
                    <div class="rounded-circle border overflow-hidden shadow-sm bg-light mb-3" style="width: 140px; height: 140px; border-width: 3px !important; border-color: var(--border) !important;">
                        <img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" alt="Avatar Preview" class="w-100 h-100 object-fit-cover d-none" id="avatar-preview-el">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted" id="avatar-preview-fallback">
                            <i class="fa-solid fa-user-astronaut fs-1 text-royal mb-2"></i>
                            <span style="font-size: 0.72rem;">No Image Selected</span>
                        </div>
                    </div>
                    
                    <div class="w-100">
                        <label for="avatar" class="btn btn-sm btn-light border w-100 py-2 font-monospace" style="cursor: pointer; font-size: 0.8rem;">
                            <i class="fa-solid fa-cloud-arrow-up me-1"></i> Choose Image File
                        </label>
                        <input type="file" class="form-control d-none @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback d-block text-center mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="alert bg-softgray border text-start small text-muted mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                    <i class="fa-solid fa-circle-info text-royal me-1"></i> Avatar requirements:
                    <ul class="mb-0 ps-3 mt-1.5">
                        <li>Standard format: JPG, PNG, WEBP, GIF.</li>
                        <li>Maximum size limitation: 2MB.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
        // Avatar File Upload Preview
        $('#avatar').on('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatar-preview-el').attr('src', e.target.result).removeClass('d-none');
                    $('#avatar-preview-fallback').addClass('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Live Password Match Check
        function checkPasswordMatch() {
            var pass = $('#password').val();
            var conf = $('#password_confirmation').val();

            if (!pass || !conf) {
                $('#password-match-feedback').addClass('d-none').removeClass('text-success text-danger');
                return;
            }

            $('#password-match-feedback').removeClass('d-none');
            if (pass === conf) {
                $('#password-match-feedback').addClass('text-success').removeClass('text-danger').html('<i class="fa-solid fa-circle-check me-1"></i>Passwords match successfully.');
            } else {
                $('#password-match-feedback').addClass('text-danger').removeClass('text-success').html('<i class="fa-solid fa-circle-xmark me-1"></i>Passwords do not match yet.');
            }
        }

        $('#password, #password_confirmation').on('input keyup change', function() {
            checkPasswordMatch();
        });

        // Password matching block on submit
        $('#user-form').on('submit', function(e) {
            var pass = $('#password').val();
            var conf = $('#password_confirmation').val();
            if (pass !== conf) {
                e.preventDefault();
                alert('Passwords do not match. Please verify correct password inputs.');
            }
        });
    });
</script>
@endsection
