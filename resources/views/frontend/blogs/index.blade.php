@extends('layouts.frontend')

@section('content')
@php
    $blogImageUrl = function ($path, $fallback) {
        if (empty($path)) {
            return $fallback;
        }

        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    };
@endphp
<!-- Blog Header Banner -->
<section class="bg-navy text-white py-5 mt-5">
    <div class="container py-4 text-center">
        <h1 class="display-5 fw-bold mb-3" data-aos="fade-up">Travel Tips & Guides</h1>
        <p class="lead text-muted-white mb-4" data-aos="fade-up" data-aos-delay="100">
            Discover cheap flights secrets, expert itineraries, and smart travel packing tips.
        </p>
        <div class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            @include('partials.frontend.breadcrumb')
        </div>
    </div>
</section>

<!-- Blog Filter / Search Toolbar -->
<section class="py-4 bg-light border-bottom border-light">
    <div class="container">
        <div class="row align-items-center g-3">
            <!-- Categories filter -->
            <div class="col-lg-8" data-aos="fade-up">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-muted small fw-semibold text-uppercase me-2">Categories:</span>
                    <a href="{{ route('blog.index') }}" 
                       class="btn btn-sm rounded-pill px-3 py-1.5 {{ !request('category') ? 'btn-navy' : 'btn-outline-navy' }}">
                        All Posts
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category]) }}" 
                           class="btn btn-sm rounded-pill px-3 py-1.5 {{ request('category') === $category ? 'btn-navy' : 'btn-outline-navy' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>
            <!-- Search bar -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <form action="{{ route('blog.index') }}" method="GET" class="position-relative">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="q" class="form-control rounded-pill py-2.5 ps-4 pe-5 text-navy fs-8 border-light shadow-sm" 
                           placeholder="Search travel posts..." value="{{ request('q') }}">
                    <button type="submit" class="btn border-0 position-absolute end-0 top-0 mt-1 me-2 text-navy" style="background: transparent;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Blog Posts Section -->
<section class="py-5 bg-white">
    <div class="container py-3">
        @php
            $showFeaturedBlog = !empty($featuredBlog)
                && !request('q')
                && !request('category')
                && (!request('page') || request('page') == 1)
                && $blogs->total() > 3;
        @endphp

        <!-- Featured Blog Card (Only on page 1 without filters/search query) -->
        @if($showFeaturedBlog)
            <div class="row mb-5" data-aos="fade-up">
                <div class="col-12">
                    <div class="card border-light shadow-sm overflow-hidden" style="border-radius: 16px;">
                        <div class="row g-0">
                            <!-- Image Left -->
                            <div class="col-lg-7 position-relative overflow-hidden" style="min-height: 380px;">
                                <img src="{{ $blogImageUrl($featuredBlog->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=1000&q=80&auto=format') }}" 
                                     alt="{{ $featuredBlog->title }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform" 
                                     style="object-position: center;">
                                <span class="position-absolute top-4 start-4 badge bg-navy text-gold text-uppercase fw-bold px-3 py-2 fs-8 shadow">
                                    Featured Article
                                </span>
                            </div>
                            <!-- Content Right -->
                            <div class="col-lg-5 p-4 p-lg-5 d-flex flex-column justify-content-center bg-softgray">
                                <div class="mb-3 d-flex align-items-center gap-2">
                                    <span class="badge text-uppercase text-muted border border-light bg-white rounded-pill px-2.5 py-1 fs-8">
                                        {{ $featuredBlog->category ?? 'Travel Guide' }}
                                    </span>
                                    @if(!empty($featuredBlog->read_time))
                                        <span class="text-muted small"><i class="fa-regular fa-clock text-gold"></i> {{ $featuredBlog->read_time }} mins read</span>
                                    @endif
                                </div>
                                
                                <h2 class="display-6 fw-bold text-navy mb-3">
                                    <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="text-navy text-decoration-none hover-gold-text">
                                        {{ $featuredBlog->title }}
                                    </a>
                                </h2>
                                
                                <p class="text-muted mb-4 lead" style="font-size: 0.95rem;">
                                    {{ $featuredBlog->excerpt }}
                                </p>
                                
                                <div class="d-flex align-items-center justify-content-between mt-auto border-top pt-4 border-light">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <img src="{{ $featuredBlog->author_image ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop' }}" 
                                             alt="{{ $featuredBlog->author_name }}" 
                                             class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <span class="d-block fw-semibold text-navy small">{{ $featuredBlog->author_name ?? 'Editorial Staff' }}</span>
                                            <span class="text-muted fs-8">{{ $featuredBlog->published_at ? $featuredBlog->published_at->format('M d, Y') : 'Recent' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('blog.show', $featuredBlog->slug) }}" class="btn btn-navy px-4 py-2">
                                        Read Article <i class="fa-solid fa-arrow-right ms-2 fs-9"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Mid grid separation bar -->
            <div class="border-bottom border-light pb-5 mb-5">
                <h3 class="h3 fw-bold text-navy mb-0">Latest Articles</h3>
            </div>
        @endif

        <!-- Blogs Grid -->
        @if($blogs->isEmpty())
            <div class="text-center py-5" data-aos="fade-up">
                <i class="fa-regular fa-newspaper fs-1 text-muted mb-3"></i>
                <h3>No Blog Posts Found</h3>
                <p class="text-muted">We couldn't find any articles matching your search query. Please try searching for something else.</p>
                <a href="{{ route('blog.index') }}" class="btn btn-navy px-4 py-2 mt-2">Clear Filters & Search</a>
            </div>
        @else
            <div class="row g-4 mb-5">
                @foreach($blogs as $blog)
                    @if(!$showFeaturedBlog || $blog->id !== $featuredBlog->id || request('q') || request('category') || (request('page') && request('page') > 1))
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                            <div class="card card-flight h-100 shadow-sm border-light">
                                <!-- Featured Image -->
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ $blogImageUrl($blog->featured_image, 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=800&q=80&auto=format') }}" 
                                         alt="{{ $blog->title }}" 
                                         class="w-100 h-100 object-fit-cover transition-transform" 
                                         loading="lazy">
                                    <span class="position-absolute top-3 start-3 badge bg-navy text-gold text-uppercase fw-bold px-2.5 py-1.5 fs-8 shadow">
                                        {{ $blog->category ?? 'Travel' }}
                                    </span>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body p-4 d-flex flex-column">
                                    @if(!empty($blog->read_time))
                                        <div class="text-muted fs-8 mb-2 d-flex align-items-center gap-1.5">
                                            <i class="fa-regular fa-clock text-gold"></i>
                                            <span>{{ $blog->read_time }} mins read</span>
                                        </div>
                                    @endif

                                    <h4 class="h5 fw-bold text-navy mb-2 line-clamp-2">
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-navy text-decoration-none hover-gold-text">
                                            {{ $blog->title }}
                                        </a>
                                    </h4>

                                    <p class="card-text text-muted small flex-grow-1 mb-4 line-clamp-3">
                                        {{ $blog->excerpt }}
                                    </p>

                                    <!-- Author and date footer -->
                                    <div class="d-flex align-items-center justify-content-between mt-auto border-top pt-3 border-light">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $blogImageUrl($blog->author_image, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop') }}" 
                                                 alt="{{ $blog->author_name }}" 
                                                 class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                            <div>
                                                <span class="d-block fw-semibold text-navy fs-8">{{ $blog->author_name ?? 'Staff' }}</span>
                                                <span class="text-muted fs-9">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Recent' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-outline-navy btn-sm px-3 rounded-pill py-1.5 fs-8">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center" data-aos="fade-up">
                {{ $blogs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>
@endsection
