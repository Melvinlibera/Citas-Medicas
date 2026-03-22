# 📊 Documentación Técnica - Hospital & Human

## 📋 Información General del Proyecto

**Nombre del Proyecto:** Sistema de Citas Médicas - Hospital & Human  
**Versión:** 1.0.0  
**Fecha de Desarrollo:** Marzo 2026  
**Tipo de Proyecto:** Aplicación Web para Gestión de Citas Médicas  
**Cumplimiento:** SOF-109 (Estándares de Desarrollo de Software)

## 🏗️ Arquitectura del Sistema

### Tecnologías Utilizadas

- **Backend:** PHP 8.0+ con PDO para base de datos
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Base de Datos:** MySQL 8.0+ con motor InnoDB
- **Servidor Web:** Apache/XAMPP
- **Framework CSS:** Bootstrap (componentes personalizados)
- **Librerías JavaScript:** Axios (AJAX), Boxicons (iconos)

### Patrón de Arquitectura

- **MVC Simplificado:** Separación de lógica de negocio, presentación y datos
- **Arquitectura en Capas:** Presentación → Lógica → Datos
- **Programación Orientada a Objetos:** Uso de clases y métodos estáticos

## 📁 Estructura del Proyecto

```
citas_medicas/
├── 📂 admin/                    # Panel de Administración
│   ├── dashboard.php           # Dashboard principal del admin
│   ├── usuarios.php            # Gestión de usuarios
│   ├── doctores.php            # Gestión de doctores
│   ├── especialidades.php      # Gestión de especialidades
│   ├── citas.php               # Gestión de citas
│   ├── sidebar.php             # Barra lateral de navegación
│   └── 📂 ajax/                # Endpoints AJAX
│       ├── agregar_usuario.php
│       ├── editar_usuario.php
│       ├── eliminar_usuario.php
│       ├── agregar_doctor.php
│       ├── editar_doctor.php
│       ├── eliminar_doctor.php
│       ├── agregar_especialidad.php
│       ├── editar_especialidad.php
│       ├── eliminar_especialidad.php
│       ├── cambiar_estado_cita.php
│       └── get_doctores.php
├── 📂 assets/                   # Recursos estáticos
│   ├── 📂 css/
│   │   └── style.css           # Estilos principales
│   ├── 📂 img/                 # Imágenes del sistema
│   └── 📂 js/
│       ├── main.js             # Funciones principales
│       ├── script.js           # Scripts específicos
│       └── validaciones.js     # Validaciones del lado cliente
├── 📂 auth/                     # Sistema de Autenticación
│   ├── login.php               # Página de inicio de sesión
│   ├── logout.php              # Cierre de sesión
│   └── register.php            # Registro de nuevos usuarios
├── 📂 config/                   # Configuración del Sistema
│   └── db.php                  # Conexión a base de datos
├── 📂 doctor/                   # Panel del Doctor
│   ├── dashboard.php           # Dashboard del doctor
│   ├── mis_citas.php           # Citas del doctor
│   └── perfil.php              # Perfil del doctor
├── 📂 especialidades/           # Páginas Públicas
│   ├── index.php               # Lista de especialidades
│   └── ver.php                 # Detalle de especialidad
├── 📂 includes/                 # Componentes Reutilizables
│   ├── header.php              # Cabecera del sitio
│   ├── header_dynamic.php      # Cabecera dinámica
│   └── footer.php              # Pie de página
├── 📂 user/                     # Panel del Usuario/Paciente
│   ├── dashboard.php           # Dashboard del usuario
│   ├── agendar.php             # Agendar cita
│   ├── mis_citas.php           # Mis citas
│   ├── index.php               # Página principal del usuario
│   ├── agendar_ajax.php        # AJAX para agendar citas
│   └── get_doctores.php        # Obtener doctores por especialidad
├── 📄 index.php                 # Página de inicio
├── 📄 database.sql             # Script de base de datos
└── 📄 [Archivos de documentación]
```

## 🗄️ Base de Datos

### Estructura de Tablas

