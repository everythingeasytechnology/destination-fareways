@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
@php
    $adminUser = Auth::user();
    $canManageCommunications = $adminUser && $adminUser->hasAnyRole(['superadmin', 'admin']);
    $canManageCms = $adminUser && $adminUser->hasAnyRole(['superadmin', 'admin', 'editor']);
    $canManageSystem = $adminUser && $adminUser->hasRole('superadmin');
@endphp

<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Welcome to Destination Fareways Admin</h2>
    <p class="text-muted mb-0">Monitor flight enquiries, manage sales leads, adjust configurations, and optimize SEO rankings from a single hub.</p>
</div>

<!-- 6 Stat Cards Grid -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Leads -->
    @if($canManageCommunications)
    <div class="col-12 col-sm-6 col-xxl-2">
        <div class="card premium-card p-3 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #1D4ED8 0%, #1e40af 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small font-monospace text-uppercase" style="font-size: 0.72rem;">Travel Leads</span>
                    <h3 class="mb-0 fw-bold mt-1 text-white">{{ $stats['leads_count'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-users-line fs-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.leads.index') }}" class="text-white-50 small text-decoration-none d-flex align-items-center">
                    <span>Manage Leads</span> <i class="fa-solid fa-angle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat 2: Enquiries -->
    @if($canManageCommunications)
    <div class="col-12 col-sm-6 col-xxl-2">
        <div class="card premium-card p-3 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small font-monospace text-uppercase" style="font-size: 0.72rem;">Flight Enquiries</span>
                    <h3 class="mb-0 fw-bold mt-1 text-white">{{ $stats['enquiries_count'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-ticket fs-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.enquiries.index') }}" class="text-white-50 small text-decoration-none d-flex align-items-center">
                    <span>Review Enquiries</span> <i class="fa-solid fa-angle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat 3: Contact Messages -->
    @if($canManageCommunications)
    <div class="col-12 col-sm-6 col-xxl-2">
        <div class="card premium-card p-3 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small font-monospace text-uppercase" style="font-size: 0.72rem;">Inbox Messages</span>
                    <h3 class="mb-0 fw-bold mt-1 text-white">{{ $stats['messages_count'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-envelope-open-text fs-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.contacts.index') }}" class="text-white-50 small text-decoration-none d-flex align-items-center">
                    <span>Read Messages</span> <i class="fa-solid fa-angle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat 4: Subscribers -->
    @if($canManageCommunications)
    <div class="col-12 col-sm-6 col-xxl-2">
        <div class="card premium-card p-3 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small font-monospace text-uppercase" style="font-size: 0.72rem;">Newsletter</span>
                    <h3 class="mb-0 fw-bold mt-1 text-white">{{ $stats['subscribers_count'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-paper-plane fs-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.newsletter.index') }}" class="text-white-50 small text-decoration-none d-flex align-items-center">
                    <span>View Subscribers</span> <i class="fa-solid fa-angle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat 5: Blogs -->
    @if($canManageCms)
    <div class="col-12 col-sm-6 col-xxl-2">
        <div class="card premium-card p-3 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #38BDF8 0%, #0284c7 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small font-monospace text-uppercase" style="font-size: 0.72rem;">Travel Blogs</span>
                    <h3 class="mb-0 fw-bold mt-1 text-white">{{ $stats['blogs_count'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-newspaper fs-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.blogs.index') }}" class="text-white-50 small text-decoration-none d-flex align-items-center">
                    <span>Write Blogs</span> <i class="fa-solid fa-angle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Stat 6: Offers -->
    @if($canManageCms)
    <div class="col-12 col-sm-6 col-xxl-2">
        <div class="card premium-card p-3 border-0 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #F59E0B 0%, #d97706 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small font-monospace text-uppercase" style="font-size: 0.72rem;">Promo Offers</span>
                    <h3 class="mb-0 fw-bold mt-1 text-white">{{ $stats['offers_count'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-10 p-3 rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-tags fs-5"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('admin.offers.index') }}" class="text-white-50 small text-decoration-none d-flex align-items-center">
                    <span>Adjust Offers</span> <i class="fa-solid fa-angle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Quick Action Shortcuts -->
<div class="card premium-card border-0 p-4 mb-4">
    <h5 class="display-font mb-3 text-navy"><i class="fa-solid fa-rocket text-warning me-2"></i>Quick Operations Console</h5>
    <div class="d-flex flex-wrap gap-2">
        @if($canManageCms)
            <a href="{{ route('admin.offers.create') }}" class="btn btn-action"><i class="fa-solid fa-tag me-1"></i> New Promo Offer</a>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-action" style="background-color: #38BDF8; border-color: #38BDF8; color: #07111F;"><i class="fa-solid fa-pen-fancy me-1"></i> New Blog Article</a>
            <a href="{{ route('admin.destinations.create') }}" class="btn btn-action" style="background-color: #10B981; border-color: #10B981;"><i class="fa-solid fa-map-pin me-1"></i> Add Destination</a>
        @endif
        @if($canManageSystem)
            <a href="{{ route('admin.users.create') }}" class="btn btn-action" style="background-color: #64748B; border-color: #64748B;"><i class="fa-solid fa-user-plus me-1"></i> Add Team User</a>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-cta"><i class="fa-solid fa-sliders me-1"></i> Global Settings</a>
            <a href="{{ route('admin.call-settings.index') }}" class="btn btn-cta" style="background-color: #ef4444; border-color: #ef4444; color: white;"><i class="fa-solid fa-phone me-1"></i> Call Now Buttons</a>
        @endif
    </div>
</div>

<!-- Recent Logs Block -->
<div class="row g-4">
    <!-- Recent Leads -->
    @if($canManageCommunications)
    <div class="col-12 col-xl-6">
        <div class="card premium-card border-0 p-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="display-font mb-0 text-navy"><i class="fa-solid fa-users text-primary me-2"></i>Recent Sales Leads</h5>
                <a href="{{ route('admin.leads.index') }}" class="btn btn-sm btn-outline-primary py-1 px-3">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle" style="font-size: 0.9rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th>Name</th>
                            <th>Route</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentLeads as $lead)
                            <tr>
                                <td>
                                    <div class="fw-bold text-navy">{{ $lead->name }}</div>
                                    <small class="text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $lead->phone }}</small>
                                </td>
                                <td>
                                    <span class="mono-badge bg-light text-navy px-2 py-1 rounded fw-semibold">
                                        {{ $lead->from_city }} <i class="fa-solid fa-plane-right mx-1 text-warning"></i> {{ $lead->to_city }}
                                    </span>
                                </td>
                                <td>{{ $lead->travel_date ? $lead->travel_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'new' => 'bg-danger-subtle text-danger',
                                            'contacted' => 'bg-warning-subtle text-warning-emphasis',
                                            'converted' => 'bg-success-subtle text-success',
                                            'closed' => 'bg-secondary-subtle text-muted',
                                        ][$lead->status] ?? 'bg-light text-navy';
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-capitalize rounded-pill px-2.5 py-1">{{ $lead->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-inbox fs-4 mb-2 d-block"></i> No leads found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Enquiries -->
    @if($canManageCommunications)
    <div class="col-12 col-xl-6">
        <div class="card premium-card border-0 p-4 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="display-font mb-0 text-navy"><i class="fa-solid fa-ticket text-info me-2"></i>Recent Flight Enquiries</h5>
                <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-info py-1 px-3 text-info">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle" style="font-size: 0.9rem;">
                    <thead class="table-light text-muted">
                        <tr>
                            <th>Passenger</th>
                            <th>Airports</th>
                            <th>Departure</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentEnquiries as $enq)
                            <tr>
                                <td>
                                    <div class="fw-bold text-navy">{{ $enq->name }}</div>
                                    <small class="text-muted"><i class="fa-solid fa-user-check me-1"></i>Pax: {{ $enq->adults + $enq->children + $enq->infants }} ({{ ucfirst($enq->cabin_class) }})</small>
                                </td>
                                <td>
                                    <span class="mono-badge bg-light text-navy px-2 py-1 rounded fw-semibold text-uppercase">
                                        {{ $enq->from_airport }} <i class="fa-solid fa-arrow-right mx-1 text-info"></i> {{ $enq->to_airport }}
                                    </span>
                                </td>
                                <td>{{ $enq->departure_date ? $enq->departure_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'new' => 'bg-danger-subtle text-danger',
                                            'reviewed' => 'bg-info-subtle text-info-emphasis',
                                            'quoted' => 'bg-warning-subtle text-warning-emphasis',
                                            'booked' => 'bg-success-subtle text-success',
                                            'cancelled' => 'bg-secondary-subtle text-muted',
                                        ][$enq->status] ?? 'bg-light text-navy';
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-capitalize rounded-pill px-2.5 py-1">{{ $enq->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-inbox fs-4 mb-2 d-block"></i> No flight enquiries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
