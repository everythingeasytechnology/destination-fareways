@extends('layouts.admin')

@section('title', 'Global SEO Pages Settings')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">SEO Settings</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Global SEO Pages Settings</h2>
    <p class="text-muted mb-0">Configure metadata, target focus keywords, indexation directives, and social share representations for the core landing pages.</p>
</div>

<!-- SEO List Table Card -->
<div class="card premium-card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table id="seo-table" class="table table-hover align-middle" style="width:100%">
            <thead>
                <tr>
                    <th>Page Name</th>
                    <th>Identifier</th>
                    <th>Meta Title</th>
                    <th>Focus Keyword</th>
                    <th>Robots Tag</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seoSettings as $seoSetting)
                <tr>
                    <td>
                        <div class="fw-bold text-navy">{{ $seoSetting->page_name }}</div>
                    </td>
                    <td>
                        <span class="badge bg-royal bg-opacity-10 text-royal font-monospace" style="font-size: 0.75rem;">{{ $seoSetting->page_identifier }}</span>
                    </td>
                    <td>
                        <div class="small text-muted text-truncate" style="max-width: 250px;" title="{{ $seoSetting->meta_title }}">
                            {{ $seoSetting->meta_title }}
                        </div>
                    </td>
                    <td>
                        @if($seoSetting->focus_keyword)
                            <span class="badge bg-warning text-navy rounded-pill px-2 py-1" style="font-size: 0.7rem;"><i class="fa-solid fa-key me-1"></i>{{ $seoSetting->focus_keyword }}</span>
                        @else
                            <span class="text-muted small">Not Specified</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary font-monospace" style="font-size: 0.75rem;">{{ $seoSetting->robots_tag }}</span>
                    </td>
                    <td class="text-end">
                        <!-- Edit Button -->
                        <a href="{{ route('admin.seo.edit', $seoSetting->id) }}" class="btn btn-sm btn-light border text-navy" title="Configure SEO Details">
                            <i class="fa-regular fa-pen-to-square me-1"></i>Configure
                        </a>
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
        $('#seo-table').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search pages..."
            }
        });
    });
</script>
@endsection
