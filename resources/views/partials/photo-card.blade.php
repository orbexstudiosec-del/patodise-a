@php $compact = $compact ?? false; @endphp
<div class="group block bg-white dark:bg-ink-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition border border-ink-200 relative">
    {{-- Imagen protegida con marca de agua --}}
    <a href="{{ route('photos.show', $photo) }}" class="photo-protected block relative {{ $compact ? 'aspect-square' : 'aspect-[4/3]' }} overflow-hidden bg-ink-100" oncontextmenu="return false;">
        <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" loading="lazy" draggable="false"
             class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105 select-none pointer-events-none">
        {{-- Marca de agua diagonal repetida --}}
        <div class="watermark-overlay"></div>
        @if ($photo->is_featured)
            <span class="absolute top-3 left-3 bg-ink-900 text-white text-xs px-2 py-1 rounded-full">Destacada</span>
        @endif
    </a>

    {{-- Botón rápido para añadir al carrito (formato digital) sin entrar al detalle --}}
    <form x-data method="POST" action="{{ route('cart.add-photo', $photo) }}"
          @submit.prevent="$store.cart.addPhoto($el.action, new FormData($el))"
          class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition">
        @csrf
        <input type="hidden" name="format" value="digital">
        <input type="hidden" name="quantity" value="1">
        <button type="submit"
                title="Añadir versión digital al carrito"
                aria-label="Añadir al carrito"
                :disabled="$store.cart.loading"
                class="w-10 h-10 rounded-full bg-white text-ink-900 shadow-lg hover:bg-ink-100 inline-flex items-center justify-center disabled:opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="w-4 h-4"><path d="M12 5v14M5 12h14"/></svg>
        </button>
    </form>

    <a href="{{ route('photos.show', $photo) }}" class="block p-4 flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="font-medium truncate">{{ $photo->title }}</p>
            <p class="text-xs text-ink-500 mt-0.5">{{ $photo->gallery?->name ?? 'Sin galería' }}@if ($photo->location) · {{ $photo->location }} @endif</p>
        </div>
        <span class="font-semibold text-ink-900 whitespace-nowrap">${{ number_format($photo->effective_price, 2) }}</span>
    </a>
</div>
