@php
    $siteName = setting('site_name', 'Pato Diseña');
    $owner = setting('business_owner', 'Pato Molina');
    $tagline = setting('site_tagline', 'Fotografía de eventos y diseño visual');
    $city = setting('business_city', 'Tulcán, Ecuador');
    $phone = setting('contact_phone', '0968179682');
    $email = setting('contact_email', 'alvaromolina115@gmail.com');
    $waUrl = setting('social_whatsapp', 'https://wa.me/593968179682');
    $fbUrl = setting('social_facebook', 'https://facebook.com/pato.molina04');
    $igUrl = setting('social_instagram', 'https://instagram.com/patodisena.ec');
@endphp
<footer class="border-t border-ink-200 bg-ink-900 text-ink-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-12 grid sm:grid-cols-2 md:grid-cols-3 gap-8 sm:gap-10 text-sm">
        <div>
            <img src="{{ asset('images/logo-light.svg') }}" alt="{{ $siteName }}" class="h-16 sm:h-20 md:h-24 lg:h-28 w-auto mb-4 sm:mb-5" onerror="this.replaceWith(Object.assign(document.createElement('h3'),{className:'text-white font-semibold text-lg mb-3',textContent:'{{ $siteName }}'}))">
            <p class="text-ink-300 leading-relaxed">{{ $tagline }}@if ($owner) por <span class="text-white">{{ $owner }}</span>@endif. Cada evento, una galería; cada foto, un instante a tu medida.</p>
        </div>
        <div>
            <h4 class="text-white font-medium mb-3">Navegación</h4>
            <ul class="space-y-2 text-ink-300">
                <li><a href="{{ route('galleries.index') }}" class="hover:text-white">Galerías de eventos</a></li>
                <li><a href="{{ route('services') }}" class="hover:text-white">Servicios</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white">Sobre mí</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contacto</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-medium mb-3">Contacto</h4>
            <ul class="space-y-1.5 text-ink-300">
                @if ($phone && $waUrl)
                    <li>
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            {{ $phone }}
                        </a>
                    </li>
                @endif
                @if ($email)
                    <li>
                        <a href="mailto:{{ $email }}" class="inline-flex items-center gap-2 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            {{ $email }}
                        </a>
                    </li>
                @endif
                @if ($city)
                    <li class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $city }}
                    </li>
                @endif
            </ul>
            <div class="flex items-center gap-3 mt-4 text-ink-400">
                @if ($fbUrl)
                    <a href="{{ $fbUrl }}" target="_blank" rel="noopener" aria-label="Facebook" class="hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.77l-.44 2.9h-2.33V22c4.78-.75 8.43-4.91 8.43-9.93z"/></svg></a>
                @endif
                @if ($igUrl)
                    <a href="{{ $igUrl }}" target="_blank" rel="noopener" aria-label="Instagram" class="hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a>
                @endif
                @if ($waUrl)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg></a>
                @endif
            </div>
        </div>
    </div>
    <div class="border-t border-ink-700 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-ink-400 text-center sm:text-left">
            <p>© {{ date('Y') }} {{ $siteName }}@if ($owner) · {{ $owner }}@endif. Todos los derechos reservados.</p>
            <p class="text-ink-500">
                Desarrollado por
                <a href="https://orbexec.com/" target="_blank" rel="noopener" class="text-white hover:text-ink-200 font-medium">Orbex Studios</a>
            </p>
        </div>
    </div>
</footer>
