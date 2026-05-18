@extends('layouts.app')

@section('title', 'Galería privada · Pato Diseña')

@section('content')
    <section class="max-w-md mx-auto px-4 sm:px-6 py-16">
        <div class="text-center">
            <div class="mx-auto w-14 h-14 rounded-full bg-ink-900 text-white flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-6 h-6"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            </div>
            <p class="text-ink-900 uppercase tracking-widest text-xs mt-4">Galería privada</p>
            <h1 class="font-display text-3xl mt-2">{{ $gallery->name }}</h1>
            @if ($gallery->client_name)
                <p class="text-ink-600 mt-1">Para {{ $gallery->client_name }}</p>
            @endif
            <p class="text-ink-500 text-sm mt-3">Esta galería requiere una contraseña para acceder.</p>
        </div>

        <form method="POST" action="{{ route('private-gallery.unlock', $gallery->share_token) }}"
              class="mt-8 bg-white border border-ink-200 rounded-2xl p-6 space-y-4">
            @csrf
            <label class="block text-sm">
                <span class="text-ink-600">Contraseña</span>
                <input type="password" name="password" required autofocus
                       class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2.5 text-base">
                @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
            <button class="w-full bg-ink-900 text-white py-3 rounded-full text-sm font-medium hover:bg-ink-700">
                Entrar a la galería
            </button>
            <p class="text-xs text-ink-500 text-center">
                Si no tienes la contraseña, contacta a <a href="mailto:alvaromolina115@gmail.com" class="underline">alvaromolina115@gmail.com</a>
            </p>
        </form>
    </section>
@endsection
