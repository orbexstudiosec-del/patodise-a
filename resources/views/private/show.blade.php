@extends('layouts.app')

@section('title', $gallery->name . ' · Galería privada · Pato Diseña')

@section('content')
    {{-- Banner aviso de galería privada --}}
    <div class="bg-ink-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 text-xs flex flex-wrap items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3.5 h-3.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <span>Estás viendo una galería {{ $gallery->isPrivate() ? 'privada' : 'no listada' }}{{ $gallery->client_name ? ' · ' . $gallery->client_name : '' }}</span>
        </div>
    </div>

    {{-- Hero --}}
    <section class="relative bg-ink-900 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-40">
            <img src="{{ $gallery->cover_url }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-ink-900/30 via-ink-900/60 to-ink-900"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-14 sm:py-20 md:py-28">
            <p class="uppercase tracking-[0.3em] text-white text-xs">
                @if ($gallery->event_date) {{ $gallery->event_date->translatedFormat('d \\d\\e F, Y') }} @endif
                @if ($gallery->event_date && $gallery->location) · @endif
                @if ($gallery->location) {{ $gallery->location }} @endif
            </p>
            <h1 class="font-display text-3xl sm:text-4xl md:text-6xl mt-3">{{ $gallery->name }}</h1>
            @if ($gallery->description)
                <p class="mt-4 max-w-2xl text-ink-200">{{ $gallery->description }}</p>
            @endif
            <p class="mt-4 text-sm text-ink-300">{{ $gallery->photos_count }} fotografías · Desde ${{ number_format($gallery->per_photo_price, 2) }} c/u</p>
        </div>
    </section>

    {{-- Planes (igual que galería pública) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-12">
        <h2 class="font-display text-xl sm:text-2xl md:text-3xl mb-5 sm:mb-6">Planes de compra</h2>
        <div class="grid md:grid-cols-2 gap-4 sm:gap-6">
            <div class="bg-white border border-ink-200 rounded-3xl p-7">
                <p class="text-xs uppercase tracking-widest text-ink-500">Plan individual</p>
                <p class="font-display text-3xl mt-3">${{ number_format($gallery->per_photo_price, 2) }} <span class="text-base text-ink-500 font-sans">/ fotografía</span></p>
                <ul class="mt-5 space-y-2 text-sm text-ink-700">
                    <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-ink-900 shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Elige solo las que quieras</li>
                    <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-ink-900 shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Descarga digital alta resolución</li>
                </ul>
            </div>

            @if ($gallery->full_price > 0)
                @php
                    $individualTotal = $gallery->per_photo_price * $gallery->photos_count;
                    $savings = max(0, $individualTotal - $gallery->full_price);
                @endphp
                <div class="relative bg-ink-900 text-white rounded-3xl p-7 overflow-hidden">
                    <span class="absolute top-4 right-4 bg-white text-ink-900 text-xs px-3 py-1 rounded-full">Mejor valor</span>
                    <p class="text-xs uppercase tracking-widest text-white">Pack completo</p>
                    <p class="font-display text-3xl mt-3">${{ number_format($gallery->full_price, 2) }} <span class="text-base text-ink-300 font-sans">/ galería entera</span></p>
                    @if ($savings > 0)
                        <p class="text-xs text-white mt-1">Ahorras ${{ number_format($savings, 2) }} vs comprar una por una</p>
                    @endif
                    <ul class="mt-5 space-y-2 text-sm text-ink-200">
                        <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-white shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Todas las {{ $gallery->photos_count }} fotografías</li>
                        <li class="flex gap-2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4 text-white shrink-0 mt-0.5"><path d="M5 13l4 4L19 7"/></svg> Descarga digital alta resolución</li>
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

    {{-- Grid fotos --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <div class="flex items-end justify-between mb-5 sm:mb-6 gap-3">
            <h2 class="font-display text-xl sm:text-2xl md:text-3xl">Fotografías</h2>
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
            <p class="text-center text-ink-500 py-12">Esta galería aún no tiene fotos publicadas.</p>
        @endif
    </section>
@endsection
