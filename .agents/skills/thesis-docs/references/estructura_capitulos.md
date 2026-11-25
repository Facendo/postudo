# Estructura Estándar para Tesis y Trabajo de Grado de Ingeniería de Software

Esta guía define la estructura formal académica para la redacción de informes técnicos, proyectos de grado y tesis universitarias (formato estándar de ingeniería de computación/sistemas).

---

## Estructura General del Documento

1. **Páginas Preliminares:**
   - Portada institucional (Universidad, Núcleo, Escuela, Título, Autor, Tutor Académico, Fecha).
   - Dedicatoria y Agradecimientos (opcional).
   - Resumen y Abstract (Contexto, objetivo general, metodología, tecnologías y principales conclusiones, con 3-5 palabras clave).
   - Índice General, Índice de Tablas, Índice de Figuras.

---

## Capítulo I: El Problema

### 1.1 Planteamiento del Problema
- **Macro a Micro:** Contexto general de la educación de postgrado → Situación en la universidad/núcleo → Situación operativa de la Coordinación.
- **Síntomas y Causas:** Procesos manuales o basados en archivos aislados, dispersión de información de pagos, demora en generación de planillas oficiales, dificultad en la apertura y control de cupos por cohorte.
- **Consecuencias:** Sobrecarga administrativa, riesgos de inconsistencias financieras, falta de trazabilidad en las solicitudes.
- **Formulación del Problema:** Pregunta central de investigación (¿Cómo optimizar y automatizar los procesos de gestión académica, preinscripción y control de aranceles...?).

### 1.2 Objetivos de la Investigación
- **Objetivo General:** Desarrollar un sistema de información web para la gestión académica, control financiero de aranceles y procesos de preinscripción en la Coordinación de Estudios de Postgrado de la Universidad de Oriente.
- **Objetivos Específicos (Verbos medibles en infinitivo):**
  1. *Diagnosticar* la situación actual de los procesos de gestión de áreas, postgrados, cohortes, pagos y preinscripciones.
  2. *Determinar* los requerimientos funcionales y no funcionales que debe satisfacer la plataforma web.
  3. *Diseñar* la arquitectura de software, modelo de base de datos relacional y las interfaces de usuario bajo estándares institucionales.
  4. *Desarrollar* los módulos del sistema utilizando el framework Laravel, MySQL y servicios especializados desacoplados.
  5. *Validar* el correcto funcionamiento del sistema mediante la ejecución de casos de prueba integrales y pruebas de aceptación.

### 1.3 Justificación y Delimitación
- Justificación teórica, metodológica, práctica/social e institucional.
- Alcance espacial (Núcleo Anzoátegui de la UDO), temporal y temático (módulos contemplados).

---

## Capítulo II: Marco Teórico y Tecnológico

### 2.1 Antecedentes de la Investigación
- Resumen de al menos 3 a 5 investigaciones o proyectos de grado previos relacionados con sistemas de gestión de postgrados o control administrativo universitario (autor, año, título, aporte metodológico/técnico al proyecto).

### 2.2 Bases Teóricas
- Sistemas de Información Web transaccionales.
- Arquitectura Monolítica vs. Microservicios (justificación técnica del monolito MVC en Laravel).
- Patrón Modelo-Vista-Controlador (MVC).
- Integridad Referencial y normalización de bases de datos relacionales.
- Control de Acceso Basado en Roles (RBAC).

### 2.3 Bases Tecnológicas (Ecosistema Real del Proyecto)
- Lenguaje backend: **PHP 8.2+**.
- Framework backend: **Laravel 12** (Eloquent ORM, Blade templating, Service layer, Middlewares).
- Base de Datos: **MySQL**.
- Frontend: CSS nativo optimizado (`style.css`), Vite + Tailwind CSS v3 (para Breeze/Auth), tipografías institucionales Outfit e Inter.
- Integraciones y Servicios: Sincronización cambiaria automatizada con BCV, manipulación OpenXML `.docx` vía PhpWord / ZipArchive.

### 2.4 Definición de Términos Básicos
- Glosario técnico y de dominio (Cohorte, Mención, Cupo de apertura, Arancel, Conciliación bancaria, Voucher).

---

## Capítulo III: Marco Metodológico

### 3.1 Tipo y Nivel de Investigación
- Proyecto Factible / Investigación Aplicada de nivel descriptivo y proyectivo.

