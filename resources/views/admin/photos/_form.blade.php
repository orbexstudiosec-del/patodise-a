@csrf
<div class="grid md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <label class="block text-sm">
            <span class="text-ink-600">Título *</span>
            <input name="title" value="{{ old('title', $photo->title ?? '') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </label>
        <label class="block text-sm">
            <span class="text-ink-600">Galería</span>
            <select name="gallery_id" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                <option value="">Sin galería</option>
                @foreach ($galleries as $g)
                    <option value="{{ $g->id }}" @selected(old('gallery_id', $photo->gallery_id ?? ($preselected ?? null)) == $g->id)>{{ $g->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="block text-sm">
            <span class="text-ink-600">Descripción</span>
            <textarea name="description" rows="5" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">{{ old('description', $photo->description ?? '') }}</textarea>
        </label>
        <div class="grid grid-cols-2 gap-4">
            <label class="block text-sm">
                <span class="text-ink-600">Precio especial (opcional)</span>
                <input type="number" step="0.01" name="price" value="{{ old('price', $photo->price ?? '') }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                <span class="text-xs text-ink-500">Si está vacío usa el precio por foto de la galería.</span>
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Stock</span>
                <input type="number" name="stock" value="{{ old('stock', $photo->stock ?? 99) }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Lugar</span>
                <input name="location" value="{{ old('location', $photo->location ?? '') }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Año</span>
                <input type="number" name="captured_year" value="{{ old('captured_year', $photo->captured_year ?? date('Y')) }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-ink-50 border border-ink-200 rounded-2xl p-4">
            <label class="block text-sm font-medium mb-2">Imagen {{ isset($photo) && $photo->image_path ? '(reemplazar)' : '*' }}</label>
            @if (isset($photo) && $photo->image_path)
                <img src="{{ $photo->image_url }}" alt="" class="w-full h-48 object-cover rounded-xl mb-3">
            @endif
            <input type="file" name="image" accept="image/*" class="text-sm">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-ink-500 mt-2">JPG/PNG/WebP · máx 10 MB</p>
        </div>

        <div class="space-y-2 bg-ink-50 border border-ink-200 rounded-2xl p-4 text-sm">
            <label class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $photo->is_published ?? true)) class="accent-black">
                Publicar en la galería
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $photo->is_featured ?? false)) class="accent-black">
                Marcar como destacada
            </label>
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button class="bg-ink-900 text-white px-5 py-2.5 rounded-full text-sm hover:bg-ink-700">Guardar</button>
    <a href="{{ route('admin.photos.index') }}" class="text-sm text-ink-500 hover:text-ink-900">Cancelar</a>
</div>
