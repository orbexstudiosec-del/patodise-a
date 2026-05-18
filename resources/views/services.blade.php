@extends('layouts.app')

@section('title', 'Servicios · Pato Diseña')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-16">
        <p class="text-ink-900 uppercase tracking-widest text-sm">Servicios</p>
        <h1 class="font-display text-2xl sm:text-3xl md:text-5xl mt-2">¿Cómo puedo ayudarte?</h1>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mt-8 sm:mt-10">
            @foreach ([
                ['Fotografía editorial', 'Reportajes, productos y campañas con dirección de arte.', 'M4 6h16M4 12h10M4 18h16'],
                ['Retrato', 'Sesiones individuales o de marca personal en estudio o exteriores.', 'M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3z'],
                ['Diseño visual', 'Identidad, papelería, redes sociales y diseño web.', 'M12 4l8 4-8 4-8-4 8-4z'],
            ] as [$title, $desc, $iconPath])
                <div class="bg-white border border-ink-200 rounded-2xl p-6 hover:shadow-md transition">
                    <div class="w-10 h-10 rounded-xl bg-ink-100 text-ink-900 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="{{ $iconPath }}"/></svg>
                    </div>
                    <h2 class="font-semibold text-lg">{{ $title }}</h2>
                    <p class="text-ink-600 text-sm mt-2">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 sm:mt-12 bg-ink-900 text-white rounded-3xl p-6 sm:p-10 text-center">
            <h2 class="font-display text-xl sm:text-2xl md:text-3xl">¿Necesitas un presupuesto?</h2>
            <p class="text-ink-300 mt-2">Cada proyecto es único. Cuéntame qué tienes en mente.</p>
            <a href="{{ route('contact') }}" class="mt-5 inline-block bg-white hover:bg-ink-200 text-ink-900 px-6 py-3 rounded-full font-medium">Contáctame</a>
        </div>
    </section>
@endsection
