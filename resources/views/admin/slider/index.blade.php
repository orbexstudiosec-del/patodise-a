@extends('admin.layout')
@section('title', 'Slider del home')
@section('heading', 'Slider del home')

@section('content')
    <form method="POST" action="{{ route('admin.slider.update') }}" class="space-y-6 max-w-5xl">
        @csrf

        {{-- Configuración general --}}
        <section class="bg-white border border-ink-200 rounded-2xl p-6" x-data="{ enabled: {{ setting('slider_enabled', '1') === '0' ? 'false' : 'true' }} }">
            <div class="mb-4">
                <h2 class="font-semibold text-lg">Configuración</h2>
                <p class="text-xs text-ink-500">El slider grande que aparece en la página de inicio.</p>
            </div>
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="slider_enabled" value="0">
                    <input type="checkbox" name="slider_enabled" value="1" x-model="enabled" class="accent-black w-4 h-4">
                    <span class="text-sm font-medium">Mostrar slider en el home</span>
                </label>

                <label class="block text-sm" x-show="enabled" x-transition>
                    <span class="text-ink-600">Tiempo entre slides (segundos)</span>
                    <input type="number" name="slider_interval" min="2" max="30"
                           value="{{ old('slider_interval', setting('slider_interval', '6')) }}"
                           class="mt-1 w-32 border border-ink-200 rounded-lg px-3 py-2 text-sm">
                    <span class="text-xs text-ink-500 ml-2">recomendado: 5–8 s</span>
                    @error('slider_interval')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
            </div>
        </section>

        {{-- Lista de galerías --}}
        <section class="bg-white border border-ink-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-ink-200">
                <h2 class="font-semibold text-lg">Galerías disponibles</h2>
                <p class="text-xs text-ink-500">Marca las que quieres que aparezcan como diapositivas y define el orden (menor primero).</p>
            </div>

            @if ($galleries->isEmpty())
                <p class="px-6 py-10 text-center text-ink-500 text-sm">
                    No hay galerías públicas todavía. <a href="{{ route('admin.galleries.create') }}" class="text-ink-900 underline">Crea una</a> para empezar.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead class="bg-ink-50 text-ink-500 text-left">
                            <tr>
                                <th class="px-4 py-3 w-16">En slider</th>
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3">Galería</th>
                                <th class="px-4 py-3">Fotos</th>
                                <th class="px-4 py-3 w-28">Orden</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink-100">
                            @foreach ($galleries as $g)
                                <tr class="hover:bg-ink-50">
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="galleries[{{ $g->id }}][featured]" value="0">
                                        <input type="checkbox" name="galleries[{{ $g->id }}][featured]" value="1"
                                               @checked($g->is_featured) class="accent-black w-4 h-4">
                                    </td>
                                    <td class="px-4 py-3">
                                        <img src="{{ $g->cover_url }}" alt="" class="w-14 h-10 object-cover rounded">
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium">{{ $g->name }}</p>
                                        <p class="text-xs text-ink-500">
                                            @if ($g->event_date) {{ $g->event_date->format('d/m/Y') }} @endif
                                            @if ($g->event_date && $g->location) · @endif
                                            @if ($g->location) {{ $g->location }} @endif
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-ink-500">{{ $g->photos_count }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="galleries[{{ $g->id }}][sort_order]"
                                               value="{{ $g->sort_order }}" min="0" max="9999"
                                               class="w-20 border border-ink-200 rounded-lg px-2 py-1 text-sm">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="flex items-center gap-3 sticky bottom-0 bg-ink-100 border-t border-ink-200 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4">
            <button class="bg-ink-900 text-white px-6 py-2.5 rounded-full text-sm hover:bg-ink-700">Guardar cambios</button>
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-ink-500 hover:text-ink-900">Ver home →</a>
        </div>
    </form>
@endsection
