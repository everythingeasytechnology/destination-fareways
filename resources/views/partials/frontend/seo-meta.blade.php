@php
    $seo = $seoData ?? ($seo ?? null);
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
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $seo->og_title ?? $seo->meta_title ?? $seo->seo_title ?? ($settings->site_name ?? '') }}">
<meta property="og:description" content="{{ $seo->og_description ?? $seo->meta_description ?? $seo->seo_description ?? '' }}">
<meta property="og:image" content="{{ !empty($seo->og_image) ? asset('storage/'.$seo->og_image) : (!empty($settings->logo) ? asset('storage/'.$settings->logo) : 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200') }}">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $seo->twitter_title ?? $seo->meta_title ?? $seo->seo_title ?? ($settings->site_name ?? '') }}">
<meta name="twitter:description" content="{{ $seo->twitter_description ?? $seo->meta_description ?? $seo->seo_description ?? '' }}">
<meta name="twitter:image" content="{{ !empty($seo->twitter_image) ? asset('storage/'.$seo->twitter_image) : (!empty($seo->og_image) ? asset('storage/'.$seo->og_image) : 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1200') }}">

<!-- Schema Markups Loop -->
@if(!empty($schemas))
    @foreach($schemas as $schema)
        @if($schema->status === 'active')
            <!-- Structured Data Script: {{ $schema->schema_type }} -->
            <script type="application/ld+json">
            {!! $schema->schema_json !!}
            </script>
        @endif
    @endforeach
@endif

<!-- Direct Schema Markups embedded on pages, blogs, destinations -->
@if(!empty($seo->schema_markup))
    <!-- Structured Page Data -->
    <script type="application/ld+json">
    {!! $seo->schema_markup !!}
    </script>
@endif
@if(!empty($seo->faq_schema))
    <!-- FAQ page Structured Data -->
    <script type="application/ld+json">
    {!! $seo->faq_schema !!}
    </script>
@endif
@if(!empty($seo->breadcrumb_schema))
    <!-- Breadcrumb Structured Data -->
    <script type="application/ld+json">
    {!! $seo->breadcrumb_schema !!}
    </script>
@endif

<!-- Favicon Details -->
@if(!empty($settings->favicon))
    <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon) }}" type="image/x-icon">
@else
    <link rel="shortcut icon" href="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=32" type="image/x-icon">
@endif
