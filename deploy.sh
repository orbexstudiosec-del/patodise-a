#!/usr/bin/env bash
# =====================================================================
#  deploy.sh  —  Actualizar Pato Diseña a la última versión
# =====================================================================
#  Ejecutar cada vez que quieras subir cambios desde GitHub al servidor.
#
#  Uso (en SSH del servidor):
#      cd /www/wwwroot/patomolina.com
#      ./deploy.sh
# =====================================================================

set -e
cd "$(dirname "$0")"

G='\033[1;32m'; Y='\033[1;33m'; R='\033[1;31m'; B='\033[1;34m'; N='\033[0m'
step() { echo -e "\n${B}==>${N} ${1}"; }
ok()   { echo -e "${G}✓${N} ${1}"; }
warn() { echo -e "${Y}⚠${N}  ${1}"; }

START=$(date +%s)

# ---------------------------------------------------------------------
step "1. Modo mantenimiento ON (los visitantes ven 'Be right back')"
# ---------------------------------------------------------------------
php artisan down --render="errors::503" --retry=15 2>/dev/null || warn "No se pudo activar modo mantenimiento (sigo igual)"

# Atrapar cualquier error y SIEMPRE salir de mantenimiento al final
trap 'php artisan up 2>/dev/null || true' EXIT

# ---------------------------------------------------------------------
step "2. Bajando últimos cambios desde GitHub"
# ---------------------------------------------------------------------
git fetch origin
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/main)
if [ "$LOCAL" = "$REMOTE" ]; then
    ok "Ya estás en la última versión, no hay nada que actualizar"
else
    echo "Cambios pendientes:"
    git log --oneline HEAD..origin/main | head -10
    git reset --hard origin/main
    ok "Código actualizado a $(git rev-parse --short HEAD)"
fi

# ---------------------------------------------------------------------
step "3. Actualizando dependencias de PHP"
# ---------------------------------------------------------------------
composer install --no-dev --optimize-autoloader --no-interaction
ok "Composer OK"

# ---------------------------------------------------------------------
step "4. Aplicando migraciones de base de datos"
# ---------------------------------------------------------------------
php artisan migrate --force
ok "Migraciones al día"

# ---------------------------------------------------------------------
step "5. Compilando assets (si Node está)"
# ---------------------------------------------------------------------
if command -v npm >/dev/null; then
    npm install --no-audit --no-fund --silent
    npm run build
    ok "Assets compilados"
else
    warn "Node no instalado — saltando build. Sube public/build/ desde tu equipo si cambió el CSS/JS"
fi

# ---------------------------------------------------------------------
step "6. Limpiando y reconstruyendo caché de Laravel"
# ---------------------------------------------------------------------
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
ok "Caché regenerado"

# ---------------------------------------------------------------------
step "7. Permisos OK"
# ---------------------------------------------------------------------
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
ok "Storage y bootstrap/cache escribibles"

# ---------------------------------------------------------------------
step "8. Saliendo de modo mantenimiento"
# ---------------------------------------------------------------------
php artisan up
ok "Sitio ONLINE"

# ---------------------------------------------------------------------
END=$(date +%s)
DUR=$((END-START))
echo
echo -e "${G}╔══════════════════════════════════════════════╗${N}"
echo -e "${G}║   ✓ Deploy completado en ${DUR}s                    ${G}║${N}"
echo -e "${G}║${N}  Versión actual: ${Y}$(git rev-parse --short HEAD)${N}                       ${G}║${N}"
echo -e "${G}╚══════════════════════════════════════════════╝${N}"
