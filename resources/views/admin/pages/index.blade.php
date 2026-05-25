@extends('layouts.admin')

@section('title', 'Pages Manager')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pages</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Pages Manager</h2>
        <p class="text-muted mb-0">Create and modify standalone landing pages, configure header banners, and control custom schema declarations.</p>
    </div>
    
    <a href="{{ route('admin.pages.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Create New Page
    </a>
</div>

<!-- Pages Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="pages-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Page Title</th>
                    <th>Subheading</th>
                    <th>URL Path Slug</th>
                    <th>Breadcrumbs</th>
                    <th>Schemas Attached</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                <tr>
                    <td>
                        <div class="fw-bold text-navy">{{ $page->title }}</div>
                        @if($page->focus_keyword)
                            <span class="badge bg-light text-navy border font-monospace mt-1" style="font-size: 0.65rem;"><i class="fa-solid fa-key me-1 text-muted"></i>{{ $page->focus_keyword }}</span>
                        @endif
                    </td>
                    <td class="small text-muted text-truncate" style="max-width: 250px;">
                        {{ $page->subtitle ?? 'No subheading declared' }}
                    </td>
                    <td class="font-monospace small text-navy fw-semibold" style="font-size: 0.75rem;">
                        /{{ $page->slug }}
                    </td>
                    <td>
                        @if($page->show_breadcrumb)
                            <span class="badge bg-royal bg-opacity-10 text-royal rounded-pill px-2 py-1" style="font-size: 0.7rem;"><i class="fa-solid fa-angle-right me-1"></i>Active</span>
                        @else
                            <span class="text-muted small">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @if($page->schema_markup)
                                <span class="badge bg-dark text-warning font-monospace" style="font-size: 0.65rem;">LD+JSON</span>
                            @endif
                            @if($page->faq_schema)
                                <span class="badge bg-light text-navy border font-monospace" style="font-size: 0.65rem;">FAQ</span>
                            @endif
                            @if($page->breadcrumb_schema)
                                <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.65rem;">Breadcrumb</span>
                            @endif
                            @if(!$page->schema_markup && !$page->faq_schema && !$page->breadcrumb_schema)
                                <span class="text-muted small">None</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($page->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Draft</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Page">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Page">
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
        // Initialize DataTables
        var table = $('#pages-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search pages..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this page? It will be soft deleted.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
