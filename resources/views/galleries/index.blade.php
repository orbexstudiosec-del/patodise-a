@extends('layouts.app')

@section('title', 'Galerías de eventos · Pato Diseña')

@section('content')
    <section class="bg-ink-100 border-b border-ink-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-12">
            <p class="text-ink-900 text-sm uppercase tracking-widest">Galerías</p>
            <h1 class="font-display text-2xl sm:text-3xl md:text-5xl mt-2">Eventos cubiertos</h1>
            <p class="mt-2 text-ink-600 max-w-2xl">Selecciona la galería de tu evento para ver las fotografías y comprar.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <form method="GET" class="flex flex-col md:flex-row gap-3 md:items-center mb-8">
            <div class="flex-1 flex items-center gap-2 bg-white border border-ink-200 rounded-full px-4 py-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 text-ink-400"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Buscar evento, lugar o fecha" class="w-full bg-transparent outline-none text-sm">
            </div>
            <button class="bg-ink-900 text-white rounded-full px-5 py-2.5 text-sm hover:bg-ink-700">Buscar</button>
        </form>

        @if ($galleries->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($galleries as $gallery)
                    @include('partials.gallery-card', ['gallery' => $gallery])
                @endforeach
            </div>
            <div class="mt-10">{{ $galleries->links() }}</div>
        @else
            <div class="text-center py-20">
                <p class="text-ink-500">No encontramos galerías con esa búsqueda.</p>
                <a href="{{ route('galleries.index') }}" class="mt-4 inline-block text-ink-900">Ver todas las galerías</a>
            </div>
        @endif
    </section>
@endsection