### 3.2 Metodología de Desarrollo de Software
- Justificación de la metodología seleccionada (ej. Modelo Ágil / Scrum adaptado, o Ciclo de Vida Incremental).
- Fases de la metodología:
  1. Fase I: Levantamiento y Análisis de Requerimientos.
  2. Fase II: Diseño de la Arquitectura y Base de Datos.
  3. Fase III: Codificación e Implementación Modular.
  4. Fase IV: Pruebas, Verificación y Refactorización.

---

## Capítulo IV: Análisis y Diseño del Sistema

*(Sección conectada directamente con la skill `software-diagrams`)*

### 4.1 Requerimientos del Sistema
- Matriz de Requerimientos Funcionales (RF-01 a RF-n) con código, nombre, prioridad y descripción.
- Matriz de Requerimientos No Funcionales (RNF: Rendimiento, Seguridad, Usabilidad, Compatibilidad).

### 4.2 Modelado de Negocio y Casos de Uso
- Actores del sistema: Estudiante, Administrador, Coordinador General, Coordinador de Postgrado.
- Diagramas de Casos de Uso en Mermaid (`flowchart LR`) y fichas de especificación formal.
  *(Ver [software-diagrams/references/casos_uso.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/casos_uso.md))*.

### 4.3 Análisis de Robustez (BCE)
- Diagramas Frontera-Control-Entidad para flujos críticos:
  - Registro y verificación de pagos vía Google Sheet.
  - Solicitud, aprobación y consumo de cupos de cohortes.
  - Proceso de preinscripción y generación de documento Word.
  *(Ver [software-diagrams/references/analisis_robustez.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/analisis_robustez.md))*.

### 4.4 Diseño de Datos
- Modelo Entidad-Relación (diagrama Mermaid `erDiagram`).
- Diccionario de Datos detallado con campos, tipos y restricciones FK extraído con el [Protocolo Code-to-Text](./code_to_text.md).

### 4.5 Arquitectura de Software
- Diagrama de Componentes por Capas (Presentación Blade, Controladores/Middlewares, Capa de Servicios de Negocio, Capa de Persistencia Eloquent, Almacenamiento Seguro).
  *(Ver [software-diagrams/references/componentes.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/componentes.md))*.

---

## Capítulo V: Implementación, Pruebas y Resultados

### 5.1 Entorno de Ejecución e Instalación
- Especificaciones de hardware y software del entorno (Laragon, servidor web Apache/Nginx, MySQL, PHP).

### 5.2 Implementación por Subsistemas
Descripción técnica profunda respaldada por capturas de pantalla de la interfaz:
1. **Subsistema de Estructura Académica:** Áreas → Postgrados → Menciones → Cohortes (eliminación de asignaturas obsoletas, regla de fecha fin calculada a 4 años, asociación 1:1 con coordinadores de postgrado).
2. **Subsistema de Solicitud y Apertura de Cohortes:** Flujo de cupos acumulables `cupos - consumido`, bloqueos transaccionales y aprobaciones por el Coordinador General.
3. **Subsistema de Preinscripción y Generación Documental:** Formulario público, validaciones estrictas de cédula/teléfonos, vinculación de comprobante bancario e inyección en plantilla OpenXML con centrado de tablas.
4. **Subsistema Financiero y Conciliación de Pagos:** Registro de pagos en Bs. con tasa BCV automatizada, importación y conciliación de extractos bancarios desde Google Sheet vía CSV.
5. **Subsistema de Notificaciones:** Mensajería interna fan-out entre perfiles.

### 5.3 Pruebas del Sistema
- Matriz de Casos de Prueba (ID, Objetivo, Entradas, Resultado Esperado, Resultado Obtenido, Estado Pasa/Falla).
- Pruebas de validación de entradas, integridad referencial y permisos de rol.

---

## Conclusiones y Recomendaciones

- **Conclusiones:** Un bloque conciso por cada objetivo específico formulado en el Capítulo I, destacando el logro técnico y el impacto en la Coordinación.
- **Recomendaciones:** Pasos futuros sugeridos (incorporación de pagos en línea en tiempo real, expansión de actas de grado, etc.).
- **Referencias Bibliográficas:** Listado normalizado de fuentes según normas IEEE o APA.
