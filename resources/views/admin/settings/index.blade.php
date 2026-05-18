@extends('admin.layout')
@section('title', 'Configuración')
@section('heading', 'Configurar sitio')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6 max-w-5xl">
        @csrf

        {{-- SEO --}}
        <section class="bg-white border border-ink-200 rounded-2xl p-6">
            <div class="mb-4">
                <h2 class="font-semibold text-lg">SEO</h2>
                <p class="text-xs text-ink-500">Cómo aparece tu sitio en Google y al compartir en redes.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <label class="block text-sm md:col-span-2">
                    <span class="text-ink-600">Título por defecto <span class="text-ink-400">(máx 60 caracteres recomendado)</span></span>
                    <input type="text" name="seo_title" maxlength="80" value="{{ old('seo_title', setting('seo_title')) }}"
                           placeholder="Pato Diseña — Fotografía de eventos · Tulcán"
                           class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                    @error('seo_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm md:col-span-2">
                    <span class="text-ink-600">Descripción por defecto <span class="text-ink-400">(máx 160 recomendado)</span></span>
                    <textarea name="seo_description" rows="2" maxlength="200"
                              placeholder="Pato Molina · fotógrafo en Tulcán. Compra fotos individuales o packs completos."
                              class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">{{ old('seo_description', setting('seo_description')) }}</textarea>
                    @error('seo_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm md:col-span-2">
                    <span class="text-ink-600">Palabras clave <span class="text-ink-400">(separadas por comas)</span></span>
                    <input type="text" name="seo_keywords" value="{{ old('seo_keywords', setting('seo_keywords')) }}"
                           placeholder="fotografía, eventos, pregón, Tulcán, matrimonios, quinces"
                           class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>

                {{-- OG Image --}}
                <div class="md:col-span-2 bg-ink-50 border border-ink-200 rounded-xl p-4">
                    <label class="block text-sm font-medium">Imagen para compartir (Open Graph) <span class="text-ink-400 text-xs">(1200×630 ideal · máx 5 MB)</span></label>
                    @php $og = setting('seo_og_image'); @endphp
                    @if ($og)
                        <div class="mt-3 flex items-center gap-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($og) }}" alt="" class="w-32 h-20 object-cover rounded">
                            <label class="text-xs text-red-600 inline-flex items-center gap-1.5">
                                <input type="checkbox" name="remove_og_image" value="1" class="accent-black"> Eliminar
                            </label>
                        </div>
                    @endif
                    <input type="file" name="og_image" accept="image/*" class="mt-3 text-sm">
                    @error('og_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        {{-- Identidad / marca --}}
        <section class="bg-white border border-ink-200 rounded-2xl p-6">
            <div class="mb-4">
                <h2 class="font-semibold text-lg">Identidad de marca</h2>
                <p class="text-xs text-ink-500">Datos que aparecen en footer, copyright y cabecera.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <label class="block text-sm">
                    <span class="text-ink-600">Nombre del sitio</span>
                    <input type="text" name="site_name" value="{{ old('site_name', setting('site_name')) }}"
                           placeholder="Pato Diseña" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
                <label class="block text-sm">
                    <span class="text-ink-600">Dueño / autor</span>
                    <input type="text" name="business_owner" value="{{ old('business_owner', setting('business_owner')) }}"
                           placeholder="Pato Molina" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
                <label class="block text-sm md:col-span-2">
                    <span class="text-ink-600">Tagline / subtítulo</span>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', setting('site_tagline')) }}"
                           placeholder="Fotografía de eventos y diseño visual" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
                <label class="block text-sm">
                    <span class="text-ink-600">Ciudad / base</span>
                    <input type="text" name="business_city" value="{{ old('business_city', setting('business_city')) }}"
                           placeholder="Tulcán, Ecuador" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
            </div>
        </section>

        {{-- Contacto --}}
        <section class="bg-white border border-ink-200 rounded-2xl p-6">
            <div class="mb-4">
                <h2 class="font-semibold text-lg">Contacto</h2>
                <p class="text-xs text-ink-500">Aparece en footer, página de contacto y header.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <label class="block text-sm">
                    <span class="text-ink-600">Teléfono / WhatsApp</span>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', setting('contact_phone')) }}"
                           placeholder="0968179682" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                    @error('contact_phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm">
                    <span class="text-ink-600">Email</span>
                    <input type="email" name="contact_email" value="{{ old('contact_email', setting('contact_email')) }}"
                           placeholder="hola@dominio.com" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                    @error('contact_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
            </div>
        </section>

        {{-- Redes sociales --}}
        <section class="bg-white border border-ink-200 rounded-2xl p-6">
            <div class="mb-4">
                <h2 class="font-semibold text-lg">Redes sociales</h2>
                <p class="text-xs text-ink-500">URLs completas. Si dejas en blanco, el icono desaparece del sitio.</p>
            </div>
            <div class="space-y-3">
                <label class="block text-sm">
                    <span class="text-ink-600 inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.75 8.43-4.91 8.43-9.93z"/></svg>
                        Facebook
                    </span>
                    <input type="url" name="social_facebook" value="{{ old('social_facebook', setting('social_facebook')) }}"
                           placeholder="https://facebook.com/usuario" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                    @error('social_facebook')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block text-sm">
                    <span class="text-ink-600 inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                        Instagram
                    </span>
                    <input type="url" name="social_instagram" value="{{ old('social_instagram', setting('social_instagram')) }}"
                           placeholder="https://instagram.com/usuario" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
                <label class="block text-sm">
                    <span class="text-ink-600 inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        WhatsApp <span class="text-xs text-ink-400">(usa formato wa.me/593XXXXXXXXX)</span>
                    </span>
                    <input type="url" name="social_whatsapp" value="{{ old('social_whatsapp', setting('social_whatsapp')) }}"
                           placeholder="https://wa.me/593968179682" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                </label>
            </div>
        </section>

        <div class="flex items-center gap-3 sticky bottom-0 bg-ink-100 border-t border-ink-200 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4">
            <button class="bg-ink-900 text-white px-6 py-2.5 rounded-full text-sm hover:bg-ink-700">Guardar configuración</button>
            <p class="text-xs text-ink-500">Los cambios se aplican inmediatamente.</p>
        </div>
    </form>
@endsection
