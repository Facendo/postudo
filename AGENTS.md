# AGENTS.md

PostUDO: monolito Laravel 12 (PHP ^8.2, basado en Breeze) para gestión de posgrados universitarios. El código, los textos de interfaz, los comentarios y los nombres de rutas están en **español** — escribe nuevos textos y comentarios en español para mantener la coherencia. Desarrollo local en Laragon sobre Windows, MySQL con base de datos `postudo` según `.env`.

## Comandos

- `composer dev` — servidor + listener de colas + Vite en un solo proceso (vía `npx concurrently`).
- `npm run dev` / `npm run build` — solo Vite (Tailwind v3 vía PostCSS, Alpine).
- `composer test` — ejecuta `config:clear` y luego `artisan test` (Pest 3). Un solo test: `php artisan test --filter=Nombre` o `vendor/bin/pest tests/Feature/Auth/AuthenticationTest.php`.
- `vendor/bin/pint` — formato de código (Pint, preset por defecto, no hay `pint.json`).
- Primer arranque: `php artisan key:generate && php artisan migrate --seed`. El seed crea los accesos `admin@admin.com` / `12345678` (administrador) y `francisco@estudiante.com` / `12345678` (estudiante).
- No existe CI, ni flujos de lint/typecheck/codegen. No confíes en documentación que afirme lo contrario.

## Los tests fallan por defecto: entiende por qué antes de depurar

- `phpunit.xml` fuerza sqlite `:memory:`, así que los tests requieren la extensión `pdo_sqlite`. El CLI de PHP 8.3 de este entorno solo expone `pdo_mysql`; hasta que se habilite `pdo_sqlite` en `php.ini`, todo test con BD falla con `could not find driver`.
- Incluso con sqlite, los tests originales de Breeze no encajan con esta app y fallan por motivos ajenos a tu cambio:
  - no existe la ruta `dashboard` (los tests y `resources/views/layouts/navigation.blade.php` siguen llamando a `route('dashboard')`);
  - `UserFactory` omite las columnas NOT NULL `cedula`, `rol`, `foto_perfil`;
  - el registro exige una `cedula` que ya exista en una tabla de rol.
- Evalúa las regresiones por el mensaje de error, no por el recuento de fallos. Los tests de feature reciben `RefreshDatabase` vía `tests/Pest.php`.

## Rutas y autenticación

- `bootstrap/app.php` solo registra `routes/web.php`; ese archivo hace `require` de los archivos por rol: `auth`, `estudiante`, `pago`, `profesor`, `administrador`, `postgrado`, `asunto`, `coordinador`. **Un archivo de rutas nuevo no hace nada hasta que se requiera desde `web.php`.**
- Alias de middleware `role` → `App\Http\Middleware\CheckRole`, lee `users.rol`, acepta `role:a|b`. Cadenas de rol: `estudiante`, `profesor`, `administrador`, `coordinador_general` (no existe `coordinador`).
- Convención para rutas de panel: `->middleware('auth', 'role:<rol>')`. `routes/profesor.php` no lo usa en absoluto hoy (esas rutas son públicas) — no copies ese patrón.
- El login y el registro (`AuthenticatedSessionController`, `RegisteredUserController`) redirigen según el rol; no hay ruta `dashboard` a la que caer.
- `CheckRole` tiene un `return` inalcanzable después de la redirección — no "corrijas" el flujo de control ahí sin revisar quién lo llama.

## Particularidades del dominio

- Las filas de `users` solo se crean en el registro, que deduce el `rol` buscando la `cedula` en las tablas `estudiante`/`profesor`/`administrador`/`coordinador_general`. Esas tablas no tienen columna de rol; el rol vive únicamente en `users.rol`.
- La configuración en tiempo real son archivos JSON en el disco por defecto, no filas en BD: `tasa_cambio.json` (tasa de cambio) + `tasa_cambio_historial.json` (historial) en `AdministradorController`, `google_sheet_config.json` (URL del CSV publicado de Google Sheet) en `PagoController::verificarPagos`. Están en `storage/app/private/`.
- La verificación de pagos descarga un Google Sheet publicado como CSV y analiza delimitadores `;` o `,` y montajes tipo `8.700,00` — sensible a la configuración regional; conserva ese parser intacto al tocar `PagoController`.

## Frontend

- Dos pipelines de estilos:
  - páginas de auth/profile de Breeze → `layouts/app.blade.php` y `layouts/guest.blade.php` → bundle `@vite` (Tailwind v3, contenido escaneado desde `resources/views/**`).
  - todos los paneles de la app → `<x-layout>` (`resources/views/components/layout.blade.php`) → CSS estático `public/css/style.css` (con cache-buster `?v={{ time() }}`) más Font Awesome/Google Fonts por CDN. **Los cambios en `tailwind.config.js` no afectan a las páginas de panel.**
- `@tailwindcss/vite` ^4 en `package.json` no se usa (no está en `vite.config.js`); el pipeline real es PostCSS + `tailwindcss` v3. No "mejores" la configuración al estilo v4.
