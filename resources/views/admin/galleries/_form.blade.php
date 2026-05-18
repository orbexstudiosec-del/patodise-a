@csrf
<div class="grid md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <label class="block text-sm">
            <span class="text-ink-600">Nombre de la galería *</span>
            <input name="name" value="{{ old('name', $gallery->name ?? '') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </label>
        <label class="block text-sm">
            <span class="text-ink-600">Descripción</span>
            <textarea name="description" rows="4" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">{{ old('description', $gallery->description ?? '') }}</textarea>
        </label>
        <div class="grid grid-cols-2 gap-4">
            <label class="block text-sm">
                <span class="text-ink-600">Fecha del evento</span>
                <input type="date" name="event_date" value="{{ old('event_date', isset($gallery) ? $gallery->event_date?->format('Y-m-d') : '') }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Lugar</span>
                <input name="location" value="{{ old('location', $gallery->location ?? '') }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <label class="block text-sm">
                <span class="text-ink-600">Precio pack completo (USD) *</span>
                <input type="number" step="0.01" name="full_price" value="{{ old('full_price', $gallery->full_price ?? '0.00') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                @error('full_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Precio por foto (USD) *</span>
                <input type="number" step="0.01" name="per_photo_price" value="{{ old('per_photo_price', $gallery->per_photo_price ?? '0.00') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                @error('per_photo_price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
        </div>
        <label class="block text-sm">
            <span class="text-ink-600">Orden de aparición</span>
            <input type="number" name="sort_order" value="{{ old('sort_order', $gallery->sort_order ?? 0) }}" class="mt-1 w-32 border border-ink-200 rounded-lg px-3 py-2">
        </label>

        {{-- Editor de formatos de venta --}}
        @php
            $defaults = \App\Services\CartService::FORMATS;
            $overrides = old('formats', $gallery->formats ?? []);
            $basePrice = (float) old('per_photo_price', $gallery->per_photo_price ?? 0);
        @endphp
        <div x-data="{ base: {{ $basePrice }} }" class="bg-ink-50 border border-ink-200 rounded-2xl p-4">
            <h3 class="font-semibold text-sm mb-1">Formatos de venta</h3>
            <p class="text-xs text-ink-500 mb-3">Activa los formatos disponibles y ajusta el multiplicador. El precio final se calcula como <span class="font-mono">precio por foto × multiplicador</span>.</p>
            <div class="space-y-2">
                @foreach ($defaults as $key => $def)
                    @php
                        $cfg = $overrides[$key] ?? [];
                        $isEnabled = (bool) ($cfg['enabled'] ?? ($overrides ? false : true));
                        $multiplier = (float) ($cfg['multiplier'] ?? $def['multiplier']);
                        $label = $cfg['label'] ?? $def['label'];
                    @endphp
                    <div x-data="{ enabled: {{ $isEnabled ? 'true' : 'false' }}, mult: {{ $multiplier }} }"
                         class="bg-white border border-ink-200 rounded-xl p-3">
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="formats[{{ $key }}][enabled]" value="0">
                            <input type="checkbox" name="formats[{{ $key }}][enabled]" value="1"
                                   x-model="enabled" class="accent-black">
                            <input type="text" name="formats[{{ $key }}][label]" value="{{ $label }}"
                                   :disabled="!enabled"
                                   class="flex-1 border border-ink-200 rounded-lg px-3 py-1.5 text-sm disabled:bg-ink-100 disabled:text-ink-400">
                        </div>
                        <div class="mt-2 flex items-center gap-3 text-xs">
                            <label class="text-ink-500">×</label>
                            <input type="number" step="0.1" min="0" name="formats[{{ $key }}][multiplier]"
                                   x-model.number="mult"
                                   :disabled="!enabled"
                                   class="w-20 border border-ink-200 rounded-lg px-2 py-1 disabled:bg-ink-100 disabled:text-ink-400">
                            <span class="text-ink-500">=</span>
                            <span class="font-semibold text-ink-900"
                                  x-text="enabled ? '$' + (base * mult).toFixed(2) : '—'"></span>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('formats')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-ink-50 border border-ink-200 rounded-2xl p-4">
            <label class="block text-sm font-medium mb-2">Imagen de portada</label>
            @if (isset($gallery) && $gallery->cover_image)
                <img src="{{ $gallery->cover_url }}" alt="" class="w-full h-48 object-cover rounded-xl mb-3">
            @endif
            <input type="file" name="cover" accept="image/*" class="text-sm">
            @error('cover')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-ink-500 mt-2">Si está vacío usa la primera foto de la galería.</p>
        </div>

        <div class="space-y-2 bg-ink-50 border border-ink-200 rounded-2xl p-4 text-sm">
            <label class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $gallery->is_published ?? true)) class="accent-black">
                Publicar galería
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $gallery->is_featured ?? false)) class="accent-black">
                Marcar como destacada
            </label>
        </div>

        {{-- Visibilidad / acceso --}}
        @php
            $currentVisibility = old('visibility', $gallery->visibility ?? \App\Models\Gallery::VISIBILITY_PUBLIC);
        @endphp
        <div x-data="{ visibility: '{{ $currentVisibility }}' }" class="bg-ink-50 border border-ink-200 rounded-2xl p-4 space-y-3">
            <h3 class="font-semibold text-sm">Visibilidad y acceso</h3>

            <div class="space-y-2">
                @foreach (\App\Models\Gallery::VISIBILITY_LABELS as $value => $label)
                    <label class="flex items-start gap-3 cursor-pointer border border-ink-200 rounded-xl p-3 has-[:checked]:border-ink-900 has-[:checked]:bg-white">
                        <input type="radio" name="visibility" value="{{ $value }}" x-model="visibility" class="accent-black mt-0.5">
                        <div class="text-xs">
                            <p class="font-medium text-sm">{{ $label }}</p>
                            <p class="text-ink-500 mt-0.5">
                                @if ($value === 'public') Aparece en /galerias y es indexable.
                                @elseif ($value === 'unlisted') Solo accesible mediante el enlace privado. No aparece en listados.
                                @else Requiere enlace + contraseña. Para entrega a clientes específicos.
                                @endif
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>

            <label class="block text-sm" x-show="visibility !== 'public'" x-transition>
                <span class="text-ink-600">Cliente / destinatario (interno)</span>
                <input name="client_name" value="{{ old('client_name', $gallery->client_name ?? '') }}" placeholder="Ej. Familia García" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2 text-sm">
            </label>

            <label class="block text-sm" x-show="visibility === 'private'" x-transition>
                <span class="text-ink-600">{{ isset($gallery) && $gallery->share_password ? 'Cambiar contraseña' : 'Contraseña' }}</span>
                <input type="text" name="share_password" placeholder="{{ isset($gallery) && $gallery->share_password ? 'Dejar vacío para mantener' : 'Mínimo 4 caracteres' }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2 text-sm">
                @if (isset($gallery) && $gallery->share_password)
                    <p class="text-xs text-ink-500 mt-1">Ya tiene una contraseña. Déjalo vacío para conservarla.</p>
                @endif
                @error('share_password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
        </div>

        {{-- Bloque compartir (solo si ya tiene token) --}}
        @if (isset($gallery) && $gallery->share_token)
            @php
                $shareUrl = $gallery->share_url;
                $waText = rawurlencode("Hola! Aquí están tus fotos de \"{$gallery->name}\":\n{$shareUrl}" . ($gallery->isPrivate() ? "\nContraseña: (te la envío aparte)" : ''));
            @endphp
            <div class="bg-ink-900 text-white rounded-2xl p-4 space-y-3" x-data="{ copied: false }">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-semibold text-sm">Enlace para compartir</h3>
                    <form method="POST" action="{{ route('admin.galleries.regenerate-token', $gallery) }}" onsubmit="return confirm('Esto invalidará el enlace actual. ¿Continuar?')">
                        @csrf @method('PATCH')
                        <button class="text-xs text-ink-300 hover:text-white underline">Regenerar enlace</button>
                    </form>
                </div>
                <div class="flex items-stretch gap-2">
                    <input type="text" readonly value="{{ $shareUrl }}" x-ref="link"
                           class="flex-1 bg-ink-800 text-white text-xs px-3 py-2 rounded-lg font-mono">
                    <button type="button" @click="$refs.link.select(); document.execCommand('copy'); copied = true; setTimeout(() => copied = false, 1500)"
                            class="bg-white text-ink-900 px-3 py-2 rounded-lg text-xs font-medium">
                        <span x-show="!copied">Copiar</span><span x-show="copied">¡Copiado!</span>
                    </button>
                </div>
                <a href="https://wa.me/?text={{ $waText }}" target="_blank" rel="noopener"
                   class="flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    Compartir por WhatsApp
                </a>
                <p class="text-xs text-ink-400">Solo quien tenga este enlace podrá ver la galería.</p>
            </div>
        @endif
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button class="bg-ink-900 text-white px-5 py-2.5 rounded-full text-sm hover:bg-ink-700">Guardar</button>
    <a href="{{ route('admin.galleries.index') }}" class="text-sm text-ink-500 hover:text-ink-900">Cancelar</a>
</div>
