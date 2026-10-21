# AGENTS.md

PostUDO: monolito Laravel 12 (PHP ^8.2, basado en Breeze) para gestión de posgrados universitarios. El código, los textos de interfaz, los comentarios y los nombres de rutas están en **español** — escribe nuevos textos y comentarios en español para mantener la coherencia. Desarrollo local en Laragon sobre Windows, MySQL con base de datos `postudo` según `.env`.

## Reglas

1. **No ejecutes ningún comando sin mi permiso previo.** Antes de correr cualquier comando en la terminal (artisan, composer, npm, pint, git, migrate, etc.), pídemelo y espera mi confirmación. Esto aplica también a comandos de instalación, migración, limpieza de caché y ejecución de tests.

2. **Siempre antes de escirbir codigo piensa.** Siempre antes de escribir codigo, ejecuta un plan con tus ideas y diferentes implementaciones que quieras hacer para yo revisarlo primero antes de comenzar a ejecutarlo

3. **Siempre** Actualizar `MEMORY.md` al terminar cada tarea.

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
- Alias de middleware `role` → `App\Http\Middleware\CheckRole`, lee `users.rol`, acepta `role:a|b`. Cadenas de rol: `estudiante`, `profesor`, `administrador`, `coordinador_general`, `coordinador_postgrado` (no existe `coordinador` suelto).
- Convención para rutas de panel: `->middleware('auth', 'role:<rol>')`. `routes/profesor.php` no lo usa en absoluto hoy (esas rutas son públicas) — no copies ese patrón.
- El login y el registro (`AuthenticatedSessionController`, `RegisteredUserController`) redirigen según el rol; no hay ruta `dashboard` a la que caer.
- `CheckRole` tiene un `return` inalcanzable después de la redirección — no "corrijas" el flujo de control ahí sin revisar quién lo llama.

## Particularidades del dominio

- Las filas de `users` solo se crean en el registro, que deduce el `rol` buscando la `cedula` en las tablas `estudiante`/`profesor`/`administrador`/`coordinador_general`. Esas tablas no tienen columna de rol; el rol vive únicamente en `users.rol`.
- La configuración en tiempo real son archivos JSON en el disco por defecto, no filas en BD: `tasa_cambio.json` (tasa de cambio) + `tasa_cambio_historial.json` (historial), manejados por `App\Services\TasaCambioService` — sincronización automática con el BCV vía `https://bcv.today/api/v1/rate.json` (comando `tasa:actualizar-bcv` + fallback al cargar el panel; historial claveado por día calendario = `date` de la API); `google_sheet_config.json` (URL del CSV publicado de Google Sheet) en `PagoController::verificarPagos`. Están en `storage/app/private/`.
- Gestión de postgrados y cohortes: rutas con doble rol `role:administrador|coordinador_general` en `routes/administrador.php`. `PostgradoController::create()` admite `?area=CODIGO` para preseleccionar el área (botón «Nuevo Postgrado» de la fila desplegada en `/coordinador/areas`); tras guardar, el coordinador vuelve a Áreas y el administrador a su listado (la ruta de Áreas es solo-coordinador).
- **Las cohortes cuelgan de una mención** (`cohorte.codigo_mencion`, nullable con `set null`): la mención es obligatoria al crear/editar y `nro_de_cohorte` es único por mención. `codigo_postgrado` se conserva como espejo **derivado de la mención al guardar** en `CohorteController` — nunca confiar en `codigo_postgrado` ni `codigo_mencion` que llegue del cliente. Eliminar una mención con cohortes está **bloqueado** en `MencionController::destroy` (los estudiantes siguen con `set null`).
- **Coordinador de postgrado** (`role:coordinador_postgrado`, creado 2026-10-20): lo da de alta el coordinador general desde `/coordinador/coordinadores_postgrado` (fila en `coordinador_postgrado`, espejo de `coordinador_general`) + pivote `asignacion_coordinador_postgrado` con `unique(codigo_postgrado)` = **1 coordinador por postgrado** (un coordinador puede tener varios). El rol solo se activa al registrarse la persona (deducción por cédula, `elseif` al final de la cadena). Filtra «sus» postgrados con el trait `App\Http\Controllers\Concerns\FiltrablePorCoordinador` en los controllers de Postgrado/Cohorte/Mención (para admin/coordinador_general devuelve «sin filtro»). No puede crear/editar/borrar postgrados; sí sus menciones y cohortes.
- La verificación de pagos descarga un Google Sheet publicado como CSV y analiza delimitadores `;` o `,` y montajes tipo `8.700,00` — sensible a la configuración regional; conserva ese parser intacto al tocar `PagoController`.

## Frontend

- Dos pipelines de estilos:
  - páginas de auth/profile de Breeze → `layouts/app.blade.php` y `layouts/guest.blade.php` → bundle `@vite` (Tailwind v3, contenido escaneado desde `resources/views/**`).
  - todos los paneles de la app → `<x-layout>` (`resources/views/components/layout.blade.php`) → CSS estático `public/css/style.css` (con cache-buster `?v={{ time() }}`) más Font Awesome/Google Fonts por CDN. **Los cambios en `tailwind.config.js` no afectan a las páginas de panel.**
- `@tailwindcss/vite` ^4 en `package.json` no se usa (no está en `vite.config.js`); el pipeline real es PostCSS + `tailwindcss` v3. No "mejores" la configuración al estilo v4.


## Memoria - Al empezar, lee `MEMORY.md` para conocer el estado del proyecto y las decisiones tomadas. 
- Al terminar una tarea, actualízalo: estado actual, decisiones importantes (con su porqué) y errores a evitar. 
- Mantenlo breve (máximo ~50 líneas): resume o elimina lo que ya no aporte. 
- Si algo se convierte en una regla permanente, propón moverlo a `AGENTS.md` en lugar de dejarlo en la memoria. 
- No guardes nunca datos sensibles (claves, tokens, datos personales).