#### 1. Tabla `usuarios`
```sql
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `cedula` varchar(20) UNIQUE DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `seguro` varchar(50) DEFAULT NULL,
  `rol` varchar(20) DEFAULT 'user',
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo_unique` (`correo`),
  UNIQUE KEY `cedula_unique` (`cedula`)
);
```

#### 2. Tabla `especialidades`
```sql
CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
);
```

#### 3. Tabla `doctores`
```sql
CREATE TABLE `doctores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
);
```

#### 4. Tabla `citas`
```sql
CREATE TABLE `citas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `id_doctor` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') DEFAULT 'pendiente',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`),
  FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id`)
);
```

## 👥 Usuarios del Sistema

### Credenciales de Acceso

#### 👨‍💼 Administrador
- **Usuario:** admin@hospitalandhuman.com
- **Contraseña:** 123456
- **Rol:** admin
- **Nombre:** Admin Principal
- **Cédula:** 0000000000
- **Teléfono:** 0000000000

#### 👨‍⚕️ Doctores

| Nombre | Correo | Contraseña | Especialidad | Cédula | Teléfono |
|--------|--------|------------|--------------|--------|----------|
| Dr. Luis Fernández | dr.luis@hospitalandhuman.com | 123456 | Psicología | 001-1234567-1 | 809-555-1234 |
| Dra. Carmen Rodríguez | dra.carmen@hospitalandhuman.com | 123456 | Medicina General | 402-7654321-8 | 829-234-5678 |
| Dr. José Martínez | dr.jose@hospitalandhuman.com | 123456 | Cardiología | 031-9876543-2 | 849-345-6789 |
| Dra. Laura Gómez | dra.laura@hospitalandhuman.com | 123456 | Ginecología y Obstetricia | 223-4567890-5 | 809-456-7890 |
| Dr. Ricardo Sánchez | dr.ricardo@hospitalandhuman.com | 123456 | Urología | 054-1122334-6 | 829-567-8901 |
| Dra. Patricia Díaz | dra.patricia@hospitalandhuman.com | 123456 | Oncología | 402-9988776-3 | 849-678-9012 |
| Dr. Manuel Herrera | dr.manuel@hospitalandhuman.com | 123456 | Nefrología | 001-3344556-7 | 809-789-0123 |
| Dra. Andrea Castillo | dra.andrea@hospitalandhuman.com | 123456 | Endocrinología | 031-2233445-9 | 829-890-1234 |
| Dr. Javier Morales | dr.javier@hospitalandhuman.com | 123456 | Traumatología y Ortopedia | 402-5566778-4 | 849-901-2345 |
| Dra. Daniela Ruiz | dra.daniela@hospitalandhuman.com | 123456 | Pediatría | 054-7788990-2 | 809-112-2334 |
| Dr. Fernando Navarro | dr.fernando@hospitalandhuman.com | 123456 | Neonatología | 223-8899001-6 | 829-223-3445 |
| Dra. Sofía Méndez | dra.sofia@hospitalandhuman.com | 123456 | Medicina Intensiva (UCI) | 001-6677889-3 | 849-334-4556 |
| Dr. Alberto Cruz | dr.alberto@hospitalandhuman.com | 123456 | Radiología | 031-4455667-8 | 809-445-5667 |
| Dra. Valeria Peña | dra.valeria@hospitalandhuman.com | 123456 | Dermatología | 402-1231231-5 | 829-556-6778 |
| Dr. Miguel Ortega | dr.miguel@hospitalandhuman.com | 123456 | Oftalmología | 054-3213213-7 | 849-667-7889 |

**Nota:** Todas las contraseñas están hasheadas con `password_hash(PASSWORD_DEFAULT)` en la base de datos.

### Pacientes (Usuarios Registrados)
Los pacientes se registran a través del formulario público (`auth/register.php`) y pueden elegir su propia contraseña (mínimo 6 caracteres).

## 🔐 Sistema de Seguridad

### Autenticación
- **Hash de Contraseñas:** `password_hash()` con algoritmo PASSWORD_DEFAULT
- **Verificación:** `password_verify()` para login
- **Sesiones Seguras:** Uso de `$_SESSION` con validación de rol
- **Protección CSRF:** Tokens de sesión para formularios

