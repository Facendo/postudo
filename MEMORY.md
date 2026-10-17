# MEMORY.md — PostUDO

Memoria del proyecto entre sesiones.

## Estado actual

- Laravel 12 monolito (Breeze) en Laragon/Windows, MySQL `postudo`, textos/comentarios/rutas en español.
- **Árbol académico reestructurado a Área → Postgrado → Mención (2026-10-16):**
  - Tablas `carrera` y `especialidades` **eliminadas** (migraciones `2026_10_16_100001..100004`); modelos y `CarreraController`/`EspecialidadesController` borrados.
  - Nueva tabla `mencion` (FK `id_postgrado` con cascade); `postgrado.codigo_area` (FK → `area`, con cascade) reemplazó a `codigo_especialidad` (backfill desde especialidad→carrera→área); `estudiantes.codigo_mencion` nullable con `onDelete('set null')` — **ningún estudiante existente se tocó** (quedan en NULL = "Sin postgrado asignado").
  - Nuevos: modelo `Mencion`, `MencionController` (rutas `administrador.mencion.*`, grupo `role:coordinador_general|administrador` porque los detalles del postgrado viven en el panel admin), vistas `administrador/gestionareas.blade.php` (hub ahora solo de áreas) y `administrador/estudiantesporpostgrado.blade.php` (antes `prueba_especialidades`; ruta renombrada a `administrador.estudiantes_por_postgrado`).
  - Registro/edición de estudiante: cascade Área → Postgrado → Mención; `estudiantes.carrera` y `especialidad` se conservan como **texto espejo** (postgrado y mención) para no tocar los listados.
  - Confirmación previa con impacto en cascada: `AreaController::impacto` y `PostgradoController::impacto` (JSON de conteos) → `confirm()` en `public/js/admin_options.js` (reescrito, solo áreas) y en `gestionpostgrado.blade.php`.
  - Catálogo público `/postgrado` ahora dinámico (`PostgradoController::portal`): Áreas → Postgrados → Menciones.
  - Inscripción del estudiante: ve solo el postgrado de su mención (o todos si no tiene); se le pasan `cohortes/materias/secciones` que antes no se pasaban (cascada rota). Arreglado además el chequeo de pago (antes `Pagos::...->first()->get()` fatal).
  - Seeder `RellenoAreasSeeder` actualizado (postgrados con `codigo_area` + 3 menciones); estudiantes sembrados sin mención (NULL) — si se quieren asignar, va en el seeder, nunca en migración.
  - **Rediseño de la página de Áreas (2026-10-16):** vista **con el mismo patrón que la lista de Asuntos** (petición del usuario: "nada de cuadros dentro de cuadros"): título con `.content_texto_bienvenida` + toolbar `.areas-toolbar` (botón `button_body` y pastilla `.contador-vista`) **fuera de cualquier caja**, y la tabla **sin caja propia** (se eliminó `.tabla-glass`; usa el estilo global `table` de `style.css`, iconos `.btn-table-action`, contador de postgrados en texto plano). Listado pintado con Blade (todas las áreas) y **fila expandible** con los postgrados en lista sin tarjetas; la fila de detalle se oculta con la **clase `.hidden`** (no el atributo `hidden`), porque el CSS global de tablas fuerza `tr{display:block}` en móvil. `admin_options.js` ya no pinta datos: solo desplegar filas (guard sobre `.table-actions`), formulario crear/editar (PUT por `data-*`, código readonly) y confirm con impacto; tras un error de validación oculta el listado y, si `old('codigo')` existe en la tabla y el error no es de `codigo`, restaura el modo edición. Solo el formulario de crear/editar conserva panel glass (un cuadro a la vez). La tabla muestra **Nombre·Descripción·Postgrados·Acciones** (columna «Código» retirada el 2026-10-16: la flecha de desplegar vive en la celda del nombre y el código sigue en `data-codigo` de la fila y en el formulario de edición).
- **Estilo «sin card» extendido a 6 vistas admin (2026-10-16):** `gestionpostgrado`, `gestioncohorte`, `gestionmaterias`, `detallespostgrado`, `detallescohortes` y `detallesmaterias` dejaron `.main-content-section` (la card roja `#570e0ef5` de `style.css`) por un `div.page-content` plano como asuntos/áreas, con iconos `.btn-table-action` en lugar de pastillas `button_body`; sin pastillas rojas de contador (petición explícita). **Quedan con la card** (no tocados): `gestionevaluacion`, listas de profesor/estudiante y perfiles.
- **Menciones desde la lista de postgrados (2026-10-16):** en `gestionpostgrado` la fila de cada postgrado se despliega (patrón de Áreas: flecha en el nombre, clase `.hidden`, guard sobre `.table-actions`) mostrando sus menciones con 🗑️ (confirm con aviso de estudiantes) + mini-form «Nueva mención» anclado al postgrado. `MencionController::store/destroy` redirigen ahora con `back()` (`update` sigue yendo a `showdetalles`); `PostgradoController::index` eager-loads `menciones`; validación con `@error('nombre')` sobre la tabla (el banner de éxito lo pinta `x-layout`).
- **Módulo financiero (2026-10-09):** tabla `egreso` + `EgresoController`/`Egreso`; ingresos = pagos `estado='Actualizado'`; egresos solo crear/ver; `tasa_momento` + `registrado_por`.
- **Tasa BCV automática (2026-10-14):** `App\Services\TasaCambioService` con `tasa_cambio.json` + historial; fuente `https://bcv.today/api/v1/rate.json`; comando `tasa:actualizar-bcv`, botón en panel y fallback perezoso (scheduler NO corre solo en Windows local).
- **Reorganización de paneles (2026-10-14):** bloque académico (estudiantes, áreas) = coordinador general; admin = pagos/asuntos/finanzas. **Los nombres de ruta siguen con prefijo `administrador.*`** para no tocar vistas. `creacionArea()` vive en `AdministradorController` con la ruta en `routes/coordinador.php`.
- Sin CI, sin lint/typecheck. `vendor/bin/pint` es el único formatter (corrido el 2026-10-16: 48 detalles de estilo).
- **Subida a `origin/Yan` (2026-10-16):** un solo commit «Árbol académico Área → Postgrado → Mención y rediseño de la vista de Áreas» (feature + rediseño + Pint, desde `446d8b7`); `.opencode/` añadido a `.gitignore`.
- `composer audit`: 33 avisos pendientes (commonmark, Symfony, laravel/framework). Composer no está en PATH: `C:\laragon\bin\composer\composer.bat`.

