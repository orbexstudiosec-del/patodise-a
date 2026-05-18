<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') · Pato Diseña</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-ink-100 text-ink-900" x-data="{ menu: false }">
    {{-- Backdrop mobile --}}
    <div x-show="menu" x-transition.opacity.duration.200ms
         @click="menu = false"
         class="fixed inset-0 bg-black/50 z-30 md:hidden" x-cloak></div>

    <div class="flex min-h-screen">
        {{-- Sidebar: oculto en mobile, slide-in al abrir el menú --}}
        <aside
            class="fixed md:sticky md:top-0 inset-y-0 left-0 z-40 w-64 md:w-60 bg-ink-900 text-ink-200 flex flex-col h-screen transform transition-transform duration-300 ease-out md:translate-x-0"
            :class="menu ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
            <div class="p-5 border-b border-ink-700 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <img src="{{ asset('images/logo-light.svg') }}" alt="Pato Diseña" class="w-full max-w-[170px] h-auto" onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'inline-flex w-8 h-8 items-center justify-center rounded-full bg-white text-ink-900 text-sm font-bold',textContent:'P'}))">
                    <span class="block mt-1 text-[10px] uppercase tracking-widest text-ink-400">Panel admin</span>
                </a>
                <button @click="menu = false" class="md:hidden w-9 h-9 rounded-full hover:bg-ink-800 inline-flex items-center justify-center" aria-label="Cerrar menú">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="flex-1 p-3 text-sm space-y-1 overflow-y-auto">
                @php
                    $nav = [
                        ['admin.dashboard', 'Dashboard'],
                        ['admin.galleries.index', 'Galerías'],
                        ['admin.photos.index', 'Fotografías'],
                        ['admin.orders.index', 'Pedidos'],
                    ];
                @endphp
                @foreach ($nav as [$route, $label])
                    <a href="{{ route($route) }}" @click="menu = false"
                       class="block px-3 py-2 rounded-lg hover:bg-ink-800 {{ request()->routeIs($route) || request()->routeIs(str_replace('.index','.*',$route)) ? 'bg-ink-800 text-white' : '' }}">{{ $label }}</a>
                @endforeach

                <div class="border-t border-ink-700 my-3"></div>
                <a href="{{ route('admin.slider.edit') }}" @click="menu = false"
                   class="block px-3 py-2 rounded-lg hover:bg-ink-800 {{ request()->routeIs('admin.slider.*') ? 'bg-ink-800 text-white' : '' }}">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="8" cy="20" r="1"/><circle cx="12" cy="20" r="1"/><circle cx="16" cy="20" r="1"/></svg>
                        Slider
                    </span>
                </a>
                <a href="{{ route('admin.settings.edit') }}" @click="menu = false"
                   class="block px-3 py-2 rounded-lg hover:bg-ink-800 {{ request()->routeIs('admin.settings.*') ? 'bg-ink-800 text-white' : '' }}">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Configurar
                    </span>
                </a>
            </nav>
            <div class="p-3 border-t border-ink-700">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-xs text-ink-400 hover:text-white">← Volver al sitio</a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="block w-full text-left px-3 py-2 text-xs text-ink-400 hover:text-white">Salir</button>
                </form>
            </div>
        </aside>

        <main class="flex-1 min-w-0">
            {{-- Topbar --}}
            <header class="bg-white border-b border-ink-200 px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3 sticky top-0 z-20">
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="menu = true" class="md:hidden w-9 h-9 rounded-lg border border-ink-200 inline-flex items-center justify-center shrink-0" aria-label="Abrir menú">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-semibold text-base sm:text-lg truncate">@yield('heading', 'Admin')</h1>
                </div>
                <div class="hidden sm:block text-sm text-ink-500 truncate">{{ auth()->user()->name ?? '' }}</div>
            </header>

            @if (session('status'))
                <div class="bg-ink-100 border-b border-ink-200 px-4 sm:px-6 py-3 text-sm text-ink-900">{{ session('status') }}</div>
            @endif

            <div class="p-4 sm:p-6">
                @yield('content')
            </div>
        </main>
    </div>
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
