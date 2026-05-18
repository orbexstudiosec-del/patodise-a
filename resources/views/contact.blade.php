@extends('layouts.app')

@section('title', 'Contacto · Pato Diseña')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-10 sm:py-16">
        <p class="text-ink-900 uppercase tracking-widest text-sm">Contacto</p>
        <h1 class="font-display text-2xl sm:text-3xl md:text-5xl mt-2">Cuéntame tu proyecto</h1>
        <p class="text-ink-600 mt-3 max-w-2xl">Responderé tu mensaje en menos de 48 horas. También puedes escribirme directo por WhatsApp.</p>

        <div class="grid lg:grid-cols-3 gap-8 mt-10">
            {{-- Formulario --}}
            <form method="POST" action="{{ route('contact.send') }}" class="lg:col-span-2 bg-white border border-ink-200 rounded-2xl p-6 space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="block text-sm">
                        <span class="text-ink-600">Nombre *</span>
                        <input name="name" value="{{ old('name') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block text-sm">
                        <span class="text-ink-600">Email *</span>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                        @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                </div>
                <label class="block text-sm">
                    <span class="text-ink-600">Asunto</span>
                    <input name="subject" value="{{ old('subject') }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
                <label class="block text-sm">
                    <span class="text-ink-600">Mensaje *</span>
                    <textarea name="message" rows="6" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
                <button class="bg-ink-900 text-white px-6 py-3 rounded-full text-sm hover:bg-ink-700">Enviar mensaje</button>
            </form>

            {{-- Datos de contacto --}}
            <aside class="bg-ink-900 text-white rounded-2xl p-6 h-fit space-y-5">
                <div>
                    <p class="text-xs uppercase tracking-widest text-white/60">Habla con</p>
                    <p class="font-display text-2xl mt-1">Pato Molina</p>
                    <p class="text-sm text-ink-300">Fotógrafo & diseñador · Tulcán</p>
                </div>

                <a href="https://wa.me/593968179682?text=Hola%20Pato%2C%20me%20interesa%20tu%20trabajo" target="_blank" rel="noopener"
                   class="flex items-center gap-3 bg-white text-ink-900 hover:bg-ink-200 px-4 py-3 rounded-xl text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    Escribir por WhatsApp
                </a>

                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 mt-0.5 text-white/60 shrink-0"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.33 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <div>
                            <p class="text-white/60 text-xs">Teléfono</p>
                            <a href="tel:+593968179682" class="hover:text-white/80">0968179682</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 mt-0.5 text-white/60 shrink-0"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <div>
                            <p class="text-white/60 text-xs">Email</p>
                            <a href="mailto:alvaromolina115@gmail.com" class="hover:text-white/80 break-all">alvaromolina115@gmail.com</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 mt-0.5 text-white/60 shrink-0"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                        <div>
                            <p class="text-white/60 text-xs">Instagram</p>
                            <a href="https://instagram.com/patodisena.ec" target="_blank" rel="noopener" class="hover:text-white/80">@patodisena.ec</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mt-0.5 text-white/60 shrink-0"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.75 8.43-4.91 8.43-9.93z"/></svg>
                        <div>
                            <p class="text-white/60 text-xs">Facebook</p>
                            <a href="https://facebook.com/pato.molina04" target="_blank" rel="noopener" class="hover:text-white/80">pato.molina04</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 mt-0.5 text-white/60 shrink-0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <div>
                            <p class="text-white/60 text-xs">Ubicación</p>
                            <p>Tulcán, Ecuador</p>
                        </div>
                    </li>
                </ul>
            </aside>
        </div>
    </section>
@endsection
