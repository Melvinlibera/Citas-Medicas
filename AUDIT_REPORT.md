# Informe de Auditoría y Plan de Mejoras para el Proyecto Citas Médicas

Este documento detalla la auditoría del proyecto existente y propone un plan de mejoras para cumplir con los requisitos técnicos, estructurales y de diseño especificados por el usuario.

## 1. Análisis del Proyecto Existente

El proyecto `Citas-Medicas` ha sido clonado desde el repositorio de GitHub `https://github.com/Melvinlibera/Citas-Medicas.git`. A continuación, se presenta un resumen de la estructura de archivos y las tecnologías identificadas:

### Estructura de Archivos Clave:

- `/admin`: Contiene la lógica y las vistas para el panel de administración (agendar, citas, doctores, especialidades, usuarios).
- `/admin/ajax`: Archivos PHP para operaciones AJAX (agregar, editar, eliminar citas, doctores, especialidades, usuarios; cambiar estado de cita, rol de usuario; obtener doctores).
- `/assets`: Recursos estáticos como CSS, JavaScript e imágenes.
  - `/assets/css/style.css`: Hoja de estilos principal.
  - `/assets/img/logo.png`: Logo del proyecto.
  - `/assets/js/main.js`, `/assets/js/script.js`, `/assets/js/validaciones.js`: Archivos JavaScript.
- `/auth`: Archivos para autenticación (login, logout, register).
- `/config/db.php`: Archivo de configuración para la conexión a la base de datos.
- `/especialidades`: Vistas relacionadas con las especialidades (index, ver).
- `/includes`: Archivos PHP para incluir en otras páginas (footer, header).
- `/user`: Contiene la lógica y las vistas para el panel de usuario (agendar, mis_citas, dashboard).
- `index.php`: Página principal del sitio.
- `import.sql` / `phpmyadmin archivo/citas_medicas.sql`: Scripts SQL para la base de datos.

### Tecnologías Identificadas:

- **Backend:** PHP (con uso de sesiones).
- **Base de Datos:** MySQL (configuración en `config/db.php`, esquema en `citas_medicas.sql`).
- **Frontend:** HTML5, CSS (con un `style.css` personalizado y estilos inline en `index.php`), JavaScript (archivos `main.js`, `script.js`, `validaciones.js`).
- **Conexión a DB:** PDO (según `config/db.php`).

## 2. Evaluación de Cumplimiento de Pautas Académicas

A continuación, se evalúa el cumplimiento del proyecto actual con las pautas académicas proporcionadas:

