# MEMORY.md — PostUDO

Memoria del proyecto entre sesiones.

## Estado actual

- Laravel 12 monolito (Breeze) en Laragon/Windows, MySQL `postudo`, textos/comentarios/rutas en español.
- **Módulo financiero implementado y probado (2026-10-09):**
  - Tabla `egreso` (migración `2026_10_09_193858_create_egreso_table.php`) + modelo `App\Models\Egreso` con constante `MOTIVOS` (Compra, Pago, Mantenimiento, Servicios, Nómina, Alquiler, Otro).
  - `EgresoController` (`index/create/store`); rutas `administrador.finanzas`, `administrador.egreso.create`, `administrador.egreso.store` en `routes/administrador.php`.
  - Vistas `administrador/finanzas.blade.php` (tarjetas resumen + pestañas Ingresos/Egresos) y `administrador/registroegreso.blade.php` (select de motivo, campo detalle si "Otro").
  - Ingresos = pagos con `estado='Actualizado'` (solo verificados). Egresos: solo crear y ver (sin editar/eliminar).
  - El egreso guarda `tasa_momento` (de `tasa_cambio.json`) y `registrado_por` = `auth()->id()`.
- Sin CI, sin lint/typecheck. `vendor/bin/pint` es el único formatter.
- **Actualización de seguridad de dependencias (2026-10-12):** `composer audit` reportaba 46 avisos; se actualizaron `guzzlehttp/guzzle` (7.9.3→7.15.5) y `guzzlehttp/psr7` (2.7.1→2.13.1), quedando **33 avisos pendientes** (commonmark, Symfony, laravel/framework). `composer.lock` commiteado el 2026-10-12. Composer no está en PATH: usar `C:\laragon\bin\composer\composer.bat`.

## Aprendizajes y errores a evitar

- Los tests con BD fallan por diseño en este entorno (`pdo_sqlite` no habilitado en CLI PHP 8.3); evaluar regresiones por mensaje de error, no por conteo de fallos.
- Un archivo de rutas nuevo no hace nada hasta añadir su `require` en `routes/web.php`.
- No confiar en documentación que mencione CI/lint/typecheck: no existen.
- El parser de CSV de Google Sheet en `PagoController::verificarPagos` es sensible a la región (`;`/`,`, montos `8.700,00`): conservarlo intacto.
- Iconos Font Awesome inválidos fallan en silencio (ej.: `fa-bolli sign` → corregido a `fa-coins`).

## Decisiones (y por qué)

- **Ingresos = solo pagos verificados**: el usuario quiere ver dinero confirmado, no pendientes.
- **Egresos en Bs. + captura de `tasa_momento`**: consistente con cómo el estudiante registra sus pagos; permite un balance real.
- **Lista fija de motivos + "Otro" con detalle obligatorio**: datos ordenados/filtrables con flexibilidad.
- **Egresos en modo solo lectura (crear/ver)**: decisión explícita del usuario para una primera versión.
- **Reglas en AGENTS.md**: (1) no ejecutar ningún comando sin permiso previo; (2) antes de escribir código, presentar un plan de ideas/implementaciones para revisión.

## Próximos pasos

- **Pendiente de seguridad (importante):** resolver los 33 avisos restantes de `composer audit` con un `composer update` completo + prueba posterior (verificar sobre todo el flujo de verificación de pagos con Google Sheet). Guardar respaldo de `composer.lock` antes; rollback = `git restore composer.json composer.lock` + `composer install`.
- Módulo financiero: editar/eliminar egresos, filtro por rango de fechas, pestaña de pagos pendientes, exportar a PDF/Excel.
- Habilitar `pdo_sqlite` en `php.ini` para poder correr tests con BD.
- Corregir referencias a la ruta inexistente `dashboard` (`layouts/navigation.blade.php` y tests Breeze).
