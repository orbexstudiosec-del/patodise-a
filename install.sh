#!/usr/bin/env bash
# =============================================================================
#  install.sh  —  Instalador completo de Pato Diseña
# =============================================================================
#  Hace todo en un solo paso:
#    1. Clona el repo desde GitHub (o actualiza si ya existe)
#    2. Monta el symlink public_html → patomolina/public (cPanel)
#       o lo deja para configurar en aaPanel
#    3. Pide credenciales de base de datos interactivamente
#    4. Crea .env con esos datos
#    5. composer install --no-dev --optimize-autoloader
#    6. php artisan key:generate
#    7. Importa database/patodisena.sql o corre migrate --seed
#    8. php artisan storage:link
#    9. Permisos OK
#   10. npm run build (si Node está)
#   11. Caché optimizado
#
#  Uso (en SSH del servidor):
#    Opción A — descargar y ejecutar:
#       curl -fsSL https://raw.githubusercontent.com/orbexstudiosec-del/patodise-a/main/install.sh -o install.sh
#       bash install.sh
#
#    Opción B — ejecutar directo:
#       curl -fsSL https://raw.githubusercontent.com/orbexstudiosec-del/patodise-a/main/install.sh | bash
# =============================================================================

set -e

# Colores
G='\033[1;32m'; Y='\033[1;33m'; R='\033[1;31m'; B='\033[1;34m'; C='\033[1;36m'; N='\033[0m'
step()  { echo -e "\n${B}══▶${N} ${1}"; }
ok()    { echo -e "  ${G}✓${N} ${1}"; }
warn()  { echo -e "  ${Y}⚠${N}  ${1}"; }
err()   { echo -e "  ${R}✗${N} ${1}"; }
ask()   { read -p "$(echo -e "  ${C}?${N} ${1}")" "$2"; }
title() {
    echo -e "\n${B}╔══════════════════════════════════════════════════════════╗${N}"
    echo -e "${B}║${N}  ${1}"
    echo -e "${B}╚══════════════════════════════════════════════════════════╝${N}"
}

REPO_URL="git@github.com:orbexstudiosec-del/patodise-a.git"
REPO_HTTPS="https://github.com/orbexstudiosec-del/patodise-a.git"

# =============================================================================
title " INSTALADOR PATO DISEÑA"
# =============================================================================

# -----------------------------------------------------------------------------
step "Verificando entorno"
# -----------------------------------------------------------------------------
USER_HOME=$(eval echo ~$USER)
echo "  Usuario:       $USER"
echo "  Home:          $USER_HOME"

command -v php      >/dev/null || { err "PHP no instalado"; exit 1; }
command -v composer >/dev/null || { err "Composer no instalado — instálalo o pide a tu hosting"; exit 1; }
command -v git      >/dev/null || { err "Git no instalado"; exit 1; }

PHP_VER=$(php -r 'echo PHP_VERSION;')
ok "PHP $PHP_VER"
ok "Composer $(composer --version --no-ansi 2>/dev/null | head -1 | awk '{print $3}')"
ok "Git $(git --version | awk '{print $3}')"

# Versión PHP mínima para Laravel 11
PHP_MAJOR=$(echo "$PHP_VER" | cut -d. -f1)
PHP_MINOR=$(echo "$PHP_VER" | cut -d. -f2)
if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
    err "Laravel 11 requiere PHP 8.2 o superior. Actualiza en cPanel → MultiPHP Manager."
    exit 1
fi

# -----------------------------------------------------------------------------
step "Carpeta destino del proyecto"
# -----------------------------------------------------------------------------
DEFAULT_DIR="$USER_HOME/patomolina"
ask "¿Dónde instalar? [$DEFAULT_DIR]: " PROJECT_DIR
PROJECT_DIR=${PROJECT_DIR:-$DEFAULT_DIR}
PROJECT_DIR=$(realpath -m "$PROJECT_DIR")
ok "Se instalará en: $PROJECT_DIR"

# -----------------------------------------------------------------------------
step "Clonando / actualizando código"
# -----------------------------------------------------------------------------
if [ -d "$PROJECT_DIR/.git" ]; then
    cd "$PROJECT_DIR"
    git pull origin main
    ok "Repo actualizado"