| Criterio Técnico y Estructural | Estado Actual | Observaciones / Plan de Mejora |
| :----------------------------- | :------------ | :----------------------------- |
| **Lenguaje y Tecnologías**     |               |                                |
| PHP (backend)                  | ✅ Cumple     | Utilizado extensivamente.      |
| MySQL (base de datos)          | ✅ Cumple     | Base de datos definida y usada. |
| HTML5 y CSS3 (frontend)        | ✅ Cumple     | Estilos y estructura básica presentes. |
| JavaScript (validaciones)      | ✅ Cumple     | Archivo `validaciones.js` existe, se debe verificar su implementación completa. |
| **Base de Datos**              |               |                                |
| Almacenamiento en MySQL        | ✅ Cumple     | `citas_medicas.sql` define la estructura. |
| **Conexión a Base de Datos**   |               |                                |
| Uso de PDO o mysqli            | ✅ Cumple     | Se utiliza PDO en `config/db.php`. |
| **Manejo de Sesiones**         |               |                                |
| Inicio y cierre de sesión      | ✅ Cumple     | Implementado en `/auth/login.php` y `/auth/logout.php`. |
| Diferenciación de roles (admin, cliente/usuario) | ✅ Cumple | Roles `admin` y `user` (cliente) identificados y usados para acceso a dashboards. |
| **Operaciones CRUD Completas** |               |                                |
| Crear, leer, actualizar, eliminar datos desde interfaz web | ⚠️ Parcial | Se observan operaciones CRUD en `/admin/ajax` y en las vistas de administración. Se debe verificar la completitud y seguridad de todas las operaciones para ambos roles. |
| **Roles y Permisos**           |               |                                |
| Funcionalidades exclusivas para administrador | ✅ Cumple | El panel de administración (`/admin`) es exclusivo para el rol `admin`. |
| **Validaciones**               |               |                                |
| Lado del cliente (JavaScript)  | ⚠️ Parcial | Existe `validaciones.js`, pero se debe asegurar que todas las entradas críticas tengan validación robusta en el frontend. |
| Lado del servidor (PHP)        | ⚠️ Parcial | Se observan validaciones básicas (ej. `empty()` en `import.sql` que parece ser `user/agendar_ajax.php`). Se debe reforzar la validación de todos los datos de entrada en el backend para prevenir inyecciones SQL y otros ataques. |
| Campos obligatorios, formato correcto (emails, fechas) | ⚠️ Parcial | Necesita revisión y mejora para asegurar que todos los campos cumplan con los formatos y requisitos. |
| **Diseño Web Básico**          |               |                                |
| HTML5 + CSS3                   | ✅ Cumple     | Estilos básicos presentes.     |
| Uso de Bootstrap               | ❌ No Cumple  | No se detecta el uso de Bootstrap. Se puede considerar su integración para facilitar el diseño responsivo y componentes UI. |
| **Entorno de Desarrollo**      |               |                                |
| Uso de XAMPP o similar         | ✅ Cumple     | Implícito por el uso de PHP y MySQL local. |
| **Seguridad de Contraseñas**   |               |                                |
| Contraseñas cifradas con `password_hash()` y `password_verify()` | ✅ Cumple | La tabla `usuarios` en `citas_medicas.sql` tiene un campo `password` de `VARCHAR(255)`, lo que sugiere el uso de `password_hash()`. Se debe verificar la implementación en `auth/register.php` y `auth/login.php`. |
| **Mensajes de Error y Confirmación** | ⚠️ Parcial | Se definen clases `.success` y `.error` en `style.css`. Se debe asegurar su implementación consistente en toda la interfaz para retroalimentación al usuario. |
| **Documentación del Código**   |               |                                |
| Comentarios explicativos       | ❌ No Cumple  | Los archivos revisados tienen comentarios mínimos. Se requiere añadir comentarios detallados para funciones, clases y flujo general. |

## 3. Evaluación de Requisitos de Diseño y Funcionalidad del Usuario

El usuario ha especificado requisitos de diseño y funcionalidad adicionales. A continuación, se evalúa el estado actual y se proponen mejoras:

| Requisito de Diseño/Funcionalidad | Estado Actual | Observaciones / Plan de Mejora |
| :------------------------------- | :------------ | :----------------------------- |
| **Diseño General**               |               |                                |
| Logo en el medio, con efecto de desplazamiento (se centra a la izquierda al bajar) | ⚠️ Parcial | El logo está centrado inicialmente y se reduce al hacer scroll, pero no se desplaza a la izquierda. Se requiere modificar `main.js` y `style.css` para este efecto. |
| Barra azul marino con información (Quiénes somos, Procedimientos, etc.) | ⚠️ Parcial | La barra de navegación actual es simple. Se necesita expandir el `header` para incluir más enlaces y un diseño más elaborado. |
| Fondo y background color: `#FBFBFB` | ✅ Cumple     | Definido como `--background` en `style.css`. |
| Colorimetría: azul marino, azul claro y negro | ✅ Cumple     | Colores primarios y secundarios definidos en `style.css` (`--primary`, `--secondary`). |
| Página animada (como Flutter), profesional y minimalista, ambiente de empresa de salud | ⚠️ Parcial | El diseño actual es funcional pero carece de animaciones y el nivel de profesionalismo y minimalismo deseado. Se requiere refactorizar CSS y añadir más animaciones JS. |
| **Página de Bienvenida / Inicio** |               |                                |
| Pantalla de bienvenida con logo grande | ✅ Cumple     | `index.php` tiene un logo grande y una sección `hero`. |
| Al arrastrar pantalla: Quiénes somos, información de sucursales/hospital, especialistas con contactos, agendar citas, ver citas (con login/register) | ⚠️ Parcial | Las secciones 
actuales (`.info`, `.section`) cubren parcialmente esto. Se necesita integrar la información de sucursales/hospital y especialistas con contactos. La sección de agendar/ver citas ya enlaza a login/register. |
| **Sección de Especialidades**    |               |                                |
| Carga la página con el médico especialista en grande, nombre, información de la especialidad, días disponibles, agendar/ver cita, registro de paciente (cédula, teléfono, correo, seguro) | ❌ No Cumple  | La página `especialidades/ver.php` actualmente solo muestra la especialidad. Se necesita una refactorización significativa para mostrar médicos asociados, sus detalles, disponibilidad, y la funcionalidad de agendar cita con registro/login integrado. La lógica de precios con/sin seguro para RD también debe ser implementada. |

