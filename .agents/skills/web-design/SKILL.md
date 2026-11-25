---
name: web-design
description: >-
  Directrices de diseño web y UI/UX con enfoque artesanal y anti-clichés de IA.
  Aplica principios de jerarquía visual, tipografía intencional, ritmo, paleta institucional UDO,
  reducción de contenedores innecesarios y consistencia para Blade, Tailwind v3 y CSS de paneles.
---

# Skill: Diseño Web Artesanal & UI/UX (Anti-Clichés de IA)

Esta skill define las normas y principios de diseño visual y de interfaz para evitar que las pantallas y componentes parezcan "aplicaciones genéricas generadas por IA" (*AI slop* o plantillas SaaS repetitivas).

Combina **fundamentos universales de artesanía visual** con las **especificaciones técnicas y de identidad de PostUDO**.

---

## 1. La Lista Negra: Clichés de IA a Eliminar

Toda interfaz desarrollada debe evitar activamente los siguientes patrones trillados:

1. **Gradientes Neón y Texto Recortado:**
   - ❌ *No usar:* Gradientes violeta/índigo/cian (`bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600`) ni títulos con texto transparente (`bg-clip-text text-transparent`).
   - ✅ *Usar:* Tipografía con color sólido de alto contraste, utilizando el peso tipográfico y el tamaño para jerarquizar.
2. **Esferas de Luz y Blobs Difusos:**
   - ❌ *No usar:* Fondos oscuros con manchas circulares de colores desenfocados (`blur-3xl rounded-full bg-purple-500/10`).
   - ✅ *Usar:* Superficies limpias, fondos con neutros templados, sutiles mallas o fondos lisos que no distraigan de la información.
3. **"Cuadros dentro de cuadros" (Card Overkill):**
   - ❌ *No usar:* Envolver cada párrafo, métrica o fila dentro de una tarjeta con `border border-slate-200 rounded-2xl shadow-sm bg-white`.
   - ✅ *Usar:* Estructuración por espacio en blanco, tipografía, divisores lineales tenues (`border-b border-slate-200/60`) o filas de tablas planas.
4. **Héroes de Molde SaaS:**
   - ❌ *No usar:* La fórmula repetitiva: Badge píldora con brillo → H1 centrado con gradiente → Párrafo gris → Dos botones juntos (uno con degradado y otro con borde).
   - ✅ *Usar:* Cabeceras contextuales orientadas a la acción y al dominio del usuario (por ejemplo, títulos funcionales con toolbar de acciones y contadores limpios).
5. **Iconos en Burbujas Pastel:**
   - ❌ *No usar:* Cuadrícula idéntica de 3 columnas donde cada ítem tiene un icono encerrado en un círculo pastel claro (`bg-blue-50 text-blue-600 rounded-full p-4`).
   - ✅ *Usar:* Iconos integrados sutilmente junto al texto o acciones directas, con pesos y escalas acordes al contenido.
6. **Microcopy Robótico o de Relleno:**
   - ❌ *No usar:* "Transforma tu experiencia académica", "Solución integral de próxima generación", "Optimiza tu flujo de trabajo".
   - ✅ *Usar:* Textos directos, precisos y en español natural enfocados en la tarea (ej. "Descarga de constancias", "Gestión de cohortes", "Registro de comprobantes").

---

## 2. Principios de Artesanía Visual (Craft)

### A. Tipografía con Carácter y Fuerte Contraste
- **Escala Audaz:** El contraste debe ser evidente. Un título no debe competir con un subtítulo por apenas 2 píxeles de diferencia.
- **Emparejamiento Intencional:**
  - **Títulos y Display:** `Outfit`, sans-serif geométrica con personalidad para títulos de secciones, números destacados y cabeceras.
  - **Cuerpo y Datos:** `Inter`, neutral, óptima para legibilidad en tablas densas, formularios y microcopy.
- **Línea de Lectura:** Máximo de 65 a 75 caracteres por línea en textos descriptivos (`max-w-prose` o ~680px) para no fatigar la vista.

### B. Densidad y Reducción de Superficies
- **Eliminación de Contenedores:** Antes de agregar un `div` con fondo y borde para agrupar algo, pregúntate si se entiende igual solo separándolo con margen (`mt-6` o `gap-4`).
- **Respiración y Ritmo:** Alternar zonas densas de datos (tablas o listados concisos) con zonas despejadas alrededor de acciones clave.
- **Bordes vs Sombras:** Preferir bordes sutiles y definidos de 1px a sombras grandes difusas. Si se usa sombra, debe ser nítida y cercana a la superficie.

### C. Color con Propósito
- **Neutros con Temperatura:** Evitar el gris puro `#808080` o blanco clínico sobre negro absoluto. Utilizar neutros con matices suaves (azul marino profundo, pizarra, arena o crema).
- **Regla del Acento Único:** Un solo color dominante para llamadas de acción primarias e interacción. El resto de colores se reservan estrictamente para semántica de estado (éxito, alerta, error).

