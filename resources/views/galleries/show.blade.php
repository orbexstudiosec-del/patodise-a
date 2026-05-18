@extends('layouts.app')

@section('title', $gallery->name . ' · Pato Diseña')

@section('content')
    {{-- Hero de la galería --}}
    <section class="relative bg-ink-900 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-40">
            <img src="{{ $gallery->cover_url }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-ink-900/30 via-ink-900/60 to-ink-900"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-14 sm:py-20 md:py-28">
            <a href="{{ route('galleries.index') }}" class="text-xs text-white hover:text-white inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                Volver a galerías
            </a>
            <p class="mt-3 uppercase tracking-[0.3em] text-white text-xs">
                @if ($gallery->event_date) {{ $gallery->event_date->translatedFormat('d \\d\\e F, Y') }} @endif
                @if ($gallery->location) · {{ $gallery->location }} @endif
            </p>
            <h1 class="font-display text-4xl md:text-6xl mt-3">{{ $gallery->name }}</h1>
            @if ($gallery->description)
                <p class="mt-4 max-w-2xl text-ink-200">{{ $gallery->description }}</p>
            @endif
            <p class="mt-4 text-sm text-ink-300">{{ $gallery->photos_count }} fotografías disponibles · Foto individual desde ${{ number_format($gallery->per_photo_price, 2) }}</p>
        </div>
    </section>

    {{-- Planes de compra --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-12">
        <h2 class="font-display text-xl sm:text-2xl md:text-3xl mb-5 sm:mb-6">Planes de compra</h2>
        <div class="grid md:grid-cols-2 gap-4 sm:gap-6">
            {{-- Plan: foto individual --}}
            <div class="bg-white border border-ink-200 rounded-3xl p-7">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase tracking-widest text-ink-500">Plan individual</span>
                    <span class="text-xs bg-ink-100 px-2 py-1 rounded-full">Más flexible</span>
                </div>
                <p class="font-display text-3xl mt-3">${{ number_format($gallery->per_photo_price, 2) }} <span class="text-base text-ink-500 font-sans">/ fotografía</span></p>
                <ul class="mt-5 space-y-2 text-sm text-ink-700">
                    <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-ink-900 shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Elige solo las fotos que quieras</li>
                    <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-ink-900 shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Descarga digital en alta resolución</li>
                    <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-ink-900 shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Opción de impresión fine art</li>
                </ul>
                <p class="mt-5 text-sm text-ink-500">Selecciona tu foto preferida abajo ↓</p>
            </div>

            {{-- Plan: galería completa --}}
            @if ($gallery->full_price > 0)
                @php
                    $individualTotal = $gallery->per_photo_price * $gallery->photos_count;
                    $savings = max(0, $individualTotal - $gallery->full_price);
                @endphp
                <div class="relative bg-ink-900 text-white rounded-3xl p-7 overflow-hidden">
                    <span class="absolute top-4 right-4 bg-ink-900 text-white text-xs px-3 py-1 rounded-full">Mejor valor</span>
                    <div class="flex items-center justify-between">
                        <span class="text-xs uppercase tracking-widest text-white">Pack completo</span>
                    </div>
                    <p class="font-display text-3xl mt-3">${{ number_format($gallery->full_price, 2) }} <span class="text-base text-ink-300 font-sans">/ galería entera</span></p>
                    @if ($savings > 0)
                        <p class="text-xs text-white mt-1">Ahorras ${{ number_format($savings, 2) }} vs comprar una por una</p>
                    @endif
                    <ul class="mt-5 space-y-2 text-sm text-ink-200">
                        <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-white shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Todas las {{ $gallery->photos_count }} fotografías del evento</li>
                        <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-white shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Descarga digital de alta resolución</li>
                        <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-white shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Acceso permanente al álbum</li>
                    </ul>
                    <form x-data method="POST" action="{{ route('cart.add-gallery', $gallery) }}"
                          @submit.prevent="$store.cart.addGallery($el.action)" class="mt-6">
                        @csrf
                        <button class="w-full bg-white hover:bg-ink-200 text-ink-900 py-3 rounded-full font-medium inline-flex items-center justify-center gap-2 disabled:opacity-60"
                                :disabled="$store.cart.loading">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 5h13"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg>
                            <span x-text="$store.cart.loading ? 'Añadiendo...' : 'Comprar pack completo'"></span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </section>

    {{-- Grid de fotos individuales --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="flex items-end justify-between mb-5 sm:mb-6 gap-3">
            <h2 class="font-display text-xl sm:text-2xl md:text-3xl">Fotografías del evento</h2>
            <span class="text-sm text-ink-500 whitespace-nowrap">{{ $photos->total() }} fotos</span>
        </div>

        @if ($photos->count())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
                @foreach ($photos as $photo)
                    @include('partials.photo-card', ['photo' => $photo, 'compact' => true])
                @endforeach
            </div>
            <div class="mt-10">{{ $photos->links() }}</div>
        @else
            <p class="text-center text-ink-500 py-12">Aún no hay fotos publicadas en esta galería.</p>
        @endif
    </section>
@endsection