## 4. Plan de Mejoras Detallado

Para abordar las deficiencias identificadas y cumplir con todos los requisitos, se propone el siguiente plan de mejoras:

### 4.1. Mejoras en la Estructura y Seguridad del Código (Pautas Académicas)

1.  **Reforzar Validaciones (PHP y JavaScript):**
    *   Implementar validaciones robustas en el lado del servidor para todos los formularios (registro, login, agendar cita, etc.) utilizando filtros de PHP (`filter_var`, `filter_input`) y expresiones regulares para asegurar la integridad y seguridad de los datos. Esto incluye validación de formato para correos, fechas, números de teléfono y cédulas.
    *   Mejorar las validaciones del lado del cliente con JavaScript para proporcionar retroalimentación instantánea al usuario y reducir la carga del servidor. Asegurar que `assets/js/validaciones.js` se utilice de manera efectiva en todos los formularios.
2.  **Completar Operaciones CRUD:**
    *   Revisar y asegurar que todas las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) estén completamente implementadas y sean seguras para los roles de administrador y usuario. Prestar especial atención a la eliminación de registros por parte del administrador.
3.  **Manejo de Mensajes de Retroalimentación:**
    *   Implementar un sistema consistente para mostrar mensajes de éxito (color verde) y error (color rojo) en toda la interfaz, utilizando las clases CSS ya definidas (`.success`, `.error`). Esto puede hacerse mediante variables de sesión para mensajes temporales después de redirecciones.
4.  **Documentación del Código:**
    *   Añadir comentarios explicativos detallados a todas las funciones, clases, bloques lógicos y archivos importantes, siguiendo un estándar de documentación (ej. PHPDoc para PHP). Esto mejorará la mantenibilidad y comprensión del código.

### 4.2. Mejoras en el Diseño y la Experiencia de Usuario (Requisitos del Usuario)

1.  **Header y Navegación:**
    *   **Efecto de Desplazamiento del Logo:** Modificar `assets/js/main.js` y `assets/css/style.css` para que el logo se desplace al centro-izquierda y se reduzca al hacer scroll, como se describe. El `header` también debe adoptar el color azul marino (`--primary`) al hacer scroll.
    *   **Barra de Navegación Ampliada:** Reestructurar el `header` para incluir enlaces a 
secciones como "Quiénes somos", "Nuestras Sucursales", "Especialistas", "Agendar Cita" y "Ver Citas".
    *   **Menú Desplegable para Especialidades:** Implementar un menú desplegable bajo "Procedimientos" o "Especialidades" que liste las ramas médicas (Psicología, Ginecología, Médico General, etc.). Esto requerirá modificaciones en `includes/header.php` (si se crea uno global) o directamente en `index.php` y `assets/css/style.css`.
2.  **Página de Inicio (Landing Page):**
    *   **Secciones Completas:** Asegurar que la página de inicio (`index.php`) presente de manera clara y animada las secciones de "Quiénes somos", "Información de nuestras sucursales y hospital", "Especialistas con sus listas de contactos", y "Agendar citas y ver citas" (con enlaces a login/register).
    *   **Animaciones y Estilo:** Refactorizar el CSS y añadir JavaScript para lograr un diseño "animado como si estuviera en Flutter", "super profesional y minimalista", que transmita un "ambiente de una empresa de salud". Esto implica el uso de transiciones suaves, micro-interacciones y un diseño limpio.
