@extends('admin.layout')
@section('title', 'Fotografías')
@section('heading', 'Fotografías')

@section('content')
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <select name="gallery" onchange="this.form.submit()" class="bg-white border border-ink-200 rounded-full px-4 py-2 text-sm">
                <option value="">Todas las galerías</option>
                @foreach ($galleries as $g)
                    <option value="{{ $g->id }}" @selected(request()->integer('gallery') === $g->id)>{{ $g->name }}</option>
                @endforeach
            </select>
            <span class="text-sm text-ink-500">{{ $photos->total() }} fotografías</span>
        </form>
        <a href="{{ route('admin.photos.create') }}" class="bg-ink-900 text-white px-4 py-2 rounded-full text-sm hover:bg-ink-700 text-center whitespace-nowrap">+ Nueva fotografía</a>
    </div>

    <div class="bg-white border border-ink-200 rounded-2xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead class="bg-ink-50 text-ink-500 text-left">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3">Título</th>
                    <th class="px-4 py-3">Galería</th>
                    <th class="px-4 py-3">Precio</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach ($photos as $photo)
                    <tr class="hover:bg-ink-50">
                        <td class="px-4 py-3"><img src="{{ $photo->thumbnail_url }}" class="w-14 h-14 object-cover rounded-lg" alt=""></td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $photo->title }}</p>
                            <p class="text-xs text-ink-500">{{ $photo->location }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $photo->gallery?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            ${{ number_format($photo->effective_price, 2) }}
                            @if (is_null($photo->price))<span class="block text-xs text-ink-400">heredado</span>@endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($photo->is_published)
                                <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">Publicada</span>
                            @else
                                <span class="text-xs bg-ink-100 text-ink-600 px-2 py-1 rounded-full">Borrador</span>
                            @endif
                            @if ($photo->is_featured)
                                <span class="text-xs bg-ink-100 text-ink-900 px-2 py-1 rounded-full">Destacada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.photos.edit', $photo) }}" class="text-ink-900 text-sm">Editar</a>
                            <form method="POST" action="{{ route('admin.photos.destroy', $photo) }}" class="inline" onsubmit="return confirm('¿Eliminar esta fotografía?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 text-sm ml-2">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $photos->links() }}</div>
@endsection
