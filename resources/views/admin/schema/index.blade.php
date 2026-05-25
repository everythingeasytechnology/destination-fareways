@extends('layouts.admin')

@section('title', 'Schema Markups Manager')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Schema Settings</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Structured Schema Markups</h2>
        <p class="text-muted mb-0">Manage JSON-LD structured schemas to enable Google rich snippets, FAQ accordion drops, and corporate graph mappings.</p>
    </div>
    
    <a href="{{ route('admin.schema.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Add Schema Markup
    </a>
</div>

<!-- Schema Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="schema-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Page Scope</th>
                    <th>Schema Type</th>
                    <th>JSON Payload Preview</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schemas as $schema)
                <tr>
                    <td>
                        <span class="badge bg-navy text-gold px-2.5 py-1.5 font-monospace text-uppercase" style="font-size: 0.72rem;">{{ $schema->page_identifier }}</span>
                    </td>
                    <td>
                        <div class="fw-bold text-navy"><i class="fa-solid fa-code text-royal me-1.5"></i>{{ $schema->schema_type }}</div>
                    </td>
                    <td>
                        <div class="text-truncate font-monospace small text-muted" style="max-width: 320px; font-size: 0.75rem;" title="{{ $schema->schema_json }}">
                            <code>{{ Str::limit($schema->schema_json, 80) }}</code>
                        </div>
                    </td>
                    <td>
                        @if($schema->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.schema.edit', $schema->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Schema Details">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.schema.destroy', $schema->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Schema Markup">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
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
        $('#schema-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search schemas..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this structured schema markup?")) {
                form.submit();
            }
        });
    });
</script>
@endsection
