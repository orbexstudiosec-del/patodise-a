# Pato Diseña

Tienda online de fotografía y diseño construida con **Laravel 11 + Tailwind CSS 4 + MySQL**.

Permite al cliente publicar y vender fotografías en distintos formatos (digital, impresión A4/A3, canvas), gestionar pedidos desde un panel de administración y ofrecer información de servicios.

## Stack

- PHP 8.5 + Laravel 11
- MySQL 8 (vía XAMPP)
- Tailwind CSS 4 + Alpine.js
- Vite

## Requisitos

- PHP 8.3+ con extensiones `pdo_mysql`, `mbstring`, `gd`, `fileinfo`
- Composer
- Node.js 18+ y npm
- MySQL (XAMPP, Laragon, etc.)

## Instalación

```bash
# 1. Clonar y entrar
cd c:\patodiseña

# 2. Dependencias PHP
composer install

# 3. Dependencias JS
npm install

# 4. Configurar entorno
copy .env.example .env
php artisan key:generate
```

Editar `.env` y ajustar la conexión MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=patodisena
DB_USERNAME=root
DB_PASSWORD=
```

Crear la base de datos:

```sql
CREATE DATABASE patodisena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Migrar y sembrar datos de demo:

```bash
php artisan migrate --seed
php artisan storage:link
```

## Desarrollo

```bash
# Terminal 1 — servidor PHP
php artisan serve

# Terminal 2 — Vite (Tailwind + JS en hot reload)
npm run dev
```

Abrir [http://127.0.0.1:8000](http://127.0.0.1:8000).

Para producción / probar build:

```bash
npm run build
```

## Cuentas de prueba

| Rol      | Email                    | Contraseña |
|----------|--------------------------|-----------|
| Admin    | admin@patodisena.com     | password  |
| Cliente  | cliente@example.com      | password  |

El panel admin está en [/admin](http://127.0.0.1:8000/admin).

## Estructura del proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        ← Dashboard, CRUD fotos/categorías/pedidos
│   │   ├── Auth/         ← Login/registro/logout
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── ContactController.php
│   │   ├── GalleryController.php
│   │   ├── HomeController.php
│   │   └── PhotoController.php
│   └── Middleware/EnsureAdmin.php
├── Models/                ← User, Category, Photo, Order, OrderItem
└── Services/CartService.php  ← carrito en sesión

resources/views/
├── layouts/app.blade.php
├── partials/{nav,footer,photo-card}.blade.php
├── home, gallery, photos, cart, checkout, auth, about, services, contact
└── admin/                 ← layout + dashboard + photos/categories/orders
```

## Funcionalidades

### Cliente final
- Home con hero, categorías, obras destacadas y recientes
- Galería con búsqueda, filtro por categoría y ordenamiento
- Detalle de fotografía con selector de formato (digital / impresión A4 / A3 / canvas) y cantidad
- Carrito en sesión (sin necesidad de login)
- Checkout con datos del cliente, dirección y método de pago
- Páginas de servicios, sobre mí y contacto
- Registro y login opcional

### Admin
- Dashboard con métricas (fotografías, pedidos, ingresos)
- CRUD de fotografías con subida de imagen, categoría, precio, stock, destacada
- CRUD de categorías
- Listado y detalle de pedidos con cambio de estado (pendiente / pagado / enviado / completado / cancelado)

## Próximos pasos sugeridos

- Integración real con pasarela de pagos (Stripe / PayPal / PayPhone)
- Envío de email transaccional al confirmar pedido
- Marca de agua automática para las imágenes públicas
- Galerías privadas para entrega de proyectos personalizados
- Multidioma (es / en)
