@extends('layouts.app')

@section('title', 'Pato Diseña — Fotografía de eventos')

@section('content')
    {{-- Hero slider --}}
    @if (setting('slider_enabled', '1') !== '0')
        @include('partials.hero-slider', ['slides' => $featuredGalleries->count() ? $featuredGalleries : $galleries])
    @endif

    {{-- Intro corto bajo el slider --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10 text-center">
        <p class="text-ink-900 text-sm uppercase tracking-widest">Fotografía & diseño</p>
        <h1 class="font-display text-2xl sm:text-3xl md:text-4xl mt-2">Cada evento, una galería.</h1>
        <p class="mt-3 text-ink-600 max-w-2xl mx-auto">Elige las fotos que quieras o llévate el pack completo del evento.</p>
    </section>

    {{-- Galerías destacadas --}}
    @if ($featuredGalleries->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-16">
        <div class="flex items-end justify-between mb-6 sm:mb-8 gap-3">
            <div>
                <p class="text-ink-900 text-sm uppercase tracking-widest">Eventos destacados</p>
                <h2 class="font-display text-3xl md:text-4xl mt-1">Galerías recientes</h2>
            </div>
            <a href="{{ route('galleries.index') }}" class="hidden sm:inline text-sm text-ink-600 hover:text-ink-900">Ver todas →</a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($featuredGalleries as $gallery)
                @include('partials.gallery-card', ['gallery' => $gallery])
            @endforeach
        </div>
    </section>
    @endif

    {{-- Cómo funciona --}}
    <section class="bg-ink-100 border-y border-ink-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-16">
            <h2 class="font-display text-3xl md:text-4xl text-center">¿Cómo funciona?</h2>
            <div class="grid md:grid-cols-3 gap-6 mt-10">
                @foreach ([
                    ['1', 'Entra a la galería del evento', 'Encuentra el pregón, desfile o ceremonia que estás buscando.'],
                    ['2', 'Elige tu foto o el pack completo', 'Compra una sola fotografía o llévate todas las del evento con un solo precio.'],
                    ['3', 'Recibe tus archivos', 'Descarga digital inmediata. También impresión fine art a pedido.'],
                ] as [$n, $title, $desc])
                    <div class="bg-white rounded-2xl p-6 border border-ink-200">
                        <span class="inline-flex w-10 h-10 items-center justify-center rounded-full bg-ink-900 text-white font-display">{{ $n }}</span>
                        <h3 class="font-semibold text-lg mt-4">{{ $title }}</h3>
                        <p class="text-ink-600 text-sm mt-2">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Todas las galerías --}}
    @if ($galleries->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-16">
        <div class="flex items-end justify-between mb-8">
            <h2 class="font-display text-3xl md:text-4xl">Más galerías</h2>
            <a href="{{ route('galleries.index') }}" class="text-sm text-ink-600 hover:text-ink-900">Ver más →</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($galleries as $g)
                @include('partials.gallery-card', ['gallery' => $g])
            @endforeach
        </div>
    </section>
    @endif

    {{-- Fotos destacadas sueltas --}}
    @if ($featuredPhotos->count())
    <section class="bg-ink-100 border-y border-ink-200">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <h2 class="font-display text-3xl md:text-4xl">Fotografías destacadas</h2>
            <p class="text-ink-600 mt-2">Selecciones individuales de las distintas galerías.</p>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($featuredPhotos as $photo)
                    @include('partials.photo-card', ['photo' => $photo])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="bg-ink-900 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 sm:py-16 text-center">
            <h2 class="font-display text-2xl sm:text-3xl md:text-4xl">¿Tienes un evento próximo?</h2>
            <p class="mt-3 text-ink-300">Hago cobertura fotográfica de pregones, desfiles, matrimonios, quinces y celebraciones corporativas.</p>
            <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 bg-white hover:bg-ink-200 text-ink-900 px-6 py-3 rounded-full font-medium">
                Solicita una cotización
            </a>
        </div>
    </section>
@endsection
