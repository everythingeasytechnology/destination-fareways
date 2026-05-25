@extends('layouts.admin')

@section('title', 'Travel Blogs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Blogs</li>
@endsection

@section('content')
<div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
    <div>
        <h2 class="display-font mb-1 text-navy">Travel Blogs</h2>
        <p class="text-muted mb-0">Publish rich articles, news, travel tips, and stories with optimized schema structures.</p>
    </div>
    
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-action rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i>Create New Blog Post
    </a>
</div>

<!-- Blogs Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="blogs-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Post Title</th>
                    <th>Author</th>
                    <th>Category & Tags</th>
                    <th>Publish Date</th>
                    <th>Views</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end" style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                <tr>
                    <td>
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured" class="rounded object-fit-cover shadow-sm" width="55" height="40">
                        @else
                            <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center border" style="width: 55px; height: 40px; font-size: 0.75rem;">No Img</div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-navy">{{ $blog->title }}</div>
                        <div class="small text-muted font-monospace" style="font-size: 0.75rem;">/blogs/{{ $blog->slug }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($blog->author_image)
                                <img src="{{ asset('storage/' . $blog->author_image) }}" alt="Author" class="rounded-circle object-fit-cover shadow-sm" width="30" height="30">
                            @else
                                <div class="rounded-circle bg-navy text-gold border d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.7rem; font-weight: bold;">{{ substr($blog->author_name ?? 'A', 0, 1) }}</div>
                            @endif
                            <div>
                                <div class="fw-semibold text-navy small" style="font-size: 0.8rem;">{{ $blog->author_name ?? 'Anonymous' }}</div>
                                @if($blog->read_time)
                                    <div class="text-muted small font-monospace" style="font-size: 0.7rem;"><i class="fa-regular fa-clock me-1"></i>{{ $blog->read_time }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><span class="badge bg-royal bg-opacity-10 text-royal px-2 py-1" style="font-size: 0.7rem;">{{ $blog->category ?? 'General' }}</span></div>
                        @if($blog->tags)
                            <div class="mt-1 flex-wrap gap-1 d-flex">
                                @foreach(explode(',', $blog->tags) as $tag)
                                    <span class="badge bg-light text-muted border px-1.5 py-0.5" style="font-size: 0.65rem;">#{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="small text-muted" style="font-size: 0.8rem;">
                        @if($blog->published_at)
                            {{ $blog->published_at->format('Y-m-d H:i') }}
                        @else
                            <span class="text-danger small">Draft</span>
                        @endif
                    </td>
                    <td class="font-monospace small text-navy fw-bold">
                        <i class="fa-regular fa-eye me-1 text-muted"></i>{{ number_format($blog->views) }}
                    </td>
                    <td>
                        @if($blog->is_featured)
                            <span class="badge bg-warning text-navy rounded-pill px-2 py-1"><i class="fa-solid fa-star me-1"></i>Featured</span>
                        @else
                            <span class="text-muted small">Standard</span>
                        @endif
                    </td>
                    <td>
                        @if($blog->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1.5"><i class="fa-solid fa-circle-xmark me-1"></i>Draft</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-light border text-navy" title="Edit Blog Post">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline delete-form-confirm">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete Blog Post">
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
        var table = $('#blogs-table').DataTable({
            responsive: true,
            order: [[4, 'desc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search travel blogs..."
            }
        });

        // Confirmation dialog for deletes
        $('.delete-form-confirm').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            if (confirm("Are you sure you want to delete this blog post? It will be soft deleted.")) {
                form.submit();
            }
        });
    });
</script>
@endsection
