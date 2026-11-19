# Guía de Modelo de Análisis y Clases de Análisis (BCE)

El **Modelo de Análisis** sirve como puente entre los casos de uso (los requisitos) y el diseño de la arquitectura. Utiliza el patrón **BCE (Boundary - Control - Entity)** introducido en el proceso unificado (RUP / Jacobson).

---

## 1. Estereotipos de Análisis

1. **Frontera (`<<boundary>>` / Interfaz):**
   * Modela la interacción entre el sistema y sus actores externos.
   * Representa formularios, páginas Blade, diálogos modales, respuestas JSON o endpoints de APIs externas.
   * *Regla:* Los actores solo se comunican directamente con clases Frontera.

2. **Control (`<<control>>` / Coordinador):**
   * Encapsula la lógica de control, casos de uso, orquestación de transacciones y reglas de negocio del flujo.
   * Representa Controllers, Services, Listeners o Handlers.
   * *Regla:* Un controlador coordina objetos frontera y objetos entidad.

3. **Entidad (`<<entity>>` / Datos):**
   * Modela la información persistente y duradera del sistema.
   * Representa modelos de datos o registros de negocio (ej. `Postgrado`, `Cohorte`, `Preinscripcion`).
   * *Regla:* Las entidades no se comunican directamente con las fronteras; siempre median los objetos de control.

---

## 2. Reglas de Conexión de Robustez

| Desde \ Hacia | Frontera (`<<boundary>>`) | Control (`<<control>>`) | Entidad (`<<entity>>`) |
| :--- | :--- | :--- | :--- |
| **Actor** |  Válido |  Inválido |  Inválido |
| **Frontera** |  Inválido |  Válido |  Inválido |
| **Control** |  Válido |  Válido |  Válido |
| **Entidad** |  Inválido |  Válido |  Válido |

---

## 3. Diagrama de Robustez en Mermaid (`flowchart LR`)

```mermaid
flowchart LR
    %% Actores
    A["👤 Aspirante"]

    %% Clases Frontera
    subgraph Fronteras["<<boundary>> Fronteras"]
        F1["FormularioPreinscripcionView"]
        F2["DescargaPlanillaResponse"]
    end

    %% Clases de Control
    subgraph Controles["<<control>> Controles"]
        C1["PreinscripcionController"]
        C2["GeneradorPlanillaService"]
    end

    %% Clases Entidad
    subgraph Entidades["<<entity>> Entidades"]
        E1[("Preinscripcion")]
        E2[("Cohorte")]
        E3[("Mencion")]
    end

    %% Flujos de interacción
    A -->|1. Enviar formulario| F1
    F1 -->|2. Invocar store| C1
    C1 -->|3. Validar ventana activa| E2
    C1 -->|4. Persistir registro| E1
    E1 -.->|asociada a| E3
    C1 -->|5. Solicitar generación docx| C2
    C2 -->|6. Retornar archivo .docx| F2
    F2 -->|7. Descarga automática| A
```

---

## 4. Diagrama de Clases de Análisis (`classDiagram`)

Representa las clases con sus estereotipos y métodos de análisis conceptuales:

```mermaid
classDiagram
    direction TB

    class FormularioPreinscripcionView {
        <<boundary>>
        +mostrarFormulario()
        +capturarDatos()
        +mostrarErroresValidacion()
    }

    class PreinscripcionController {
        <<control>>
        +store(Request)
        +aprobar(id)
        +rechazar(id, motivo)
        -validarReglas()
    }

    class GeneradorPlanillaService {
        <<control>>
        +generarDocx(Preinscripcion)
        -reemplazarPlaceholders()
    }

    class Preinscripcion {
        <<entity>>
        +String cedula
        +String estado
        +Object datos_personales
        +cambiarEstado(nuevoEstado)
    }

    FormularioPreinscripcionView ..> PreinscripcionController : solicita
    PreinscripcionController ..> GeneradorPlanillaService : delega
    PreinscripcionController --> Preinscripcion : crea y consulta
```
