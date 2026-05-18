<?php

if (! function_exists('setting')) {
    /**
     * Obtener un setting por clave con un valor por defecto.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}
