# Guía de Referencia: Micro-UI y Pulido Visual

Inspirada en las mejores prácticas de ingeniería de diseño de interfaces (`better-ui`), adaptada a las convenciones y estilos de PostUDO.

---

## 1. Geometría y Radios de Borde (Border Radius)

### La Regla del Radio Anidado
Cuando un elemento hijo con fondo o borde está dentro de un contenedor con borde redondeado, **nunca uses el mismo radio de borde en ambos**:
$$\text{radio\_interior} = \max(0, \text{radio\_exterior} - \text{padding})$$

*Ejemplo:*
- Si el contenedor exterior tiene `border-radius: 12px` y `padding: 8px`:
  - El radio interior debe ser $12 - 8 = 4\text{px}$.
  - Usar 12px en el hijo genera esquinas ópticamente deformadas con huecos irregulares.

### Escala de Radios Coherente
- **Micro-elementos (Badges, tags, píldoras de estado):** `rounded-full` si el padding es simétrico, o `rounded-md` (4-6px).
- **Botones y campos de formulario:** `rounded-lg` (8px) o `rounded-md` (6px) para un aspecto sobrio e institucional.
- **Evitar:** `rounded-3xl` o bordes de 24px en botones o tarjetas normales (cliché típico de IA).

---

## 2. Sombras Fieles y Elevación Nítida

### Evitar el Resplandor Borroso de IA
Las interfaces generadas por IA suelen usar `shadow-2xl` difusas o resplandores de color (glow violeta/azul) que hacen flotar los elementos sin anclaje real.

### Técnica de Sombra en 2 Capas (Contacto + Elevación)
En interfaces claras, las superficies reales proyectan una sombra de contacto pequeña y oscura, más una sombra difusa amplia y suave:

```css
/* Elevación sutil para menús desplegables, tooltips o modales en PostUDO */
box-shadow: 
  0 1px 2px 0 rgba(0, 18, 51, 0.06),   /* Capa 1: contacto nítido */
  0 4px 16px 0 rgba(0, 18, 51, 0.08);  /* Capa 2: elevación suave */
```

Para elementos interactivos en reposo, preferir un borde sutil de 1px (`var(--borde)`) antes que depender de sombras.

---

## 3. Alineación Óptica (Optical Alignment)

### Icono + Texto en Botones y Enlaces
1. **Compensación de peso:** El grosor del trazo del icono debe coincidir con el peso de la tipografía vecina. Si el texto es `font-medium`, el icono debe tener un peso regular/medio, no ultra grueso ni fino como un cabello.
2. **Espaciado consistente:** El espacio entre icono y etiqueta debe ser compacto (ej. `gap: 6px` a `8px`). Si supera 12px, el icono y el texto se leen como dos elementos separados.
3. **Centrado visual de formas asimétricas:**
   - Iconos como triángulos de reproducción (`fa-play`), flechas (`fa-arrow-right`) o chevrones tienen más masa visual en un extremo.
   - En botones circulares o cuadrados, desplazar el icono 1-1.5px hacia la masa menor para que se vea ópticamente centrado.

---

## 4. Estados Táctiles y Accesibilidad

1. **Estado Activo (Press Feedback):**
   - Para botones primarios y botones de acción en tabla:
     ```css
     .btn-interactivo:active {
       transform: scale(0.98);
     }
     ```
2. **Anillo de Foco (Focus Visible):**
   - No eliminar el outline sin reemplazarlo.
   - Usar un anillo con separación (`offset`) para que no quede pegado al borde del elemento:
     ```css
     .form-input:focus-visible,
     .btn:focus-visible {
       outline: 2px solid var(--accent-orange, #f7941d);
       outline-offset: 2px;
     }
     ```
3. **Cursor Semántico:**
   - `cursor: pointer` únicamente en elementos clicables que disparan acciones (botones, enlaces, filas seleccionables).
   - No aplicar `cursor: pointer` a tarjetas completas que contienen múltiples enlaces internos independientes.
