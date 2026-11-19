# Guía de Casos de Uso y Requerimientos del Sistema

Esta referencia detalla el estándar para documentar requerimientos del sistema y diagramas de casos de uso.

---

## 1. Requerimientos del Sistema

### Matriz de Requerimientos Funcionales (RF)
| Código | Nombre | Descripción | Prioridad | Actor Principal |
| :--- | :--- | :--- | :--- | :--- |
| **RF-01** | *Nombre del Requisito* | *Descripción clara de la capacidad que el sistema debe proveer.* | Alta / Media / Baja | *Rol involucrado* |

### Matriz de Requerimientos No Funcionales (RNF)
| Código | Categoría | Descripción | Métrica / Criterio |
| :--- | :--- | :--- | :--- |
| **RNF-01** | Seguridad / Rendimiento / Usabilidad | *Descripción del atributo de calidad o restricción técnica.* | *Criterio verificable* |

---

## 2. Ficha de Especificación de Caso de Uso (Formato Estándar)

* **Código:** `CU-XX`
* **Nombre:** *Verbo en infinitivo + objeto (ej. Registrar Preinscripción)*
* **Actor Principal:** *Rol que inicia la acción*
* **Actores Secundarios:** *Sistemas o roles que intervienen pasivamente*
* **Precondiciones:**
  1. *Condición 1 necesaria antes de iniciar.*
* **Flujo Principal (Escenario Ideal):**
  1. El actor solicita...
  2. El sistema valida...
  3. El actor introduce los datos...
  4. El sistema almacena y confirma...
* **Flujos Alternativos / Excepciones:**
  * **2a. Datos inválidos:** El sistema resalta los campos con error y solicita corrección.
  * **4a. Fallo de conexión externa:** El sistema notifica al usuario y reintenta más tarde.
* **Postcondiciones:**
  * *Estado final del sistema tras completarse con éxito.*

---

## 3. Diagrama de Casos de Uso en Mermaid

Para representar casos de uso en Mermaid, se utiliza `flowchart LR` con subgrafos que delimitan la frontera del sistema:

* **Actores:** `actorId["fa:fa-user NombreActor"]` o nodos con forma de persona/caja estilizada.
* **Casos de Uso:** Nodos con bordes redondeados u óvalos `([CU-01: Nombre del Caso])`.
* **Relaciones:**
  * Asociación simple: `-->`
  * Inclusión: `-.->|"<<include>>"|`
  * Extensión: `-.->|"<<extend>>"|`
  * Generalización: `---|Herencia|`

### Ejemplo en Mermaid:
```mermaid
flowchart LR
    %% Actores
    subgraph Actores[" "]
        direction TB
        A["👤 Aspirante"]
        CP["👤 Coordinador Postgrado"]
    end

    %% Límite del Sistema
    subgraph Sistema["Sistema PostUDO"]
        CU01(["CU-01: Llenar Planilla de Preinscripción"])
        CU02(["CU-02: Descargar Planilla .docx"])
        CU03(["CU-03: Validar Datos Formato"])
        CU04(["CU-04: Revisar Preinscripciones"])
        CU05(["CU-05: Aprobar / Rechazar Preinscripción"])
    end

    %% Asociaciones
    A --> CU01
    CU01 -.->|"<<include>>"| CU03
    CU01 -.->|"<<include>>"| CU02

    CP --> CU04
    CP --> CU05
    CU05 -.->|"<<extend>>"| CU04
```