### Validaciones
- **Servidor:** Validaciones PHP en todos los endpoints
- **Cliente:** Validaciones JavaScript para UX mejorada
- **Formato Cédula:** Automático XXX-XXXXXXX-X
- **Formato Teléfono:** Automático XXX-XXX-XXXX
- **Longitud Contraseña:** Mínimo 6 caracteres

### Sanitización
- **Entrada:** `trim()`, `filter_var()`, expresiones regulares
- **Salida:** `htmlspecialchars()` para prevenir XSS
- **SQL:** Prepared statements con PDO

## 🎯 Funcionalidades del Sistema

### 👨‍💼 Panel de Administración
- **Gestión de Usuarios:** Crear, editar, eliminar usuarios
- **Gestión de Doctores:** Crear, editar, eliminar doctores con especialidad
- **Gestión de Especialidades:** CRUD completo de especialidades médicas
- **Gestión de Citas:** Ver, confirmar, cancelar citas
- **Dashboard:** Estadísticas y métricas del sistema

### 👨‍⚕️ Panel del Doctor
- **Dashboard Personalizado:** Estadísticas de citas
- **Mis Citas:** Ver citas asignadas (pendientes, confirmadas, canceladas)
- **Perfil:** Información personal y especialidad

### 👤 Panel del Paciente
- **Dashboard:** Acceso rápido a funcionalidades
- **Agendar Cita:** Selección de especialidad, doctor, fecha y hora
- **Mis Citas:** Ver historial de citas
- **Perfil:** Información personal

### 🌐 Funcionalidades Públicas
- **Registro de Usuarios:** Formulario público de registro
- **Inicio de Sesión:** Autenticación unificada
- **Ver Especialidades:** Lista y detalle de especialidades disponibles

## 🔄 Flujo de Trabajo

### Agendar una Cita
1. **Usuario se registra** → `auth/register.php`
2. **Usuario inicia sesión** → `auth/login.php`
3. **Usuario selecciona especialidad** → `user/agendar.php`
4. **Sistema muestra doctores disponibles** → AJAX `user/get_doctores.php`
5. **Usuario selecciona fecha/hora** → Validación de disponibilidad
6. **Cita se guarda** → `user/agendar_ajax.php`
7. **Confirmación por email** → (futuro)

### Crear Doctor (Admin)
1. **Admin accede al panel** → `admin/doctores.php`
2. **Admin crea usuario doctor** → `admin/ajax/agregar_usuario.php`
3. **Admin crea registro doctor** → `admin/ajax/agregar_doctor.php`
4. **Sistema enlaza automáticamente** → Doctor ↔ Especialidad

## 📊 Modelo Entidad-Relación (ER)

### Diagrama ER (Texto)

```
┌─────────────────────────┐
│      USUARIOS           │
├─────────────────────────┤
│ id (PK)                 │
│ nombre                  │
│ cedula (UQ)             │
│ telefono                │
│ correo (UQ)             │
│ password                │
│ seguro                  │
│ rol (admin/doctor/user) │
│ fecha_registro          │
└─────────────────────────┘
         │ │
         │ └──────────────────────┐
         │                        │
         ▼                        ▼
┌─────────────────────────┐  ┌──────────────────┐
│     CITAS               │  │   DOCTORES       │
├─────────────────────────┤  ├──────────────────┤
│ id (PK)                 │  │ id (PK)          │
│ id_usuario (FK)────────┐│  │ nombre           │
│ id_especialidad (FK)──┐││  │ id_especialidad  │
│ id_doctor (FK)───────┐│││  │ id_usuario (FK)─┬┘
│ fecha               │││  │
│ hora                │││  │
│ estado              │││  │
│ fecha_creacion      │││  │
└─────────────────────────┘│  └──────────────────┘
           │              │
           │              │
           └──────┬───────┘
                  │
         ┌────────▼──────────┐
         │  ESPECIALIDADES   │
         ├───────────────────┤
         │ id (PK)           │
│ nombre            │
         │ descripcion       │
         │ precio            │
         └───────────────────┘
```

### Relaciones
- **1:N** Usuario → Citas (Un usuario puede tener múltiples citas)
- **1:N** Doctor → Citas (Un doctor puede tener múltiples citas)
- **1:N** Especialidad → Citas (Una especialidad puede tener múltiples citas)
- **1:1** Usuario → Doctor (Un usuario puede ser un doctor)
- **N:1** Doctor → Especialidad (Múltiples doctores pueden tener la misma especialidad)

