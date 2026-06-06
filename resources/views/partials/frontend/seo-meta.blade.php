@php
    $seo = $seoData ?? ($seo ?? null);

    /**
     * Resolve an image path to a full URL.
     * - Already absolute (http/https): returned as-is (external CDN, Unsplash, etc.)
     * - Relative path: prefixed with storage URL.
     * - Empty: returns $fallback.
     */
    $resolveImage = function (?string $img, ?string $fallback = null): ?string {
        if (empty($img)) return $fallback;
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }
        return asset('storage/' . ltrim($img, '/'));
    };

    $fallbackImage = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200';

    $ogImage = $resolveImage($seo->og_image ?? null)
        ?? $resolveImage($settings->logo ?? null)
        ?? $fallbackImage;

    $twitterImage = $resolveImage($seo->twitter_image ?? null)
        ?? $resolveImage($seo->og_image ?? null)
        ?? $ogImage;
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- SEO Core Elements -->
<title>{{ $seo->meta_title ?? $seo->seo_title ?? ($settings->site_name ?? config('app.name')) }}</title>
<meta name="description" content="{{ $seo->meta_description ?? $seo->seo_description ?? ($settings->tagline ?? '') }}">
<meta name="keywords" content="{{ $seo->meta_keywords ?? $seo->seo_keywords ?? '' }}">
<link rel="canonical" href="{{ $seo->canonical_url ?? url()->current() }}">
<meta name="robots" content="{{ $seo->robots_tag ?? 'index, follow' }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:locale" content="en_US">
<meta property="og:url" content="{{ $seo->canonical_url ?? url()->current() }}">
<meta property="og:title" content="{{ $seo->og_title ?? $seo->meta_title ?? $seo->seo_title ?? ($settings->site_name ?? '') }}">
<meta property="og:description" content="{{ $seo->og_description ?? $seo->meta_description ?? $seo->seo_description ?? '' }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $seo->og_title ?? $seo->meta_title ?? ($settings->site_name ?? '') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="{{ $settings->site_name ?? config('app.name') }}">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $seo->twitter_title ?? $seo->meta_title ?? $seo->seo_title ?? ($settings->site_name ?? '') }}">
<meta name="twitter:description" content="{{ $seo->twitter_description ?? $seo->meta_description ?? $seo->seo_description ?? '' }}">
<meta name="twitter:image" content="{{ $twitterImage }}">

<!-- Global Structured Data (Organization + WebSite) — injected on every page -->
@if(!empty($globalSchemas))
    @foreach($globalSchemas as $schema)
        <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endforeach
@endif

<!-- Admin-configured schema settings per page -->
@if(!empty($schemas))
    @foreach($schemas as $schema)
        @if($schema->status === 'active')
            <script type="application/ld+json">
            {!! $schema->schema_json !!}
            </script>
        @endif
    @endforeach
@endif

<!-- Inline schema from page/blog/destination model -->
@if(!empty($seo->schema_markup))
    @if(str_starts_with(ltrim($seo->schema_markup), '<script'))
        {!! $seo->schema_markup !!}
    @else
        <script type="application/ld+json">
        {!! $seo->schema_markup !!}
        </script>
    @endif
@endif
@if(!empty($seo->faq_schema))
    @if(str_starts_with(ltrim($seo->faq_schema), '<script'))
        {!! $seo->faq_schema !!}
    @else
        <script type="application/ld+json">
        {!! $seo->faq_schema !!}
        </script>
    @endif
@endif
@if(!empty($seo->breadcrumb_schema))
    @if(str_starts_with(ltrim($seo->breadcrumb_schema), '<script'))
        {!! $seo->breadcrumb_schema !!}
    @else
        <script type="application/ld+json">
        {!! $seo->breadcrumb_schema !!}
        </script>
    @endif
@endif

<!-- Favicon -->
@if(!empty($settings->favicon))
    <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}" type="image/x-icon">
@else
    <link rel="shortcut icon" href="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=32" type="image/x-icon">
@endif
