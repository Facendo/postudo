---
name: software-diagrams
description: >-
  Use this skill whenever the user requests software engineering diagrams, specifications, or architectural models:
  use case diagrams, system requirements (functional and non-functional), domain models, analysis models,
  analysis class diagrams (Boundary-Control-Entity / robustness), and component diagrams using Mermaid and Markdown.
---

# Skill: Software Diagrams & Modeling Guide

Esta skill proporciona las directrices, estándares y plantillas para elaborar especificaciones de ingeniería de software y diagramas visuales en sintaxis **Mermaid** compatibles con el entorno.

---

## 1. Principios Generales de Modelado

1. **Lenguaje y Contexto:** Todo el texto, nombres de entidades, casos de uso y explicaciones deben redactarse en **español** para mantener coherencia con el dominio del sistema.
2. **Mermaid Estricto:** Utilizar diagramas nativos compatibles:
   * Diagramas de flujo / Casos de uso / Componentes / Robustez: `flowchart TD` o `flowchart LR`.
   * Diagramas de Clases y Modelo de Dominio: `classDiagram` o `erDiagram`.
   * Diagramas de Secuencia: `sequenceDiagram`.
3. **Escapado de Caracteres en Mermaid:** Nunca usar caracteres especiales o paréntesis en las etiquetas sin entrecomillarlas, por ejemplo: `A["Estudiante (Usuario)"]`.
4. **Presentación:** Si la solicitud es extensa o comprende especificaciones de requisitos completas, generarlas en un artefacto Markdown estructurado para facilitar su visualización y consulta.

---

## 2. Tipos de Artefactos y Diagramas

Consulta las guías de referencia detalladas según el tipo de diagrama solicitado:

* [Casos de Uso y Requerimientos](./references/casos_uso.md):
  * Matriz de Requerimientos Funcionales (RF) y No Funcionales (RNF).
  * Ficha de especificación de Casos de Uso (Precondiciones, Flujo Principal, Alternos, Postcondiciones).
  * Diagrama de Casos de Uso en Mermaid (`flowchart LR` con actores `[\Actor/]`, casos `([Caso de Uso])` y límites de sistema `subgraph`).

* [Modelo de Dominio](./references/modelo_dominio.md):
  * Entidades conceptuales del negocio, atributos representativos y asociaciones/cardinalidades.
  * Diagrama de Modelo de Dominio en Mermaid (`classDiagram` conceptual o `erDiagram`).

* [Modelo de Análisis y Clases de Análisis (BCE)](./references/analisis_robustez.md):
  * Clasificación según el patrón de robustez de Ivar Jacobson:
    * **Frontera / Boundary (`<<boundary>>`):** Formularios, pantallas, vistas Blade, APIs.
    * **Control / Control (`<<control>>`):** Controladores, Services, manejadores de flujo.
    * **Entidad / Entity (`<<entity>>`):** Modelos de datos persistentes.
  * Diagramas de robustez con `flowchart LR` y diagramas de clases de análisis con `classDiagram`.

* [Diagrama de Componentes](./references/componentes.md):
  * Organización de la arquitectura en capas (Presentación, Negocio, Persistencia/Datos, Servicios Externos).
  * Diagrama de componentes físicos y lógicos en Mermaid (`flowchart TD` o `flowchart LR`).

---

## 3. Flujo de Trabajo al Generar Diagramas

1. **Análisis del Alcance:** Identificar los actores involucrados, los límites del sistema y las entidades clave del módulo en cuestión.
2. **Estructuración Textual:** Presentar primero la descripción clara de los requerimientos o del flujo.
3. **Generación del Diagrama en Mermaid:** Modelar con sintaxis limpia, usando colores o clases de estilo de Mermaid cuando agregue claridad.
4. **Trazabilidad:** Asegurar que cada caso de uso corresponda a requerimientos funcionales y que las entidades del análisis reflejen el modelo de dominio.
