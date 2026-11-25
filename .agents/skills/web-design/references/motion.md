# Guía de Referencia: Animación y Motion Design

Inspirada en las mejores prácticas de ingeniería de movimiento (`better-motion`), adaptada a los aprendizajes y patrones de PostUDO.

---

## 1. Principio Fundamental: La Animación es Informativa, no Decorativa

1. **Evitar la "Fiesta de Rebotes":** La IA suele generar animaciones lentas con rebotes exagerados (`ease-in-out` largo de 600ms o efectos de rebote) que hacen que una aplicación de gestión universitaria se sienta poco profesional y lenta.
2. **Jerarquía:** No todo en la pantalla debe animarse al mismo tiempo. Anima la llegada del contenedor principal o la aparición de un modal; los datos de las tablas deben ser legibles de inmediato sin retrasos innecesarios.

---

## 2. Duraciones y Curvas de Aceleración Recomendadas

| Tipo de Interacción | Duración | Curva de Aceleración (Easing) | Propósito |
| :--- | :--- | :--- | :--- |
| **Microinteracción** (hover de botón, cambio de color, foco) | `100ms - 150ms` | `ease-out` o `linear` | Respuesta instantánea al usuario; >200ms se percibe "lento". |
| **Menús desplegables / Tooltips** | `150ms - 200ms` | `cubic-bezier(0.16, 1, 0.3, 1)` | Entrada ágil con asentamiento suave. |
| **Filas desplegables / Acordeones** | `200ms - 250ms` | `cubic-bezier(0.2, 0.8, 0.2, 1)` | Sensación de deslizamiento físico natural. |
| **Modales / Diálogos de Confirmación** | `250ms - 300ms` | `cubic-bezier(0.16, 1, 0.3, 1)` + escala leve (0.96 → 1) | Presencia firme sin flotación excesiva. |
| **Entrada de página (Fade Up)** | `300ms - 400ms` | `ease-out` con traslado corto (máx. 16px - 24px) | Transición fluida sin recorrido exagerado. |

---

## 3. Reglas Técnicas Críticas (Lecciones del Proyecto)

### A. Finalizar siempre en `transform: none`
Al animar la entrada de un contenedor con `keyframes`:
```css
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: none; /* NO usar translateY(0) retenido con forwards */
  }
}
```
> [!WARNING]
> Si la regla final retiene `transform: translateY(0)`, el navegador convierte ese contenedor en un **containing block** para todos sus descendientes `position: fixed` (como widgets flotantes, widgets de tasa de cambio o modales), rompiendo su posición en la ventana.

### B. Prevenir Parpadeos de la Barra de Scroll
- No aplicar `translateY(+24px)` al **último elemento** del pie de página si ya toca el límite inferior del viewport. El desplazamiento temporal hacia abajo generará una barra de scroll vertical que desaparecerá al finalizar la animación, causando un "salto" o recolocación molesta de la vista.
- Solución: Animar un elemento hijo interior que tenga margen inferior de seguridad, o aplicar `overflow-y: clip` en el contenedor padre.

### C. Escalonamiento Controlado (Stagger)
Si varios bloques entran consecutivamente, usar desfases breves de tiempo:
- Encabezado / Barra: retardo `0s`.
- Contenido principal: retardo `0.1s`.
- Pie: retardo `0.2s`.
- Utilizar siempre `animation-fill-mode: both` cuando haya un retardo (`animation-delay`), para que el elemento permanezca oculto en su estado inicial antes de comenzar a animar.

---

## 4. Accesibilidad Obligatoria: Reducción de Movimiento

Siempre que se defina una animación o transición, debe incluirse el bloque para usuarios con sensibilidad al movimiento:

```css
@media (prefers-reduced-motion: reduce) {
  *, ::before, ::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```
*(Manteniendo la posición base del elemento sin perder alineaciones fijas como `translateY(-50%)` para centrados absolutos).*
