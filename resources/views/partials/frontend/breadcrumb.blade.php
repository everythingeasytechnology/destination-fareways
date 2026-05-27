<!-- Breadcrumb Partial -->
@if(!empty($breadcrumbs))
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb custom-breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house me-1"></i> Home</a>
        </li>
        @foreach($breadcrumbs as $item)
            @if(!$loop->last)
                <li class="breadcrumb-item">
                    @if(!empty($item['url']))
                        <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                    @else
                        <span class="text-muted">{{ $item['title'] }}</span>
                    @endif
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $item['title'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif

<style>
/* Light Breadcrumb colors inside dark background parent containers */
.bg-navy .custom-breadcrumb .breadcrumb-item a,
.bg-dark .custom-breadcrumb .breadcrumb-item a,
.text-white .custom-breadcrumb .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8) !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}
.bg-navy .custom-breadcrumb .breadcrumb-item a:hover,
.bg-dark .custom-breadcrumb .breadcrumb-item a:hover,
.text-white .custom-breadcrumb .breadcrumb-item a:hover {
    color: #ffffff !important;
}
.bg-navy .custom-breadcrumb .breadcrumb-item.active,
.bg-dark .custom-breadcrumb .breadcrumb-item.active,
.text-white .custom-breadcrumb .breadcrumb-item.active {
    color: #ffffff !important;
    font-weight: 600;
}
.bg-navy .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before,
.bg-dark .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before,
.text-white .custom-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.5) !important;
}
</style>
