@extends('layouts.app')

@section('title', 'Crear cuenta · Pato Diseña')

@section('content')
    <section class="max-w-md mx-auto px-6 py-16">
        <h1 class="font-display text-3xl">Crear cuenta</h1>

        <form method="POST" action="{{ route('register') }}" class="mt-8 bg-white border border-ink-200 rounded-2xl p-6 space-y-4">
            @csrf
            <label class="block text-sm">
                <span class="text-ink-600">Nombre</span>
                <input name="name" value="{{ old('name') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Contraseña</span>
                <input type="password" name="password" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Confirmar contraseña</span>
                <input type="password" name="password_confirmation" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
            <button class="w-full bg-ink-900 text-white py-2.5 rounded-full text-sm hover:bg-ink-700">Crear cuenta</button>
            <p class="text-center text-sm text-ink-500">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-ink-900">Acceder</a></p>
        </form>
    </section>
@endsection