---

## 3. Directrices Específicas del Ecosistema PostUDO

Al crear o ajustar vistas en este proyecto, se debe respetar su arquitectura frontend:

### A. Identidad y Paleta UDO
- **Azul Institucional:**
  - Principal: `--udo-azul` (`#003da5`)
  - Marino/Fondo profundo: `--udo-azul-marino` (`#001233`)
  - Claro/Hover: `--udo-azul-claro` (`#0056b3`)
- **Naranja de Acento:**
  - Primario de interacción / botones clave: `--udo-naranja` (`#f7941d`)
  - Degradados/Bordes: `--udo-naranja-oscuro` (`#d97306`)
  - Fondos suaves: `--udo-naranja-suave` (`#ffd9b8`)
  - **Accesibilidad:** Sobre fondos blancos o claros, utilizar siempre naranja con contraste suficiente (`#c26a00`).
- **Colores Semánticos (Conservar intactos):**
  - Éxito / Verificado: Verde (`#28a745` / `.badge-success` / pestañas de finanzas verdes).
  - Error / Peligro: Rojo (`#dc3545` / `.alerta-error` / `.btn-icono-glass.peligro`).
  - Avisos / Atención: Ámbar (`#ffc107`).

### B. Los Dos Pipelines de Estilos (¡Crítico!)
1. **Paneles Administrativos y de Gestión (`<x-layout>`):**
   - **No dependen de Tailwind.** Utilizan el CSS estático [public/css/style.css](file:///c:/laragon/www/postudo/public/css/style.css).
   - Patrón obligatorio: **Diseño plano sin tarjetas** (*estilo Asuntos/Áreas*):
     - Contenedor raíz: `<div class="page-content">` (evitar volver a la vieja `.main-content-section` roja).
     - Encabezado: Título con `.content_texto_bienvenida`.
     - Barra de herramientas: Botones `.button_body` y contadores en texto plano o `.contador-vista`.
     - Tablas: Usar directamente la etiqueta `<table>` con clase `.table` global, sin envolver en `.tabla-glass`.
     - Acciones de fila: Iconos discretos `.btn-table-action`.
     - Filas desplegables de detalle: Usar la clase utilitaria `.hidden` (no el atributo HTML `hidden`).
     - Botones de envío: `.submit-button` (degradado naranja institucional).
2. **Vistas de Autenticación y Perfil (`layouts/guest`, `layouts/app`):**
   - Utilizan el bundle compilar de Vite + **Tailwind v3** (`@vite`).
   - Utilizar las clases configuradas en `tailwind.config.js`: `brand`, `brand-soft`, `primary-button`, etc.

---

## 4. Microdetalles y Estados de Componentes

1. **Feedback Táctil:**
   - Botones e interactivos deben responder al clic: `active:scale-[0.98]` o clase con micro-transición suave (`transition-all duration-150`).
2. **Estados Vacíos (Empty States):**
   - Nunca dejar una tabla vacía sin feedback o con un dibujo genérico descontextualizado.
   - Usar un mensaje simple y accionable:
     * "No se encontraron postgrados registrados en esta área."
     * Si el usuario tiene permisos: incluir un enlace o botón para "Registrar el primer postgrado".
3. **Formularios:**
   - Etiquetas siempre visibles arriba o al lado de cada campo (nunca depender únicamente de los `placeholder`).
   - Indicador claro de campos obligatorios (*).
   - Errores de validación específicos justo debajo del campo afectado (`@error('campo')`).
4. **Confirmaciones Destructivas:**
   - Todo botón de eliminación o cambio irreversible debe avisar el impacto en cascada real (estudiantes afectados, registros derivados), siguiendo el patrón de diálogos del proyecto.

---

## 5. Guías de Referencia Detalladas

Para especificaciones numéricas exactas y recetas de implementación avanzada, consulta:
- [Guía de Micro-UI y Pulido Visual](./references/micro_ui.md) (inspirada en `better-ui`): Radios de bordes anidados, sombras en 2 capas, alineación óptica de iconos y anillos de foco accesibles.
- [Guía de Animación y Motion Design](./references/motion.md) (inspirada en `better-motion`): Duraciones por tipo de interacción, curvas easing, control de `containing block` y prevención de parpadeos de scroll.

---

## 6. Checklist de Revisión de Diseño

Antes de dar por finalizada una vista o ajuste de interfaz, verificar:
- [ ] ¿Se siente como una herramienta sólida y real para la universidad o parece una plantilla SaaS generada por IA?
- [ ] ¿Hay tarjetas o contenedores innecesarios que se puedan simplificar?
- [ ] ¿La jerarquía tipográfica guía la mirada claramente hacia lo más importante?
- [ ] ¿Los colores respetan la paleta UDO y mantienen legibilidad accesible?
- [ ] ¿Se utilizó el pipeline de estilos correcto (CSS de paneles vs. Tailwind en Auth)?
- [ ] ¿Los estados vacíos, hover, foco y errores están contemplados?