else
    # Intenta SSH primero (más seguro). Si no, cae a HTTPS.
    if git ls-remote "$REPO_URL" >/dev/null 2>&1; then
        git clone "$REPO_URL" "$PROJECT_DIR"
        ok "Clonado por SSH"
    else
        warn "SSH a GitHub no funcionó (revisa que tu llave esté en GitHub Deploy Keys)"
        warn "Usando HTTPS — te puede pedir usuario y token de GitHub"
        git clone "$REPO_HTTPS" "$PROJECT_DIR"
        ok "Clonado por HTTPS"
    fi
    cd "$PROJECT_DIR"
fi

chmod +x setup.sh deploy.sh install.sh 2>/dev/null || true

# -----------------------------------------------------------------------------
step "Configuración del Document Root"
# -----------------------------------------------------------------------------
PUBLIC_HTML="$USER_HOME/public_html"
TARGET_PUBLIC="$PROJECT_DIR/public"

if [ -L "$PUBLIC_HTML" ] && [ "$(readlink "$PUBLIC_HTML")" = "$TARGET_PUBLIC" ]; then
    ok "Symlink public_html → patomolina/public ya está configurado"
elif [ -d "$PUBLIC_HTML" ] || [ -L "$PUBLIC_HTML" ]; then
    warn "Vas a reemplazar tu public_html actual con un symlink al public/ de Laravel"
    warn "Si tienes archivos importantes en public_html, BÁJALOS por FTP ANTES de seguir"
    ask "¿Reemplazar public_html con el symlink? [y/N]: " RESP
    if [[ "$RESP" =~ ^[Yy]$ ]]; then
        rm -rf "$PUBLIC_HTML"
        ln -s "$TARGET_PUBLIC" "$PUBLIC_HTML"
        ok "Symlink creado: public_html → $TARGET_PUBLIC"
    else
        warn "Salteado — recuerda que el sitio NO funcionará hasta que apuntes el document root a $TARGET_PUBLIC"
    fi
else
    ln -s "$TARGET_PUBLIC" "$PUBLIC_HTML"
    ok "Symlink creado: public_html → $TARGET_PUBLIC"
fi

# -----------------------------------------------------------------------------
step "Configurando archivo .env"
# -----------------------------------------------------------------------------
if [ -f .env ]; then
    warn ".env ya existe en el proyecto"
    ask "¿Sobrescribir? [y/N]: " RESP
    if [[ ! "$RESP" =~ ^[Yy]$ ]]; then
        ok "Manteniendo .env actual"
        SKIP_ENV=1
    fi
fi

if [ -z "$SKIP_ENV" ]; then
    cp .env.example .env

    ask "Dominio del sitio (sin https://) [patomolina.com]: " DOMAIN
    DOMAIN=${DOMAIN:-patomolina.com}

    echo
    echo "  Datos de la base de datos (los creas en cPanel → MySQL Databases):"
    ask "  Nombre de la BD       : " DB_NAME
    ask "  Usuario de la BD      : " DB_USER
    ask "  Contraseña de la BD   : " DB_PASS

    # Reemplazar valores en .env
    sed -i.bak "s|^APP_NAME=.*|APP_NAME=\"Pato Diseña\"|" .env
    sed -i.bak "s|^APP_ENV=.*|APP_ENV=production|" .env
    sed -i.bak "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
    sed -i.bak "s|^APP_URL=.*|APP_URL=https://$DOMAIN|" .env
    sed -i.bak "s|^DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env
    sed -i.bak "s|^# DB_HOST=.*|DB_HOST=127.0.0.1|" .env
    sed -i.bak "s|^DB_HOST=.*|DB_HOST=127.0.0.1|" .env
    sed -i.bak "s|^# DB_PORT=.*|DB_PORT=3306|" .env
    sed -i.bak "s|^DB_PORT=.*|DB_PORT=3306|" .env
    sed -i.bak "s|^# DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
    sed -i.bak "s|^DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
    sed -i.bak "s|^# DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
    sed -i.bak "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
    sed -i.bak "s|^# DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env
    sed -i.bak "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env
    rm -f .env.bak
    ok ".env configurado"
fi

