@extends('layouts.app')

@section('title', 'Acceder · Pato Diseña')

@section('content')
    <section class="max-w-md mx-auto px-6 py-16">
        <h1 class="font-display text-3xl">Acceder</h1>
        <p class="text-ink-600 text-sm mt-1">Ingresa con tu cuenta para ver tus pedidos.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-8 bg-white border border-ink-200 rounded-2xl p-6 space-y-4">
            @csrf
            <label class="block text-sm">
                <span class="text-ink-600">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </label>
            <label class="block text-sm">
                <span class="text-ink-600">Contraseña</span>
                <input type="password" name="password" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" class="accent-black"> Recordarme
            </label>
            <button class="w-full bg-ink-900 text-white py-2.5 rounded-full text-sm hover:bg-ink-700">Entrar</button>
            <p class="text-center text-sm text-ink-500">¿Aún no tienes cuenta? <a href="{{ route('register') }}" class="text-ink-900">Regístrate</a></p>
        </form>
    </section>
@endsection
