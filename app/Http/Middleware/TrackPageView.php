<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * Rutas a EXCLUIR del tracking (paneles, AJAX, assets, etc.)
     */
    private const SKIP_PREFIXES = [
        'admin', 'admin/*',
        'carrito/*',           // operaciones AJAX del carrito
        'img/preview/*',       // imágenes con marca de agua
        'build/*',             // assets vite
        'fonts/*', 'images/*', 'storage/*',
        'up',                  // health check
        '_debugbar/*', 'livewire/*', 'telescope/*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if ($this->shouldTrack($request, $response)) {
                PageView::create([
                    'session_id' => Str::limit($request->hasSession() ? $request->session()->getId() : 'no-session', 64, ''),
                    'user_id' => $request->user()?->id,
                    'path' => Str::limit('/' . ltrim($request->path(), '/'), 500, ''),
                    'referer' => Str::limit((string) $request->headers->get('referer'), 500, '') ?: null,
                    'ip' => Str::limit((string) $request->ip(), 64, ''),
                    'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
                    'created_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // No bloquear la respuesta por errores del tracking
            report($e);
        }

        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if ($request->method() !== 'GET') return false;
        if (! $response->isSuccessful()) return false;
        if ($request->ajax() || $request->wantsJson()) return false;

        // Excluir si es admin
        if ($request->user()?->isAdmin()) return false;

        foreach (self::SKIP_PREFIXES as $pattern) {
            if ($request->is($pattern)) return false;
        }

        // Solo trackear respuestas HTML
        $contentType = (string) $response->headers->get('content-type');
        if ($contentType && ! str_contains($contentType, 'text/html')) return false;

        return true;
    }
}
