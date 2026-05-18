@extends('admin.layout')
@section('title', 'Galerías')
@section('heading', 'Galerías')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3">
        <p class="text-sm text-ink-500">{{ $galleries->count() }} galerías</p>
        <a href="{{ route('admin.galleries.create') }}" class="bg-ink-900 text-white px-4 py-2 rounded-full text-sm hover:bg-ink-700 text-center whitespace-nowrap">+ Nueva galería</a>
    </div>

    <div class="bg-white border border-ink-200 rounded-2xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
            <thead class="bg-ink-50 text-ink-500 text-left">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3">Galería</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Fotos</th>
                    <th class="px-4 py-3">Pack</th>
                    <th class="px-4 py-3">Por foto</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach ($galleries as $g)
                    <tr class="hover:bg-ink-50">
                        <td class="px-4 py-3"><img src="{{ $g->cover_url }}" class="w-14 h-14 object-cover rounded-lg" alt=""></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-medium">{{ $g->name }}</p>
                                @if ($g->visibility === 'unlisted')
                                    <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded-full uppercase tracking-wide">No listada</span>
                                @elseif ($g->visibility === 'private')
                                    <span class="text-[10px] bg-red-100 text-red-800 px-1.5 py-0.5 rounded-full uppercase tracking-wide">Privada · contraseña</span>
                                @endif
                            </div>
                            <p class="text-xs text-ink-500">{{ $g->client_name ? $g->client_name . ' · ' : '' }}{{ $g->location }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $g->event_date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            {{ $g->photos_count }}
                            <a href="{{ route('admin.photos.create', ['gallery' => $g->id]) }}" class="block text-xs text-ink-900 hover:underline">+ Subir foto</a>
                        </td>
                        <td class="px-4 py-3">${{ number_format($g->full_price, 2) }}</td>
                        <td class="px-4 py-3">${{ number_format($g->per_photo_price, 2) }}</td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            @if ($g->isPublic())
                                <a href="{{ route('galleries.show', $g) }}" target="_blank" class="text-ink-500 text-sm">Ver</a>
                            @else
                                <a href="{{ $g->share_url }}" target="_blank" class="text-ink-500 text-sm" title="Abrir enlace privado">Ver</a>
                            @endif
                            <a href="{{ route('admin.galleries.edit', $g) }}" class="text-ink-900 text-sm ml-2">Editar</a>
                            <form method="POST" action="{{ route('admin.galleries.destroy', $g) }}" class="inline" onsubmit="return confirm('¿Eliminar esta galería?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm ml-2">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
