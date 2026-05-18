@extends('layouts.app')

@section('title', $photo->title . ' · Pato Diseña')

@section('content')
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        @if ($photo->gallery)
            <a href="{{ route('galleries.show', $photo->gallery) }}" class="text-sm text-ink-500 hover:text-ink-900 inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                <span class="truncate">Volver a {{ $photo->gallery->name }}</span>
            </a>
        @endif

        <div class="grid lg:grid-cols-5 gap-6 sm:gap-10 mt-6">
            <div class="lg:col-span-3">
                <div class="photo-protected relative rounded-3xl overflow-hidden bg-ink-900" oncontextmenu="return false;">
                    <img src="{{ route('photos.preview', [$photo, 'full']) }}" alt="{{ $photo->title }}" draggable="false"
                         class="w-full h-auto select-none pointer-events-none">
                    {{-- Marca de agua diagonal (versión reforzada en detalle) --}}
                    <div class="watermark-overlay watermark-overlay--strong"></div>
                    <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-2 text-[11px] text-white/80">
                        <span class="bg-black/50 backdrop-blur px-2 py-1 rounded">© patodisena.ec · vista previa</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                @if ($photo->gallery)
                    <a href="{{ route('galleries.show', $photo->gallery) }}" class="text-xs uppercase tracking-widest text-ink-900">{{ $photo->gallery->name }}</a>
                @endif
                <h1 class="font-display text-3xl md:text-4xl mt-2">{{ $photo->title }}</h1>
                <p class="text-2xl font-semibold mt-3">${{ number_format($photo->effective_price, 2) }}</p>
                <p class="text-xs text-ink-500 mt-1">Precio base · varía según formato</p>

                @if ($photo->description)
                    <p class="mt-6 text-ink-700 leading-relaxed">{{ $photo->description }}</p>
                @endif

                <dl class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    @if ($photo->location)
                        <div><dt class="text-ink-500">Lugar</dt><dd>{{ $photo->location }}</dd></div>
                    @endif
                    @if ($photo->captured_year)
                        <div><dt class="text-ink-500">Año</dt><dd>{{ $photo->captured_year }}</dd></div>
                    @endif
                </dl>

                <form x-data
                      method="POST"
                      action="{{ route('cart.add-photo', $photo) }}"
                      @submit.prevent="$store.cart.addPhoto($el.action, new FormData($el))"
                      class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium block mb-2">Formato</label>
                        <div class="grid grid-cols-1 gap-2">
                            @php $formats = $photo->gallery?->active_formats ?? \App\Services\CartService::FORMATS; @endphp
                            @foreach ($formats as $key => $cfg)
                                <label class="flex items-center justify-between gap-3 border border-ink-200 rounded-xl p-3 cursor-pointer has-[:checked]:border-ink-900 has-[:checked]:bg-ink-100">
                                    <span class="flex items-center gap-3">
                                        <input type="radio" name="format" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="accent-black">
                                        <span class="text-sm">{{ $cfg['label'] }}</span>
                                    </span>
                                    <span class="text-sm font-medium">${{ number_format($photo->effective_price * (float) $cfg['multiplier'], 2) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-sm font-medium" for="qty">Cantidad</label>
                        <input id="qty" type="number" name="quantity" value="1" min="1" max="20" class="w-20 border border-ink-200 rounded-lg px-3 py-2 text-sm">
                    </div>

                    <button class="w-full bg-ink-900 text-white py-3 rounded-full font-medium hover:bg-ink-700 inline-flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 5h13"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg>
                        Agregar al carrito
                    </button>
                </form>

                @if ($photo->gallery && $photo->gallery->full_price > 0)
                    <div class="mt-4 bg-ink-100 border border-ink-200 rounded-2xl p-4 text-sm">
                        <p class="font-medium">¿Vas a comprar varias fotos de este evento?</p>
                        <p class="text-ink-600 mt-1">Llévate las {{ $photo->gallery->photos()->published()->count() }} fotos del pack completo por <span class="font-semibold">${{ number_format($photo->gallery->full_price, 2) }}</span>.</p>
                        <form x-data method="POST" action="{{ route('cart.add-gallery', $photo->gallery) }}"
                              @submit.prevent="$store.cart.addGallery($el.action)" class="mt-3">
                            @csrf
                            <button class="text-ink-900 hover:underline font-medium text-sm">Añadir pack completo →</button>
                        </form>
                    </div>
                @endif

                <div class="mt-6 text-xs text-ink-500 space-y-1">
                    <p>· Descarga digital: archivo de alta resolución sin marca de agua.</p>
                    <p>· Impresión: papel fine art, firmada y numerada.</p>
                    <p>· Envíos a todo Ecuador en 5-7 días hábiles.</p>
                </div>
            </div>
        </div>

        @if ($related->count())
            <section class="mt-20">
                <h2 class="font-display text-2xl mb-6">Más de esta galería</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($related as $r)
                        @include('partials.photo-card', ['photo' => $r, 'compact' => true])
                    @endforeach
                </div>
            </section>
        @endif
    </section>
@endsection
