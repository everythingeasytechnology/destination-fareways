@extends('layouts.admin')

@section('title', 'Sitemap Manager')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Sitemap Manager</li>
@endsection

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="display-font mb-1 text-navy">Sitemap Manager</h2>
        <p class="text-muted mb-0">Sitemap automatically updates from active offers, published blogs, active destinations, and static pages.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center">
            <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> View XML
        </a>
        <form action="{{ route('admin.sitemap.refresh') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning text-navy d-flex align-items-center">
                <i class="fa-solid fa-rotate me-2"></i> Refresh Sitemap
            </button>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-2">
        <div class="card premium-card border-0 shadow-sm p-3 h-100">
            <span class="text-muted small">Total URLs</span>
            <span class="fs-3 fw-bold text-navy">{{ $summary['total'] }}</span>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card premium-card border-0 shadow-sm p-3 h-100">
            <span class="text-muted small">Static Pages</span>
            <span class="fs-3 fw-bold text-navy">{{ $summary['static'] }}</span>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card premium-card border-0 shadow-sm p-3 h-100">
            <span class="text-muted small">Offers</span>
            <span class="fs-3 fw-bold text-navy">{{ $summary['offers'] }}</span>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card premium-card border-0 shadow-sm p-3 h-100">
            <span class="text-muted small">Blogs</span>
            <span class="fs-3 fw-bold text-navy">{{ $summary['blogs'] }}</span>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card premium-card border-0 shadow-sm p-3 h-100">
            <span class="text-muted small">Destinations</span>
            <span class="fs-3 fw-bold text-navy">{{ $summary['destinations'] }}</span>
        </div>
    </div>
    <div class="col-6 col-xl-2">
        <div class="card premium-card border-0 shadow-sm p-3 h-100">
            <span class="text-muted small">Latest Update</span>
            <span class="fs-6 fw-bold text-navy mt-2">{{ $summary['latest_lastmod'] ?? now()->toDateString() }}</span>
        </div>
    </div>
</div>

<div class="card premium-card border-0 shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-navy fw-bold">Recently Updated Sitemap URLs</h5>
        <span class="badge bg-success bg-opacity-10 text-success">Auto Generated</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Last Modified</th>
                    <th>Change Frequency</th>
                    <th>Priority</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUrls as $url)
                    <tr>
                        <td>
                            <a href="{{ $url['loc'] }}" target="_blank" class="text-decoration-none text-navy">
                                {{ $url['loc'] }}
                            </a>
                        </td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $url['type'] }}</span></td>
                        <td class="font-monospace small">{{ $url['lastmod'] }}</td>
                        <td class="font-monospace small">{{ $url['changefreq'] }}</td>
                        <td class="font-monospace small">{{ $url['priority'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No sitemap URLs available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
