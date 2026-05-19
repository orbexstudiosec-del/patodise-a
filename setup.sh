#!/usr/bin/env bash
# =====================================================================
#  setup.sh  —  Primera instalación de Pato Diseña en el servidor
# =====================================================================
#  Ejecutar UNA SOLA VEZ, después del primer git clone.
#
#  Uso (en SSH del servidor):
#      cd /www/wwwroot/patomolina.com
#      chmod +x setup.sh deploy.sh
#      ./setup.sh
# =====================================================================

set -e   # Si cualquier paso falla, detener inmediatamente
cd "$(dirname "$0")"   # Movernos a la carpeta del script

# Colores para los mensajes
G='\033[1;32m'; Y='\033[1;33m'; R='\033[1;31m'; B='\033[1;34m'; N='\033[0m'

step() { echo -e "\n${B}==>${N} ${1}"; }
ok()   { echo -e "${G}✓${N} ${1}"; }
warn() { echo -e "${Y}⚠${N}  ${1}"; }
err()  { echo -e "${R}✗${N} ${1}"; }

# ---------------------------------------------------------------------
step "1. Verificando requisitos (PHP, Composer, MySQL)"
# ---------------------------------------------------------------------
command -v php      >/dev/null || { err "PHP no instalado"; exit 1; }
command -v composer >/dev/null || { err "Composer no instalado"; exit 1; }
ok "PHP $(php -r 'echo PHP_VERSION;') · Composer $(composer --version --no-ansi 2>/dev/null | head -1)"

# ---------------------------------------------------------------------
step "2. Creando archivo .env (si no existe)"
# ---------------------------------------------------------------------
if [ ! -f .env ]; then
    cp .env.example .env
    ok "Creado .env desde .env.example"
    warn "EDITA .env AHORA con las credenciales reales antes de continuar:"
    warn "   APP_URL=https://patomolina.com"
    warn "   APP_DEBUG=false"
    warn "   APP_ENV=production"
    warn "   DB_DATABASE=patomjnh_dbpato"
    warn "   DB_USERNAME=...  DB_PASSWORD=..."
    read -p "Pulsa ENTER cuando hayas guardado .env (o Ctrl+C para abortar)..."
else
    ok ".env ya existe — no se sobrescribe"
fi

# ---------------------------------------------------------------------
step "3. Instalando dependencias PHP (composer)"
# ---------------------------------------------------------------------
composer install --no-dev --optimize-autoloader --no-interaction
ok "Composer OK"

# ---------------------------------------------------------------------
step "4. Generando clave de aplicación (APP_KEY)"
# ---------------------------------------------------------------------
if grep -q "^APP_KEY=$" .env || grep -q "^APP_KEY=base64:V+fv/3BIHV2JNUw9aEDhMjSGstQkCD4zPKIX0hN9t2Y=" .env; then
    php artisan key:generate --force
    ok "APP_KEY generada"
else
    ok "APP_KEY ya existe — no se regenera"
fi

# ---------------------------------------------------------------------
step "5. Symlink storage → public/storage (para servir uploads)"
# ---------------------------------------------------------------------
php artisan storage:link 2>&1 || warn "Storage link ya existía"

# ---------------------------------------------------------------------
step "6. Configurando permisos (storage y bootstrap/cache escribibles)"
# ---------------------------------------------------------------------
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
# Detectar el usuario web del sistema (www-data en Ubuntu/Debian, www en aaPanel/CentOS, apache en otros)
WEB_USER=""
for u in www www-data apache nginx; do
    if id "$u" &>/dev/null; then WEB_USER="$u"; break; fi
done
if [ -n "$WEB_USER" ]; then
    chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache 2>/dev/null || warn "No se pudo cambiar owner (necesita sudo)"
    ok "Permisos asignados a usuario web: $WEB_USER"
else
    warn "No se detectó el usuario web — corre manualmente: chown -R USER:USER storage bootstrap/cache"
fi

# ---------------------------------------------------------------------
step "7. ¿Importar la base de datos?"
# ---------------------------------------------------------------------
if [ -f database/patodisena.sql ]; then
    echo "Se detectó database/patodisena.sql (dump con datos de demo)."
    read -p "¿Importarlo a la BD definida en .env? Esto BORRA y RECREA las tablas. [y/N] " yn
    if [[ "$yn" =~ ^[Yy]$ ]]; then
        DB_DB=$(grep ^DB_DATABASE= .env | cut -d= -f2 | tr -d '"')
        DB_USER=$(grep ^DB_USERNAME= .env | cut -d= -f2 | tr -d '"')
        DB_PASS=$(grep ^DB_PASSWORD= .env | cut -d= -f2 | tr -d '"')
        mysql -u "$DB_USER" -p"$DB_PASS" "$DB_DB" < database/patodisena.sql && ok "BD importada"
    else
        echo "Ejecutando migraciones limpias en su lugar..."
        php artisan migrate --force --seed
        ok "Migraciones + seed corridos"
    fi
else
    php artisan migrate --force --seed
    ok "Migraciones + seed corridos"
fi

# ---------------------------------------------------------------------
step "8. Compilando assets (si Node está instalado)"
# ---------------------------------------------------------------------
if command -v npm >/dev/null; then
    npm install --no-audit --no-fund
    npm run build
    ok "Assets compilados (public/build/)"
else
    warn "Node no está instalado. Sube /public/build/ por FTP desde tu equipo o instala Node."
fi

# ---------------------------------------------------------------------
step "9. Optimizando caché de Laravel"
# ---------------------------------------------------------------------
php artisan config:cache
php artisan route:cache
php artisan view:cache
ok "Caché optimizado"

# ---------------------------------------------------------------------
echo
echo -e "${G}╔══════════════════════════════════════════════╗${N}"
echo -e "${G}║   ✓ Setup completado                         ║${N}"
echo -e "${G}╠══════════════════════════════════════════════╣${N}"
echo -e "${G}║${N}  Document root → ${Y}/public${N}                     ${G}║${N}"
echo -e "${G}║${N}  Admin login   → ${Y}admin@patodisena.com${N}        ${G}║${N}"
echo -e "${G}║${N}  Contraseña    → ${Y}password${N} (cámbiala YA)       ${G}║${N}"
echo -e "${G}║${N}  Próximas actualizaciones → ${Y}./deploy.sh${N}        ${G}║${N}"
echo -e "${G}╚══════════════════════════════════════════════╝${N}"
