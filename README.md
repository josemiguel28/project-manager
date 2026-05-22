# Parker's Job Store — Work Tracker

Aplicación web para registrar, buscar y gestionar trabajos/servicios. Reemplaza la tabla de Word con una interfaz moderna y reactiva.

## Stack

- Laravel 11 + Livewire 4
- Tailwind CSS (via Vite)
- SQLite (sin servidor de base de datos)

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js 18+

Verifica versiones:

```bash
php -v
composer -V
node -v
```

## Instalación

```bash
# 1. Instalar dependencias PHP
composer install

# 2. Instalar dependencias JS
npm install

# 3. Crear el archivo de entorno
cp .env.example .env
php artisan key:generate

# 4. Asegurarse de que la base de datos SQLite existe
touch database/database.sqlite

# 5. Verificar que .env tenga SQLite configurado
# DB_CONNECTION=sqlite
# (las líneas DB_HOST, DB_PORT, etc. deben estar comentadas)

# 6. Crear tablas y poblar con datos de ejemplo
php artisan migrate --seed

# 7. Compilar assets
npm run build
```

## Arrancar la aplicación

```bash
# Modo desarrollo (recarga automática de assets)
npm run dev &
php artisan serve
```

Abre [http://localhost:8000](http://localhost:8000) en tu navegador.

O usando el script all-in-one (requiere `concurrently`):

```bash
composer run dev
```

## Uso

- **Dashboard superior**: muestra totales cobrados (mes/año) y conteos de jobs.
- **Buscar**: escribe en el campo de búsqueda para filtrar por tienda, sitio, Work Order ID o técnico asignado.
- **Filtros de estado**: usa las pills (Todos / Pendiente / En Progreso / Completado) para filtrar la tabla.
- **Work Order ID / Invoice #**: si tienen link guardado, aparecen como hipervínculo azul con ícono ↗ que abre en pestaña nueva.
- **Agregar Job**: botón azul arriba a la derecha abre el formulario.
- **Editar**: ícono de lápiz en la columna Acciones.
- **Eliminar**: ícono de papelera — pide confirmación antes de borrar.
- **Estado → Completado**: si no tiene fecha de finalización, se rellena automáticamente con la fecha de hoy.

## Resetear datos de ejemplo

```bash
php artisan migrate:fresh --seed
```

## Estructura clave

```
app/
  Livewire/WorkOrderManager.php    — Lógica de la pantalla principal
  Models/WorkOrder.php             — Modelo Eloquent

database/
  migrations/*_create_work_orders_table.php
  seeders/WorkOrderSeeder.php      — 8 jobs fijos + 4 aleatorios
  factories/WorkOrderFactory.php

resources/views/
  livewire/work-order-manager.blade.php  — Vista Livewire
  layouts/parkers.blade.php              — Layout HTML base
```
