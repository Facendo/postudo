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
- **Actualización automática de la tasa BCV (2026-10-14):**
  - `App\Services\TasaCambioService` centraliza lectura/escritura de `tasa_cambio.json` + `tasa_cambio_historial.json` (antes duplicados en controladores y vistas).
  - Fuente: `https://bcv.today/api/v1/rate.json` (API comunitaria gratis que replica el BCV; `bcv.org.ve` no responde desde este entorno). Sin clave, sin límites.
  - 3 vías de actualización: comando `php artisan tasa:actualizar-bcv` (scheduler horario en `routes/console.php`), botón "Sincronizar con BCV" en el panel admin (ruta `administrador.tasa_cambio.bcv`), y fallback perezoso en `AdministradorController::index` y `AsuntoController::index/precios` si la tasa no es del día o tiene >6 h (con espera de 15 min tras un fallo).
  - El input manual se conservó como emergencia, marcado `fuente: 'manual'`. La tarjeta muestra "Fuente: BCV/Manual". El JSON ahora tiene claves extra (`fuente`, `fecha_iso`, `actualizada_en`); los consumidores actuales no se tocaron.
- **Reorganización de paneles (2026-10-14):** la gestión de estudiantes (lista/registro/edición), especialidades y la página-hub de creación de Áreas/Carreras/Especialidades pasó del panel del administrador al del coordinador general. Rutas movidas a `routes/coordinador.php` con URL `/coordinador/...` y `role:coordinador_general`; **los nombres de ruta se conservaron** (siguen con prefijo `administrador.*`) para no tocar vistas/controladores. Se eliminaron rutas duplicadas-legado en `routes/estudiante.php` (`estudiante.create`/`estudiante.store` con `role:administrador`). El index del admin quedó: Asuntos, Pagos, Ingresos y Egresos, Pagos Actualizados/Pendientes y Registrar Coordinador; el del coordinador: Estudiantes, Registrar Estudiante, Especialidades, Áreas y Carreras (+ Profesores/Postgrados aún placeholders). El método `creacionArea()` sigue en `AdministradorController` (la ruta vive en coordinador.php).
- **Listado de estudiantes restilado con estética glass (2026-10-14):** `gestionestudiantes.blade.php` con estilos scopeados en la vista (panel translúcido + blur, tabla glass, botones de acción circulares glass con confirmación de borrado). No toca `style.css` ni otras tablas; si se quiere el look global, replicar el patrón en `style.css`.
- Sin CI, sin lint/typecheck. `vendor/bin/pint` es el único formatter.
- **Actualización de seguridad de dependencias (2026-10-12):** `composer audit` reportaba 46 avisos; se actualizaron `guzzlehttp/guzzle` (7.9.3→7.15.5) y `guzzlehttp/psr7` (2.7.1→2.13.1), quedando **33 avisos pendientes** (commonmark, Symfony, laravel/framework). `composer.lock` commiteado el 2026-10-12. Composer no está en PATH: usar `C:\laragon\bin\composer\composer.bat`.

## Aprendizajes y errores a evitar

- Los tests con BD fallan por diseño en este entorno (`pdo_sqlite` no habilitado en CLI PHP 8.3); evaluar regresiones por mensaje de error, no por conteo de fallos.
- Un archivo de rutas nuevo no hace nada hasta añadir su `require` en `routes/web.php`.
- No confiar en documentación que mencione CI/lint/typecheck: no existen.
- El parser de CSV de Google Sheet en `PagoController::verificarPagos` es sensible a la región (`;`/`,`, montos `8.700,00`): conservarlo intacto.
- Iconos Font Awesome inválidos fallan en silencio (ej.: `fa-bolli sign` → corregido a `fa-coins`).
- `bcv.org.ve` no es accesible desde este entorno (timeout); `https://bcv.today/api/v1/rate.json` sí responde y replica la tasa oficial del BCV. En local Windows el scheduler de Laravel **no corre solo**: lo que mantiene la tasa fresca es el fallback perezoso + el botón BCV.

## Decisiones (y por qué)

- **Ingresos = solo pagos verificados**: el usuario quiere ver dinero confirmado, no pendientes.
- **Egresos en Bs. + captura de `tasa_momento`**: consistente con cómo el estudiante registra sus pagos; permite un balance real.
- **Lista fija de motivos + "Otro" con detalle obligatorio**: datos ordenados/filtrables con flexibilidad.
- **Egresos en modo solo lectura (crear/ver)**: decisión explícita del usuario para una primera versión.
- **Reglas en AGENTS.md**: (1) no ejecutar ningún comando sin permiso previo; (2) antes de escribir código, presentar un plan de ideas/implementaciones para revisión.
- **Historial de tasas claveado por día calendario (`date` de la API), no por `effective_date`**: así la tasa guardada para un día es la vigente ese día; respeta la captura "tasa del día del depósito" en `PagoController`.
- **Input manual de tasa conservado** marcado `fuente: 'manual'`: red de emergencia si la API del BCV cae; el botón BCV es la vía normal.
- **Zona horaria de la app = America/Caracas** (ya estaba en `config/app.php`): el "cambio de día" de la tasa ocurre en la medianoche de Caracas.
- **Panel del administrador = solo pagos/asuntos/finanzas (+ registrar coordinador); bloque académico (estudiantes, especialidades, áreas/carreras) = coordinador general**: decisión explícita del usuario; las rutas se movieron conservando nombres para minimizar riesgo.

## Próximos pasos

- **Pendiente de seguridad (importante):** resolver los 33 avisos restantes de `composer audit` con un `composer update` completo + prueba posterior (verificar sobre todo el flujo de verificación de pagos con Google Sheet). Guardar respaldo de `composer.lock` antes; rollback = `git restore composer.json composer.lock` + `composer install`.
- Módulo financiero: editar/eliminar egresos, filtro por rango de fechas, pestaña de pagos pendientes, exportar a PDF/Excel.
- Habilitar `pdo_sqlite` en `php.ini` para poder correr tests con BD.
- Corregir referencias a la ruta inexistente `dashboard` (`layouts/navigation.blade.php` y tests Breeze).
- **Seguridad (pendiente, decide el usuario cuándo):** la URL del remoto `origin` tiene un Personal Access Token embebido en `.git/config` (texto plano). Pendiente: revocarlo en GitHub, hacer `git remote set-url origin https://github.com/Facendo/postudo.git` y autenticar vía Git Credential Manager.
- **Tasa BCV verificada (2026-10-14):** `php artisan tasa:actualizar-bcv` OK (Bs. 877,90, vigencia 14/10/2026) y las 2 rutas registradas. El historial conservó los registros manuales previos (06,07,08,12/10) y añadió el del 14/10. Verificado en vivo post-16:30: la API NO adelanta la tasa del día siguiente (sigue `date`/`effective_date` = hoy); el cambio a tasa nueva ocurre al avanzar el día calendario (medianoche Caracas). Al llevar la app a hosting con cron: crear la entrada `* * * * * php artisan schedule:run`.
