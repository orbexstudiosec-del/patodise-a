# Logo

Sube aquí dos versiones del logo:

- `logo-dark.svg`  — logo en NEGRO, para la nav blanca del sitio público.
- `logo-light.svg` — logo en BLANCO, para el footer y el sidebar admin (fondo negro).

**Importante**: el logo que enviaste me llegó como un PNG completamente blanco
(o el logo es blanco sobre fondo blanco), así que no pude extraerlo. Necesito:

1. La versión negra (sobre fondo blanco) → `logo-dark.svg`
2. La versión blanca (sobre fondo negro) → `logo-light.svg`

Si solo tienes un PNG, también vale (`.png`) pero hay que actualizar las rutas
en `resources/views/partials/nav.blade.php`, `partials/footer.blade.php` y
`admin/layout.blade.php`. SVG escala mejor.

Mientras no haya archivos, las vistas hacen fallback a un span "Pato Diseña" en texto.