## Aprendizajes y errores a evitar

- Los tests con BD fallan por diseño en este entorno (`pdo_sqlite` no habilitado en CLI PHP 8.3); evaluar regresiones por mensaje de error, no por conteo de fallos.
- Un archivo de rutas nuevo no hace nada hasta añadir su `require` en `routes/web.php`. Las rutas de menciones se añadieron **dentro** de grupos existentes (coordinador/administrador).
- Al borrar columnas/tablas con FK: usar `dropForeign(['columna'])` (nombre por defecto `{tabla}_{columna}_foreign`) **antes** de `dropColumn`/`dropIfExists`.
- `CheckRole` acepta varios roles: `role:coordinador_general|administrador` (hace `explode('|')`).
- El parser de CSV de Google Sheet en `PagoController::verificarPagos` es sensible a la región: conservarlo intacto.
- `bcv.org.ve` no responde desde este entorno; sí `https://bcv.today/api/v1/rate.json`.

## Decisiones (y por qué)

- **La mención cuelga del postgrado (no del área)**: estructura uno-a-muchos simple; el área solo agrupa postgrados.
- **Texto espejo en `estudiantes.carrera`/`especialidad`** (postgrado y mención elegidos): así los listados y tablas de pagos siguieron funcionando sin tocarlos; al editar, si no se elige mención se conservan los textos actuales.
- **Estudiantes existentes intactos (NULL en `codigo_mencion`)**: decisión explícita del usuario; más adelante crearán estudiantes ya con su postgrado.
- **Cascada de borrado con confirmación previa** que muestra el impacto (conteos vía endpoint JSON) en vez de restringir: decisión del usuario.
- **Menciones: renombrar en "Detalles del Postgrado"; crear/borrar también desde la lista de postgrados** (fila desplegable con mini-form, 2026-10-16): ahí está el padre natural; rutas con doble rol para que sirva si la gestión de postgrados se mueve al coordinador.
- **Ingresos = solo pagos verificados; egresos en Bs. con `tasa_momento`**: decisiones del usuario.
- **Reglas en AGENTS.md**: (1) no ejecutar comandos sin permiso; (2) plan antes de escribir código; (3) actualizar MEMORY.md.

## Próximos pasos

- **Segunda parte de cambios (pendiente, el usuario dará detalles):** posiblemente códigos/nombres reales de los postgrados y qué "cosas" se añadirán a las áreas.
- **`EstudianteController::inscribir()` NO existe** (la ruta `estudiante.inscribir` con PUT está registrada y el formulario de inscripción la usa) → el paso final de inscripción falla. Preexistente; decidir con el usuario qué hace (¿inserta en `listado`? ¿controla cupo?).
- **Postgrados/cohortes con doble rol (2026-10-16, hecho):** las 8 rutas de gestión de postgrado y las 7 de cohortes salieron del grupo `role:administrador` a un grupo propio `role:administrador|coordinador_general` en `routes/administrador.php` (mismas URLs y nombres; se quitó el `->middleware('role:administrador')` por ruta que tenían las cohortes); tarjeta «Postgrados» del panel del coordinador enlazada (antes `href="#"`). **Límite:** materias/secciones siguen solo-admin → 403 si el coordinador baja desde «Detalles de Cohorte» a «materias». **Hecho además:** botón «Nuevo Postgrado» en la cabecera del área desplegada de `/coordinador/areas` (enlace `...create?area=COD`; `PostgradoController::create()` lo preselecciona respetando `old()`, y tras guardar el coordinador vuelve a Áreas —el administrador, a su listado—). **Pendientes:** editar `id_postgrado` tras crearlo (cascada sobre `cohorte.codigo_postgrado`/`mencion.id_postgrado`); tarjeta «Profesores» del coordinador sigue en `#`.
- `AreaController::edit()` sigue vacío: la edición de áreas es por JS en la misma página.
- Pendiente de seguridad: resolver los 33 avisos de `composer audit` con `composer update` + prueba (respaldo de `composer.lock` primero).
- `destroy()` de `Postgrado`/`Cohorte`/`Materias`/`Seccion`/`Asunto` hacen `find($id)->delete()` sin comprobar null → error fatal «Call to a member function delete() on null» con doble clic o registro ya borrado. **Decidido (2026-10-16): se deja así por ahora** (causado por un doble clic del usuario); arreglo propuesto y no aplicado: null-check + `session('error')` + deshabilitar botón tras confirmar.
- Habilitar `pdo_sqlite` en `php.ini` para correr tests; corregir referencias a la ruta inexistente `dashboard` (layouts y tests Breeze).
- **Seguridad:** el PAT de GitHub está en texto plano en `.git/config`; revocarlo y usar Git Credential Manager.
