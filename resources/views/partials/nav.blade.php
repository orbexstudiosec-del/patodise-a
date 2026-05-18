<header x-data="{ open: false }" class="sticky top-0 z-40 bg-ink-50/85 backdrop-blur border-b border-ink-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3 sm:gap-6">
        <a href="{{ route('home') }}" class="flex items-center shrink-0" aria-label="Pato Diseña">
            {{-- Logo oscuro (visible en modo claro) --}}
            <img src="{{ asset('images/logo-dark.svg') }}" alt="Pato Diseña" class="h-11 sm:h-14 md:h-16 lg:h-20 w-auto block dark:hidden" onerror="this.replaceWith(Object.assign(document.createElement('span'), {className:'text-lg font-semibold tracking-tight', textContent:'Pato Diseña'}))">
            {{-- Logo claro (visible en modo oscuro) --}}
            <img src="{{ asset('images/logo-light.svg') }}" alt="Pato Diseña" class="h-11 sm:h-14 md:h-16 lg:h-20 w-auto hidden dark:block">
        </a>

        <nav class="hidden md:flex items-center gap-7 text-sm">
            <a href="{{ route('home') }}" class="hover:text-ink-900 {{ request()->routeIs('home') ? 'text-ink-900' : '' }}">Inicio</a>
            <a href="{{ route('galleries.index') }}" class="hover:text-ink-900 {{ request()->routeIs('galleries.*') ? 'text-ink-900' : '' }}">Galerías</a>
            <a href="{{ route('services') }}" class="hover:text-ink-900 {{ request()->routeIs('services') ? 'text-ink-900' : '' }}">Servicios</a>
            <a href="{{ route('about') }}" class="hover:text-ink-900 {{ request()->routeIs('about') ? 'text-ink-900' : '' }}">Sobre mí</a>
            <a href="{{ route('contact') }}" class="hover:text-ink-900 {{ request()->routeIs('contact') ? 'text-ink-900' : '' }}">Contacto</a>
        </nav>

        <div class="flex items-center gap-2 sm:gap-3">
            {{-- Redes sociales --}}
            @php
                $navFb = setting('social_facebook', 'https://facebook.com/pato.molina04');
                $navIg = setting('social_instagram', 'https://instagram.com/patodisena.ec');
                $navWa = setting('social_whatsapp', 'https://wa.me/593968179682');
            @endphp
            @if ($navFb || $navIg || $navWa)
            <div class="hidden lg:flex items-center gap-1 mr-1 pr-3 border-r border-ink-200 text-ink-500">
                @if ($navFb)
                    <a href="{{ $navFb }}" target="_blank" rel="noopener" aria-label="Facebook"
                       class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-ink-100 hover:text-ink-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.75 8.43-4.91 8.43-9.93z"/></svg>
                    </a>
                @endif
                @if ($navIg)
                    <a href="{{ $navIg }}" target="_blank" rel="noopener" aria-label="Instagram"
                       class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-ink-100 hover:text-ink-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                    </a>
                @endif
                @if ($navWa)
                    <a href="{{ $navWa }}" target="_blank" rel="noopener" aria-label="WhatsApp"
                       class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-ink-100 hover:text-ink-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                @endif
            </div>
            @endif

            {{-- Toggle dark/light --}}
            <button type="button"
                    @click="$store.theme.toggle()"
                    :aria-label="$store.theme.isDark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                    :title="$store.theme.isDark ? 'Modo claro' : 'Modo oscuro'"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-ink-200 hover:border-ink-400 transition">
                {{-- Sol (visible en dark, click → light) --}}
                <svg x-show="$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                </svg>
                {{-- Luna (visible en light, click → dark) --}}
                <svg x-show="!$store.theme.isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <button type="button"
                    @click="$store.cart.refresh(); $store.cart.open = true"
                    aria-label="Abrir carrito"
                    class="relative inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-2 rounded-full border border-ink-200 hover:border-ink-400 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 5h13"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg>
                <span class="hidden md:inline">Carrito</span>
                <span x-show="$store.cart.count > 0" x-text="$store.cart.count"
                      class="absolute -top-1 -right-1 md:static inline-flex items-center justify-center min-w-5 h-5 rounded-full bg-ink-900 text-white dark:bg-white dark:text-ink-900 text-[11px] md:text-xs px-1"></span>
            </button>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex text-sm px-3 py-2 rounded-full bg-ink-900 text-white hover:bg-ink-700 dark:bg-white dark:text-ink-900 dark:hover:bg-ink-200">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">@csrf
                    <button class="text-sm text-ink-600 hover:text-ink-900">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm text-ink-600 hover:text-ink-900">Acceder</a>
            @endauth

            <button class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-full border border-ink-200" @click="open = !open" aria-label="Menú">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="md:hidden border-t border-ink-200 bg-ink-50">
        <nav class="px-6 py-4 flex flex-col gap-3 text-sm">
            <a href="{{ route('home') }}">Inicio</a>
            <a href="{{ route('galleries.index') }}">Galerías</a>
            <a href="{{ route('services') }}">Servicios</a>
            <a href="{{ route('about') }}">Sobre mí</a>
            <a href="{{ route('contact') }}">Contacto</a>
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-left">Salir</button></form>
            @else
                <a href="{{ route('login') }}">Acceder</a>
                <a href="{{ route('register') }}">Crear cuenta</a>
            @endauth

            {{-- Redes en mobile --}}
            <div class="flex items-center gap-2 pt-3 mt-1 border-t border-ink-200 text-ink-500">
                <a href="https://facebook.com/pato.molina04" target="_blank" rel="noopener" aria-label="Facebook" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-ink-200 hover:bg-ink-100 hover:text-ink-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.75 8.43-4.91 8.43-9.93z"/></svg>
                </a>
                <a href="https://instagram.com/patodisena.ec" target="_blank" rel="noopener" aria-label="Instagram" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-ink-200 hover:bg-ink-100 hover:text-ink-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                </a>
                <a href="https://wa.me/593968179682" target="_blank" rel="noopener" aria-label="WhatsApp" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-ink-200 hover:bg-ink-100 hover:text-ink-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                </a>
            </div>
        </nav>
    </div>
</header>