# -----------------------------------------------------------------------------
step "Instalando dependencias PHP (composer install)"
# -----------------------------------------------------------------------------
composer install --no-dev --optimize-autoloader --no-interaction
ok "Composer OK"

# -----------------------------------------------------------------------------
step "Generando APP_KEY"
# -----------------------------------------------------------------------------
php artisan key:generate --force
ok "APP_KEY generada"

# -----------------------------------------------------------------------------
step "Probando conexión a la base de datos"
# -----------------------------------------------------------------------------
if php artisan db:show --json >/dev/null 2>&1; then
    ok "Conexión MySQL OK"
else
    err "No se pudo conectar a MySQL. Revisa los datos en $PROJECT_DIR/.env y vuelve a correr ./install.sh"
    exit 1
fi

# -----------------------------------------------------------------------------
step "Importando base de datos"
# -----------------------------------------------------------------------------
ask "¿Importar database/patodisena.sql (datos demo)? Si dices NO, corre migrate --seed. [y/N]: " IMP
DB_NAME=$(grep ^DB_DATABASE= .env | cut -d= -f2)
DB_USER=$(grep ^DB_USERNAME= .env | cut -d= -f2)
DB_PASS=$(grep ^DB_PASSWORD= .env | cut -d= -f2)

if [[ "$IMP" =~ ^[Yy]$ ]] && [ -f database/patodisena.sql ]; then
    mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < database/patodisena.sql && ok "BD importada (con datos demo)"
else
    php artisan migrate --force --seed
    ok "Migraciones corridas con seed"
fi

# -----------------------------------------------------------------------------
step "Symlink storage → public/storage"
# -----------------------------------------------------------------------------
php artisan storage:link --force 2>/dev/null
ok "Storage linkeado"

# -----------------------------------------------------------------------------
step "Configurando permisos"
# -----------------------------------------------------------------------------
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
ok "775 en storage/ y bootstrap/cache/"

# -----------------------------------------------------------------------------
step "Compilando assets (npm run build)"
# -----------------------------------------------------------------------------
if command -v npm >/dev/null; then
    npm install --no-audit --no-fund --silent
    npm run build
    ok "public/build/ generado"
else
    warn "Node/npm no instalado en este servidor."
    warn "Compila localmente con 'npm run build' y sube /public/build/ por FTP/git."
fi

# -----------------------------------------------------------------------------
step "Optimizando caché de Laravel"
# -----------------------------------------------------------------------------
php artisan config:cache >/dev/null
php artisan route:cache >/dev/null
php artisan view:cache >/dev/null
ok "Caché de config/routes/views generado"

# =============================================================================
# FINAL
# =============================================================================
DOMAIN=${DOMAIN:-$(grep ^APP_URL= .env | cut -d= -f2 | sed 's|https\?://||')}

echo
echo -e "${G}╔══════════════════════════════════════════════════════════╗${N}"
echo -e "${G}║              ✓ INSTALACIÓN COMPLETADA                    ║${N}"
echo -e "${G}╠══════════════════════════════════════════════════════════╣${N}"
echo -e "${G}║${N}                                                          ${G}║${N}"
echo -e "${G}║${N}  Sitio web      : ${Y}https://$DOMAIN${N}"
echo -e "${G}║${N}  Panel admin    : ${Y}https://$DOMAIN/admin${N}"
echo -e "${G}║${N}                                                          ${G}║${N}"
echo -e "${G}║${N}  Login admin    : ${Y}admin@patodisena.com${N}"
echo -e "${G}║${N}  Contraseña     : ${Y}password${N}  ${R}← CÁMBIALA YA${N}"
echo -e "${G}║${N}                                                          ${G}║${N}"
echo -e "${G}║${N}  Proyecto en    : ${C}$PROJECT_DIR${N}"
echo -e "${G}║${N}  Public root    : ${C}$PROJECT_DIR/public${N}"
echo -e "${G}║${N}                                                          ${G}║${N}"
echo -e "${G}║${N}  Próximas actualizaciones:                               ${G}║${N}"
echo -e "${G}║${N}    ${C}cd $PROJECT_DIR && ./deploy.sh${N}"
echo -e "${G}║${N}                                                          ${G}║${N}"
echo -e "${G}╚══════════════════════════════════════════════════════════╝${N}"
echo