## 🚀 Instalación y Configuración

### Prerrequisitos
- **XAMPP/WAMP** con PHP 8.0+
- **MySQL 8.0+**
- **Navegador web moderno**

### Pasos de Instalación
1. **Clonar/Descargar** el proyecto en `htdocs/`
2. **Importar base de datos:**
   ```bash
   mysql -u root -p < database.sql
   ```
3. **Configurar conexión:**
   - Editar `config/db.php` con credenciales de BD
4. **Iniciar servicios:**
   - Apache y MySQL en XAMPP
5. **Acceder:** `http://localhost/citas_medicas/`

### Configuración de Base de Datos
```php
// config/db.php
<?php
$host = 'localhost';
$dbname = 'citas_medicas';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
```

## 🔧 Mantenimiento y Troubleshooting

### Logs de Error
- **PHP Errors:** Revisar `php_error.log` en XAMPP
- **MySQL Errors:** Revisar logs de MySQL
- **Aplicación:** Implementar logging personalizado

### Optimización
- **Índices:** Agregados en campos de búsqueda frecuente
- **Prepared Statements:** Prevención de SQL Injection
- **Minificación:** CSS/JS minificados para producción

### Backup
```sql
-- Backup completo
mysqldump -u root -p citas_medicas > backup.sql

-- Restaurar
mysql -u root -p citas_medicas < backup.sql
```

## 📈 Métricas y Estadísticas

### Dashboard Admin
- Total de usuarios registrados
- Total de doctores activos
- Total de citas agendadas
- Citas por estado (pendiente/confirmada/cancelada)
- Ingresos estimados por especialidad

### Dashboard Doctor
- Citas pendientes para hoy
- Total de pacientes atendidos
- Próximas citas programadas

### Dashboard Usuario
- Próximas citas
- Historial de citas
- Estado de citas activas

## 🎨 Interfaz de Usuario

### Diseño Responsivo
- **Mobile-first:** Optimizado para dispositivos móviles
- **Breakpoints:** 768px, 1024px, 1200px
- **Framework:** CSS Grid y Flexbox

### Paleta de Colores
- **Primary:** #0a1f44 (Azul oscuro)
- **Secondary:** #1e90ff (Azul claro)
- **Accent:** #28a745 (Verde éxito)
- **Warning:** #ffc107 (Amarillo)
- **Danger:** #dc3545 (Rojo)

### Componentes
- **Modales:** Para formularios CRUD
- **Tablas:** Con paginación y búsqueda
- **Cards:** Para dashboards y estadísticas
- **Alerts:** Para mensajes de éxito/error

## 🔗 APIs y Endpoints

### Endpoints AJAX
- `admin/ajax/agregar_usuario.php` - Crear usuario
- `admin/ajax/editar_usuario.php` - Editar usuario
- `admin/ajax/eliminar_usuario.php` - Eliminar usuario
- `admin/ajax/agregar_doctor.php` - Crear doctor
- `admin/ajax/editar_doctor.php` - Editar doctor
- `admin/ajax/eliminar_doctor.php` - Eliminar doctor
- `admin/ajax/agregar_especialidad.php` - Crear especialidad
- `admin/ajax/editar_especialidad.php` - Editar especialidad
- `admin/ajax/eliminar_especialidad.php` - Eliminar especialidad
- `admin/ajax/cambiar_estado_cita.php` - Cambiar estado de cita
- `user/agendar_ajax.php` - Agendar cita
- `user/get_doctores.php` - Obtener doctores por especialidad

### Respuestas JSON
```json
{
  "success": true|false,
  "message": "Mensaje descriptivo",
  "data": {} // Datos adicionales si aplica
}
```

## 📝 Notas de Desarrollo

### Convenciones de Código
- **PHP:** PSR-12 (espaciado, naming)
- **JavaScript:** camelCase, funciones arrow
- **CSS:** BEM methodology
- **SQL:** Mayúsculas para keywords, minúsculas para nombres

