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