3.  **Página de Detalle de Especialidad (`especialidades/ver.php`):**
    *   **Información Completa del Especialista:** Modificar esta página para que, al seleccionar una especialidad, muestre una lista de médicos asociados a esa especialidad. Para cada médico, debe mostrar su nombre, información detallada, días disponibles y opciones para "Agendar Cita" o "Ver Cita".
    *   **Registro/Login Integrado:** Al intentar agendar una cita, si el usuario no está logueado, se le debe pedir que se registre o inicie sesión. El formulario de registro debe incluir campos para cédula, número telefónico/celular, correo electrónico y si posee seguro.
    *   **Cálculo de Precios con Seguro:** Implementar la lógica para calcular el precio de la cita médica en función de la especialidad y si el paciente posee seguro (75% menos sin seguro). Esto requerirá ajustes en la base de datos (tabla `especialidades` ya tiene `precio`) y en la lógica PHP para el agendamiento.
    *   **Diseño Atractivo:** Asegurar que esta página tenga un diseño "hermoso, bello y super profesional", manteniendo la colorimetría definida.

### 4.3. Mejoras en la Base de Datos y Scripts SQL

1.  **Script SQL Consolidado:** Consolidar `import.sql` y `citas_medicas.sql` en un único archivo `database.sql` que contenga la estructura completa de la base de datos y los datos de prueba iniciales.
2.  **Diagrama Entidad-Relación (ER):** Generar un diagrama ER actualizado que refleje la estructura final de la base de datos, incluyendo las relaciones entre `citas`, `doctores`, `especialidades` y `usuarios`.

## 5. Archivos a Modificar, Eliminar y Crear

### Archivos a Modificar:

*   `/index.php`: Para la estructura de la página de bienvenida, integración de nuevas secciones y el `header` mejorado.
*   `/assets/css/style.css`: Para refactorizar estilos, implementar animaciones, ajustar la colorimetría y el efecto de desplazamiento del logo.
*   `/assets/js/main.js`: Para la lógica del efecto de desplazamiento del logo y otras animaciones interactivas.
*   `/assets/js/validaciones.js`: Para mejorar y extender las validaciones del lado del cliente.
*   `/config/db.php`: Posibles ajustes menores si se requiere alguna configuración adicional de la base de datos.
*   `/auth/register.php`: Para incluir los campos de cédula, teléfono y seguro en el formulario de registro.
*   `/auth/login.php`: Para asegurar la correcta autenticación con los nuevos campos de registro.
*   `/admin/dashboard.php`, `/admin/citas.php`, `/admin/doctores.php`, `/admin/especialidades.php`, `/admin/usuarios.php`: Para asegurar la consistencia del diseño, la implementación de mensajes de retroalimentación y la completitud de las operaciones CRUD.
*   `/admin/ajax/*.php`: Revisar y mejorar la seguridad y validación en todas las operaciones AJAX.
*   `/user/dashboard.php`, `/user/agendar.php`, `/user/mis_citas.php`: Para reflejar las mejoras de diseño, la integración de la lógica de agendamiento con seguro y la visualización de citas.
*   `/especialidades/ver.php`: **Modificación mayor** para mostrar detalles de médicos, disponibilidad y la funcionalidad de agendamiento con cálculo de precios.

### Archivos a Eliminar:

*   `/import.sql`: Se consolidará con `citas_medicas.sql`.
*   `/phpmyadmin archivo/citas_medicas.sql`: Se consolidará en un nuevo archivo `database.sql`.

### Archivos a Crear:

*   `/database.sql`: Archivo SQL consolidado con la estructura de la base de datos y datos de prueba.
*   `ER_Diagram.png` o `ER_Diagram.pdf`: Diagrama Entidad-Relación de la base de datos.
*   `Documentacion_Proyecto.docx` o `Documentacion_Proyecto.pdf`: Documento explicativo del sistema, requisitos técnicos, capturas de pantalla e instrucciones de instalación.
*   Posiblemente nuevos archivos PHP para la lógica de cálculo de precios o manejo de médicos por especialidad, si la complejidad lo amerita y para mantener el código modular.

## 6. Próximos Pasos

El siguiente paso será proceder con las modificaciones y desarrollos propuestos, comenzando por las mejoras en el `header` y la página de inicio, seguido por la refactorización de la página de especialidades y la implementación de la lógica de precios y registro de pacientes. Se prestará especial atención a la seguridad y la documentación del código en cada etapa del proceso.
