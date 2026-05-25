@extends('layouts.admin')

@section('title', 'Add FAQ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}" class="text-decoration-none text-muted">FAQs</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add</li>
@endsection

@section('content')
<div class="mb-4">
    <h2 class="display-font mb-1 text-navy">Add FAQ</h2>
    <p class="text-muted mb-0">Formulate a helpful question and answer set. Link it to specific landing pages to feed search engine rich text cards.</p>
</div>

<form action="{{ route('admin.faqs.store') }}" method="POST">
    @csrf

    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-12 col-xl-8">
            <div class="card premium-card border-0 shadow-sm p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="question" class="form-label fw-bold">FAQ Question</label>
                            <input type="text" class="form-control px-3" id="question" name="question" value="{{ old('question') }}" placeholder="e.g. Can I change my booking after purchase?" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="answer" class="form-label fw-bold">FAQ Answer Body</label>
                            <textarea class="form-control tinymce-editor" id="answer" name="answer" rows="8">{{ old('answer') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-12 col-xl-4">
            <div class="card premium-card border-0 shadow-sm p-4">
                <h5 class="fw-bold text-navy mb-4 border-bottom pb-2">
                    <i class="fa-solid fa-sliders text-warning me-2"></i>Parameters
                </h5>

                <div class="mb-3">
                    <label for="page_slug" class="form-label fw-bold">Display Page Target</label>
                    <select class="form-select px-3 font-monospace" id="page_slug" name="page_slug" required style="font-size: 0.85rem;">
                        <option value="global" {{ old('page_slug') === 'global' ? 'selected' : '' }}>🌎 Global (All Pages)</option>
                        @foreach($pages as $page)
                            <option value="{{ $page->slug }}" {{ old('page_slug') === $page->slug ? 'selected' : '' }}>/{{ $page->slug }} ({{ $page->title }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label fw-bold">FAQ Category (optional)</label>
                    <input type="text" class="form-control px-3" id="category" name="category" value="{{ old('category') }}" placeholder="e.g. Bookings, Payments">
                </div>

                <div class="mb-3">
                    <label for="sort_order" class="form-label fw-bold">Sort Priority Order</label>
                    <input type="number" class="form-control px-3" id="sort_order" name="sort_order" value="0" min="0" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select class="form-select px-3" id="status" name="status" required>
                        <option value="active">Active (Visible)</option>
                        <option value="inactive">Inactive (Draft)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="border-top pt-3 mt-4 text-end">
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary px-4 rounded-pill me-2">Cancel</a>
        <button type="submit" class="btn btn-action px-5"><i class="fa-solid fa-floppy-disk me-2"></i>Publish FAQ</button>
    </div>
</form>
@endsection