### Versionado
- **Git:** Control de versiones con commits descriptivos
- **Branches:** main, develop, features/*
- **Tags:** v1.0.0, v1.1.0, etc.

### Testing
- **Manual:** Casos de uso principales
- **Validaciones:** Formularios y endpoints
- **Navegación:** Flujos completos de usuario

---

**Desarrollado por:** [Tu Nombre]  
**Fecha:** Marzo 2026  
**Versión:** 1.0.0  
**Licencia:** MIT

---

## Descripción de Tablas

### 1. TABLA: `usuarios`

**Propósito:** Almacenar información de todos los usuarios del sistema (pacientes, doctores, administradores)

**Campos:**

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(100) | NOT NULL | Nombre completo del usuario |
| cedula | VARCHAR(20) | UNIQUE, NULLABLE | Cédula de identidad (RD) |
| telefono | VARCHAR(20) | NULLABLE | Número de contacto |
| correo | VARCHAR(100) | UNIQUE, NOT NULL | Email para acceso |
| password | VARCHAR(255) | NOT NULL | Hash bcrypt de contraseña |
| seguro | VARCHAR(50) | NULLABLE | Nombre ARS o 'privado' |
| rol | VARCHAR(20) | DEFAULT 'user' | admin \| doctor \| user |
| fecha_registro | TIMESTAMP | DEFAULT NOW | Fecha de registro |

**Índices:**
- PK: id
- UQ: correo, cedula
- INDEX: rol

**Justificación de Diseño:**
- UNIQUE en cedula/correo para evitar duplicados
- VARCHAR para contraseña (hash puede ser largo)
- Rol almacenado como STRING para flexibilidad futura

---

### 2. TABLA: `especialidades`

**Propósito:** Catálogo de especialidades médicas disponibles

**Campos:**

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(100) | NOT NULL | Nombre especialidad |
| descripcion | TEXT | NULLABLE | Descripción detallada |
| precio | DECIMAL(10,2) | NOT NULL | Precio base en RD$ |

**Índices:**
- PK: id
- INDEX: nombre

**Notas de Precio:**
- Precio base es SIEMPRE sin seguro
- Con seguro: precio * 0.25 (75% descuento)
- Se calcula dinámicamente en PHP

---

### 3. TABLA: `doctores`

**Propósito:** Relacionar usuarios tipo "doctor" con sus especialidades

**Campos:**

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| nombre | VARCHAR(100) | NOT NULL | Nombre del doctor (copia) |
| id_especialidad | INT | FK, NULLABLE | Referencia a especialidad |
| id_usuario | INT | FK, NULLABLE | Referencia al usuario doctor |

**Relaciones:**
- FK `id_especialidad` → `especialidades.id`
- FK `id_usuario` → `usuarios.id` (ON DELETE CASCADE)

**Índices:**
- PK: id
- FK: id_especialidad, id_usuario

**Justificación:**
- Tabla separada para diferenciar estructura de doctor vs usuario
- Permite asignar especialidades múltiples (futura expansión)
- Mantiene integridad referencial

---

### 4. TABLA: `citas`

**Propósito:** Registro de todas las citas agendadas en el sistema

**Campos:**

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| id | INT | PK, AUTO_INCREMENT | Identificador único |
| id_usuario | INT | FK | Referencia al paciente |
| id_especialidad | INT | FK | Especialidad de cita |
| id_doctor | INT | FK | Doctor que atiende |
| fecha | DATE | NOT NULL | Fecha de cita (YYYY-MM-DD) |
| hora | TIME | NOT NULL | Hora de cita (HH:MM:SS) |
| estado | ENUM | DEFAULT 'pendiente' | Ciclo de vida de cita |
| fecha_creacion | TIMESTAMP | DEFAULT NOW | Cuándo se registró |

**Estados de Cita:**
- `pendiente` - Recién creada, no confirmada
- `confirmada` - Doctor confirmó disponibilidad
- `cancelada` - Anulada por paciente o doctor

**Relaciones:**
- FK `id_usuario` → `usuarios.id` (ON DELETE CASCADE)
- FK `id_doctor` → `doctores.id` (ON DELETE CASCADE)
- FK `id_especialidad` → `especialidades.id` (ON DELETE SET NULL)

**Índices:**
- PK: id
- FK: id_usuario, id_doctor, id_especialidad
- INDEX: fecha, estado

**Justificación del Diseño:**
- ENUM para estado (optimización, solo 3 valores posibles)
- Stored fecha_creacion para auditoría
- Índices en fecha/estado para búsquedas frecuentes

---

## 🔄 Flujo de Citas

```
1. Paciente se registra
   └─→ Se crea en tabla usuarios (rol = 'user')

2. Admin crea doctor
   └─→ Se crea en usuarios (rol = 'doctor')
   └─→ Se crea en doctores (con id_usuario y id_especialidad)

3. Paciente busca especialidad
   └─→ Lee de especialidades
   └─→ Obtiene lista de doctores de esa especialidad

4. Paciente agenda cita
   └─→ INSERT en citas (estado = 'pendiente')
   └─→ Sistema calcula precio automático

5. Doctor ve cita
   └─→ Puede confirmar o cancelar

6. Cita en historial
   └─→ Ambos pueden ver estado y detalles
```

---

## 💾 Conexión a Base de Datos

**Patrón: PDO (PHP Data Objects)**

```php
// config/db.php
$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=utf8mb4",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);
```

**Ventajas:**
- Prepared statements automáticos
- Previene inyección SQL
- Soporte para múltiples bases de datos
- Manejo de errores con excepciones

---

## 🔐 Seguridad - Puntos Implementados

### 1. Autenticación
```
✅ Contraseñas cifradas con password_hash(password, PASSWORD_DEFAULT)
✅ Verificación con password_verify($input, $hash)
✅ Sessions con verificación de rol
✅ Logout que destruye sesión
```

### 2. Inyección SQL
```
✅ Prepared statements en 100% de queries
✅ PDO con emulate_prepares=false
✅ Nunca concatenar variables en SQL
```

### 3. XSS (Cross-Site Scripting)
```
✅ htmlspecialchars() en todos los outputs
✅ JSON_ENCODE con flags de seguridad
✅ Sanitización de inputs
```

### 4. Control de Acceso
```
✅ Validación de sesiones en cada página
✅ Verificación de rol (admin/doctor/user)
✅ Redirección de acceso denegado
✅ Funciones exclusivas por rol
```

---

## 🎯 Criterios Académicos Cumplidos

| Criterio | Estado | Detalles |
|----------|--------|----------|
| PHP Backend | ✅ | PDO, prepared statements |
| MySQL Database | ✅ | 4 tablas, relaciones FK, índices |
| HTML5 + CSS3 | ✅ | Estructura semántica, responsive |
| JavaScript | ✅ | Validaciones, AJAX, efectos |
| PDO/mysqli | ✅ | PDO implementado |
| Sesiones | ✅ | Login/logout funcional |
| 2+ Roles | ✅ | 3 roles: admin, doctor, user |
| CRUD Completo | ✅ | Crear, leer, actualizar, eliminar |
| Permisos por Rol | ✅ | Funciones exclusivas por rol |
| Validaciones C+S | ✅ | JavaScript + PHP |
| password_hash() | ✅ | En registro y cambio password |
| Mensajes Color | ✅ | Rojo (error), verde (éxito) |
| Documentación | ✅ | Comentarios + documentos |
| Estructura Carpetas | ✅ | /admin, /user, /config, /includes |

---

## 📊 Estadísticas del Proyecto

- **Archivos PHP:** 25+
- **Archivos CSS:** 2
- **Archivos JavaScript:** 3
- **Líneas de Código:** 3000+
- **Tablas Base de Datos:** 4
- **Relaciones FK:** 7
- **Funciones CRUD:** 20+

---

## 🔄 Versiones y Cambios

### v1.0 (Inicial)
- Estructura base PHP + MySQL
- Autenticación de usuarios
- Panel admin básico
- Gestión de citas

### v1.1 (Actual)
- Mejorada UI/UX
- Logo dinámico en header
- Creación de citas por doctor
- Perfil de usuario mejorado
- Documentación completa

---

## 📞 Contacto y Soporte

Para consultas técnicas:
- Revisar código con comentarios descriptivos
- Consultar este documento técnico
- Revisar base datos con phpMyAdmin
- Ejecutar queries de prueba

---

**Generado:** 20 de marzo de 2026
**Versión Documento:** 1.1
**Estado:** Aprobado para entrega
