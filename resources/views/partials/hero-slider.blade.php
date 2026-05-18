@php
    $slides = $slides ?? collect();
    $intervalMs = max(2, (int) setting('slider_interval', 6)) * 1000;
@endphp

@if ($slides->count())
<section
    x-data="{
        active: 0,
        total: {{ $slides->count() }},
        timer: null,
        start() { if (this.total > 1) { this.stop(); this.timer = setInterval(() => this.next(), {{ $intervalMs }}); } },
        stop() { if (this.timer) { clearInterval(this.timer); this.timer = null; } },
        next() { this.active = (this.active + 1) % this.total; },
        prev() { this.active = (this.active - 1 + this.total) % this.total; },
        go(i) { this.active = i; this.start(); }
    }"
    x-init="start()"
    @mouseenter="stop()" @mouseleave="start()"
    @keydown.window.arrow-right.prevent="next(); start()"
    @keydown.window.arrow-left.prevent="prev(); start()"
    class="relative overflow-hidden bg-ink-900 text-white select-none">

    {{-- Contenedor con altura cinematográfica responsive --}}
    <div class="relative w-full h-[58vh] min-h-[380px] max-h-[760px] sm:min-h-[460px] md:h-[78vh] lg:h-[82vh]">
        @foreach ($slides as $i => $slide)
            <div
                :class="active === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 pointer-events-none z-0'"
                @if ($i !== 0) style="opacity:0;pointer-events:none" @endif
                class="absolute inset-0 transition-opacity duration-[900ms] ease-out">

                {{-- Imagen --}}
                <img src="{{ $slide->cover_url }}" alt="{{ $slide->name }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                     class="absolute inset-0 w-full h-full object-cover">

                {{-- Gradientes para legibilidad --}}
                <div class="absolute inset-0 bg-gradient-to-t from-ink-900 via-ink-900/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-ink-900/60 via-transparent to-transparent"></div>

                {{-- Contenido alineado al bottom-left --}}
                <div class="absolute inset-x-0 bottom-0">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-10 pb-20 sm:pb-24 md:pb-32 lg:pb-36 w-full">
                        @if ($slide->event_date || $slide->location)
                            <p class="uppercase tracking-[0.25em] sm:tracking-[0.3em] text-white/70 text-[10px] sm:text-[11px] md:text-xs">
                                @if ($slide->event_date) {{ $slide->event_date->translatedFormat('d \\d\\e F, Y') }} @endif
                                @if ($slide->event_date && $slide->location) · @endif
                                @if ($slide->location) {{ $slide->location }} @endif
                            </p>
                        @endif
                        <h2 class="font-display font-bold leading-[1.05] mt-2 sm:mt-3 max-w-4xl text-3xl sm:text-5xl md:text-6xl lg:text-7xl">
                            {{ $slide->name }}
                        </h2>
                        @if ($slide->description)
                            <p class="mt-3 sm:mt-4 text-white/80 max-w-2xl text-sm sm:text-base md:text-lg line-clamp-2">{{ $slide->description }}</p>
                        @endif
                        <div class="mt-5 sm:mt-7 flex flex-wrap gap-3 sm:gap-4 items-center">
                            <a href="{{ route('galleries.show', $slide) }}"
                               class="bg-white hover:bg-ink-200 text-ink-900 px-5 sm:px-6 py-2.5 sm:py-3 rounded-full text-sm font-semibold inline-flex items-center gap-2 transition">
                                Ver galería
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                            @if ($slide->full_price > 0)
                                <span class="text-xs sm:text-sm text-white/80">Pack desde <span class="font-bold text-white">${{ number_format($slide->full_price, 2) }}</span></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($slides->count() > 1)
        {{-- Flechas (más pequeñas en mobile, ocultas en xs) --}}
        <button type="button" @click="prev(); start()" aria-label="Anterior"
                class="hidden sm:flex absolute z-20 left-3 md:left-6 top-1/2 -translate-y-1/2 w-11 h-11 md:w-14 md:h-14 rounded-full bg-white/10 hover:bg-white/25 backdrop-blur-md border border-white/20 items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 md:w-6 md:h-6"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button type="button" @click="next(); start()" aria-label="Siguiente"
                class="hidden sm:flex absolute z-20 right-3 md:right-6 top-1/2 -translate-y-1/2 w-11 h-11 md:w-14 md:h-14 rounded-full bg-white/10 hover:bg-white/25 backdrop-blur-md border border-white/20 items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 md:w-6 md:h-6"><path d="M9 6l6 6-6 6"/></svg>
        </button>

        {{-- Barra inferior: contador + dots --}}
        <div class="absolute z-20 inset-x-0 bottom-0 pb-5 sm:pb-7 md:pb-10 px-4 sm:px-6 md:px-10">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 sm:gap-6">
                {{-- Contador --}}
                <div class="text-sm font-mono tabular-nums text-white/70 flex items-center gap-2">
                    <span class="text-white font-bold text-base" x-text="String(active + 1).padStart(2, '0')"></span>
                    <span class="w-8 h-px bg-white/30"></span>
                    <span>{{ str_pad($slides->count(), 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                {{-- Dots --}}
                <div class="flex items-center gap-2">
                    @foreach ($slides as $i => $_)
                        <button type="button" @click="go({{ $i }})" aria-label="Slide {{ $i + 1 }}"
                                :class="active === {{ $i }} ? 'bg-white w-10' : 'bg-white/40 hover:bg-white/70 w-2.5'"
                                class="h-2.5 rounded-full transition-all duration-500"></button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>
@endif
