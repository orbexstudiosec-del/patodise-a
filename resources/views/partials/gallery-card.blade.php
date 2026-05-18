<a href="{{ route('galleries.show', $gallery) }}" class="group block bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition border border-ink-200">
    <div class="relative aspect-[4/3] overflow-hidden bg-ink-100">
        <img src="{{ $gallery->cover_url }}" alt="{{ $gallery->name }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-ink-900/70 via-transparent to-transparent"></div>
        @if ($gallery->is_featured)
            <span class="absolute top-3 left-3 bg-ink-900 text-white text-xs px-2 py-1 rounded-full">Destacada</span>
        @endif
        @if ($gallery->event_date)
            <span class="absolute top-3 right-3 bg-white/90 text-ink-800 text-xs px-2 py-1 rounded-full">{{ $gallery->event_date->format('d M Y') }}</span>
        @endif
        <div class="absolute bottom-0 inset-x-0 p-4 text-white">
            <p class="text-xs uppercase tracking-widest text-white">{{ $gallery->location ?? 'Galería' }}</p>
            <p class="font-display text-xl mt-1">{{ $gallery->name }}</p>
        </div>
    </div>
    <div class="px-4 py-3 flex items-center justify-between text-sm">
        <span class="text-ink-600">{{ $gallery->photos_count ?? $gallery->photos()->count() }} fotografías</span>
        <span class="text-ink-900 font-semibold">Pack ${{ number_format($gallery->full_price, 2) }}</span>
    </div>
</a>
