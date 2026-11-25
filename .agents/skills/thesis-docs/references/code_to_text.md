# Protocolo Code-to-Text (Alineación Código a Texto)

Inspirado en los principios de `1-pluto1/thesis-writing-skills`, este protocolo garantiza que la documentación de la tesis refleje con **exactitud matemática y fáctica el estado real del repositorio**, eliminando cualquier riesgo de alucinación o discrepancia arquitectónica.

---

## 1. La Regla de Oro: Evidencia Antes de Redacción

Antes de redactar cualquier especificación técnica, diagrama o párrafo explicativo en la tesis:
1. **Inspección obligatoria de fuentes primarias:** El agente debe verificar los archivos fuente del proyecto (`database/migrations/`, `app/Models/`, `app/Http/Controllers/`, `app/Services/`, `routes/`).
2. **Prohibición de entidades ficticias:** Nunca inventar tablas, atributos, roles o servicios que no existan en el código fuente.
3. **Consulta de historial técnico:** Consultar [MEMORY.md](file:///c:/laragon/www/postudo/MEMORY.md) para documentar el "por qué" de las decisiones técnicas y evolución del sistema.

---

## 2. Guía de Extracción por Componente

### A. Diccionario de Datos y Modelo de Persistencia
- **Fuentes:** `database/migrations/*.php` y `app/Models/*.php`.
- **Procedimiento:**
  1. Revisar las migraciones definitivas en orden cronológico.
  2. Identificar el nombre exacto de la tabla y sus campos.
  3. Extraer el tipo de dato SQL real, si permite nulos (`nullable()`), valores por defecto y relaciones foráneas con sus políticas de integridad referencial (`onDelete('cascade')`, `onDelete('set null')`).
- **Formato estándar para la tesis:**

| Campo | Tipo | Nulo | Clave | Descripción |
| :--- | :--- | :---: | :---: | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Identificador único autoincremental. |
| `codigo_mencion` | VARCHAR(20) | Sí | FK | Código de la mención asociada (ON DELETE SET NULL). |
| `estado` | ENUM('pendiente','aprobada','rechazada') | No | - | Estado actual del registro (default: 'pendiente'). |

---

### B. Módulos y Reglas de Negocio
- **Fuentes:** `app/Http/Controllers/` y `app/Services/`.
- **Procedimiento:**
  1. Extraer las reglas de validación exactas de los métodos `store()` o `update()` (o de los FormRequests).
  2. Documentar las fórmulas y cálculos matemáticos tal como están programados (ej. cálculo de cupos acumulables `cupos - consumido`, conversión de montos `valor_usd * tasa_momento`, cálculo automático de fecha fin a 4 años).
  3. Identificar servicios especializados desacoplados:
     - `TasaCambioService`: Gestión de divisas y sincronización BCV vía API JSON.
     - `GeneradorPlanillaPreinscripcion`: Manipulación de documentos Word OpenXML y plantillas `.docx`.
     - `NotificacionService`: Mecanismo de mensajería interna con fan-out de 1 fila por destinatario.

---

### C. Matriz de Control de Acceso y Rutas (RBAC)
- **Fuentes:** `routes/web.php`, `routes/*.php` y `App\Http\Middleware\CheckRole.php`.
- **Procedimiento:**
  1. Mapear cada ruta a su controlador y método.
  2. Verificar los roles autorizados en el middleware:
     - `role:estudiante`
     - `role:administrador`
     - `role:coordinador_general`
     - `role:coordinador_postgrado`
     - Rutas públicas / de invitados (`@guest`).
- **Formato para la tesis (Matriz de Seguridad):**

| Módulo / Funcionalidad | Ruta | Método HTTP | Roles Autorizados |
| :--- | :--- | :---: | :--- |
| Solicitud de Apertura | `/coordinador/apertura` | POST | Coordinador de Postgrado |
| Aprobación de Cupos | `/coordinador/apertura/{id}/aprobar` | POST | Coordinador General |
| Conciliación de Pagos | `/administrador/pagos/verificar` | GET/POST | Administrador |

---

## 3. Matriz de Trazabilidad Metodológica

Para el Capítulo V, cada funcionalidad descrita en la tesis debe trazarse siguiendo esta cadena de consistencia:

$$\text{Objetivo Específico} \longrightarrow \text{Requerimiento Funcional (RF)} \longrightarrow \text{Controlador/Servicio} \longrightarrow \text{Caso de Prueba}$$

*Ejemplo:*
- **Obj. Específico 3:** Implementar el módulo de control financiero y conciliación de aranceles.
- **RF-08:** El sistema debe permitir la conciliación automatizada de pagos mediante hojas de cálculo externas.
- **Implementación:** `PagoController::verificarPagos()` + `storage/app/private/google_sheet_config.json`.
- **Prueba:** Caso de prueba CP-05 (Importación de CSV, verificación de delimitadores `;` y formato decimal venezolano).
