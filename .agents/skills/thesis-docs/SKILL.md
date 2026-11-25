---
name: thesis-docs
description: >-
  Directrices y metodologías para redactar y documentar el proyecto de software como una tesis académica
  o Trabajo Especial de Grado (TEG). Aplica alineación estricta Código-a-Texto (sin alucinaciones),
  estructura formal de 5 capítulos, tono impersonal y conexión directa con los diagramas de ingeniería de software.
---

# Skill: Documentación Académica y Tesis de Software (TEG)

Esta skill proporciona las normas, metodologías y plantillas formales para documentar el proyecto de software bajo el estándar de una **Tesis Universitaria, Trabajo Especial de Grado (TEG) o Memoria Técnica de Ingeniería**.

Combina los estándares de redacción científica de `santifs/thesis-writing-skill` y la metodología de verificación fáctica de `1-pluto1/thesis-writing-skills`, integrada con los diagramas de ingeniería del proyecto.

---

## 1. Principios Fundamentales de Redacción

1. **Voz y Tono Académico:**
   - Redacción rigurosa en **tercera persona o voz pasiva impersonal** ("Se implementó", "El sistema permite", "Se analizó", en lugar de "Hicimos", "Creé" o "Nuestro sistema").
   - Cero adjetivos comerciales o de marketing ("solución innovadora de vanguardia", "revolucionario"). La validez se demuestra con métricas, arquitectura sólida y pruebas comprobables.
2. **Alineación Código-a-Texto (Cero Alucinaciones):**
   - Todo módulo, tabla, relación foránea o flujo documentado debe corresponderse con la realidad del código fuente en Laravel (`database/migrations/`, `app/Models/`, `app/Http/Controllers/`, `routes/web.php`).
3. **Consistencia Metodológica:**
   - Todo requerimiento funcional debe responder a un objetivo específico, estar respaldado por un caso de uso o diagrama de robustez, y validarse mediante un caso de prueba.

---

## 2. Estructura de Capítulos de la Tesis

La documentación se organiza en los 5 capítulos clásicos de ingeniería de software:

- **Capítulo I: El Problema:** Planteamiento contextualizado, formulación, objetivo general, objetivos específicos medibles, justificación (teórica, práctica, institucional) y delimitación.
- **Capítulo II: Marco Teórico y Tecnológico:** Antecedentes formales, bases teóricas de ingeniería de software y justificación técnica del stack (PHP 8.2+, Laravel 12, MySQL, arquitectura monolítica MVC, librerías OpenXML).
- **Capítulo III: Marco Metodológico:** Tipo de investigación (proyecto factible/aplicada) y fases del ciclo de vida del software seleccionado.
- **Capítulo IV: Análisis y Diseño del Sistema:** Especificación formal de requerimientos, modelado de negocio, robustez BCE, diseño de datos y arquitectura.
- **Capítulo V: Implementación y Pruebas:** Detalle técnico de los subsistemas reales, casos de prueba ejecutados y evaluación de resultados.
- **Conclusiones y Recomendaciones.**

Para ver la plantilla completa y los subtítulos sugeridos de cada capítulo, consulta:
👉 [Estructura Detallada de Capítulos](./references/estructura_capitulos.md)

---

## 3. Conexión con la Skill `software-diagrams`

Para todo el modelado técnico del **Capítulo IV**, esta skill se apoya directamente en las guías de [software-diagrams](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/SKILL.md):

| Sección de la Tesis | Herramienta / Estándar | Referencia en `software-diagrams` |
| :--- | :--- | :--- |
| **Requerimientos (RF/RNF)** | Tablas formales de requerimientos | [casos_uso.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/casos_uso.md) |
| **Casos de Uso** | Diagrama `flowchart LR` con actores y fichas | [casos_uso.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/casos_uso.md) |
| **Análisis de Robustez** | Diagramas Frontera-Control-Entidad (BCE) | [analisis_robustez.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/analisis_robustez.md) |
| **Modelo de Datos (ER)** | Diagrama Mermaid `erDiagram` y diccionario | [modelo_dominio.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/modelo_dominio.md) |
| **Arquitectura del Sistema** | Diagrama de componentes por capas | [componentes.md](file:///c:/laragon/www/postudo/.agents/skills/software-diagrams/references/componentes.md) |

---

## 4. Guías de Referencia de Esta Skill

Consulta los archivos complementarios para ejecutar tareas específicas:
- [Protocolo Code-to-Text](./references/code_to_text.md): Reglas para auditar e inspeccionar migraciones, modelos, controladores y rutas antes de redactar.
- [Estructura Detallada de Capítulos](./references/estructura_capitulos.md): Índice analítico y contenido requerido para los capítulos I al V.

---

## 5. Checklist de Calidad Académica

Antes de dar por finalizado un capítulo o sección de la tesis:
- [ ] ¿Está redactado en tercera persona/impersonal y con tono sobrio y formal?
- [ ] ¿Los nombres de tablas, campos y relaciones coinciden al 100% con las migraciones reales?
- [ ] ¿Los objetivos específicos inician con verbos medibles en infinitivo?
- [ ] ¿Cada requerimiento funcional está respaldado por su caso de uso y caso de prueba?
- [ ] ¿Los diagramas Mermaid son sintácticamente válidos y usan etiquetas entrecomilladas?
- [ ] ¿Se citaron las fuentes y antecedentes en formato académico normalizado (IEEE o APA)?
