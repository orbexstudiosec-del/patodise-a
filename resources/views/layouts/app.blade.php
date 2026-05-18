<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Favicon: patito del logo, auto blanco/negro según tema del navegador --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.svg') }}">
    @php
        $defaultTitle = setting('seo_title', 'Pato Diseña — Fotografía de eventos · Tulcán');
        $defaultDescription = setting('seo_description', 'Pato Molina · fotógrafo y diseñador en Tulcán, Ecuador. Compra fotos individuales o packs completos de pregones, desfiles, matrimonios y quinces.');
        $siteName = setting('site_name', 'Pato Diseña');
        $ogImagePath = setting('seo_og_image');
        $ogImageUrl = $ogImagePath ? \Illuminate\Support\Facades\Storage::disk('public')->url($ogImagePath) : null;
    @endphp
    <title>@yield('title', $defaultTitle)</title>
    <meta name="description" content="@yield('description', $defaultDescription)">
    @if ($k = setting('seo_keywords'))<meta name="keywords" content="{{ $k }}">@endif
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="es_EC">
    <meta property="og:title" content="@yield('title', $defaultTitle)">
    <meta property="og:description" content="@yield('description', $defaultDescription)">
    @if ($ogImageUrl)<meta property="og:image" content="{{ $ogImageUrl }}"><meta name="twitter:card" content="summary_large_image">@endif
    {{-- Aplicar tema antes del paint para evitar flash --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (!t) t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                if (t === 'dark') document.documentElement.classList.add('dark');
            } catch (e) {}
        })();
        window.__initialCart = { count: {{ app(\App\Services\CartService::class)->itemsCount() }}, subtotal: {{ app(\App\Services\CartService::class)->subtotal() }} };
    </script>
    {{-- CSS vars con URLs absolutas a assets de /public — robusto en dev (Vite) y prod --}}
    <style>
        :root {
            --watermark-url: url("{{ asset('images/logo-light.svg') }}");
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-ink-50 text-ink-900 antialiased">
    @include('partials.nav')

    <main class="flex-1">
        @if (session('status'))
            <div class="bg-ink-100 border-b border-ink-200 text-ink-900">
                <div class="max-w-7xl mx-auto px-6 py-3 text-sm">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.cart-drawer')
</body>
</html>
