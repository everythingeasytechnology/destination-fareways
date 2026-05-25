@extends('layouts.admin')

@section('title', 'FAQs Manager')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">FAQs</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Frequently Asked Questions</h2>
        <p class="text-muted mb-0">Create and map FAQs to specific page slugs or international landing pages for optimized SEO answer boxes.</p>
    </div>
    
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Add FAQ
    </a>
</div>

<!-- FAQs Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="faqs-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 60px;">Sort</th>
                    <th>Question</th>
                    <th>Answer Teaser</th>
                    <th>Page Context</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faqs as $faq)
                <tr>
                    <td class="font-monospace small text-muted text-center fw-bold">
                        #{{ $faq->sort_order }}
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $faq->question }}</div>
                    </td>
                    <td>
                        <div class="small text-muted text-truncate" style="max-width: 320px;" title="{{ strip_tags($faq->answer) }}">
                            {{ strip_tags($faq->answer) }}
                        </div>
                    </td>
                    <td>
                        @if($faq->page_slug === 'global')
                            <span class="badge bg-navy text-gold px-2 py-1" style="font-size: 0.7rem;"><i class="fa-solid fa-globe me-1"></i>Global</span>
                        @else
                            <span class="badge bg-royal bg-opacity-10 text-royal px-2 py-1 font-monospace" style="font-size: 0.7rem;">/{{ $faq->page_slug }}</span>
                        @endif
                    </td>
                    <td>
                        @if($faq->category)
                            <span class="badge bg-light text-navy border px-2 py-1" style="font-size: 0.7rem;">{{ $faq->category }}</span>
                        @else
                            <span class="text-muted small">None</span>
                        @endif
                    </td>
                    <td>
                        @if($faq->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit FAQ">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete FAQ">
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
        var table = $('#faqs-table').DataTable({
            responsive: true,
            order: [[3, 'asc'], [0, 'asc']],
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search FAQs..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this FAQ?")) {
                form.submit();
            }
        });
    });
</script>
@endsection
