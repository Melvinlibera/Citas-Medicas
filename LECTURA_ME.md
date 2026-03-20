# 🏥 Hospital & Human - Sistema de Citas Médicas

**Proyecto Final SOF-109** - Práctica de Laboratorio en PHP y MySQL

---

## 📋 Descripción General

Sistema de gestión de citas médicas desarrollado en PHP y MySQL que permite a pacientes agendar citas con doctores especializados, a doctores gestionar sus citas, y a administradores supervizar todo el sistema.

### Características Principales

✅ **Registro y autenticación** de usuarios (pacientes, doctores, administradores)
✅ **Sistema de roles** diferenciado (3 roles: admin, doctor, user)
✅ **Gestión de especialidades médicas** con múltiples opciones
✅ **Cálculo automático de precios** (con descuento por seguro)
✅ **CRUD completo** (Crear, Leer, Actualizar, Eliminar)
✅ **Sistema de citas** con estado (pendiente, confirmada, cancelada)
✅ **Validaciones cliente y servidor** (JavaScript + PHP)
✅ **Contraseñas cifradas** con password_hash()

---

## 🛠️ Requisitos Técnicos

### Software Requerido
- **XAMPP** (versión 7.4+) o similar
- **PHP** 7.4 o superior
- **MySQL** 5.7 o superior
- **Navegador web moderno** (Chrome, Firefox, Edge, Safari)

### Tecnologías Utilizadas
- **Backend:** PHP (PDO)
- **Base de Datos:** MySQL
- **Frontend:** HTML5, CSS3
- **Validaciones:** JavaScript
- **Servidor:** Apache (incluido en XAMPP)

---

## 📦 Estructura del Proyecto

```
citas_medicas/
├── admin/                      # Panel de administración
│   ├── ajax/                   # Peticiones AJAX para admin
│   ├── dashboard.php          
│   ├── citas.php
│   ├── usuarios.php
│   ├── doctores.php
│   ├── especialidades.php
│   └── sidebar.php
├── auth/                       # Autenticación
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── doctor/                     # Panel del doctor
│   ├── dashboard.php
│   ├── mis_citas.php
│   └── perfil.php
├── user/                       # Panel del usuario/paciente
│   ├── index.php
│   ├── dashboard.php
│   ├── agendar.php
│   ├── agendar_ajax.php
│   ├── mis_citas.php
│   ├── get_doctores.php
│   └── perfil.php
├── config/                     # Configuración
│   └── db.php                  # Conexión PDO
├── includes/                   # Componentes reutilizables
│   ├── header.php
│   ├── header_dynamic.php
│   ├── footer.php
│   └── validaciones_seguros.php
├── assets/                     # Recursos estáticos
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   ├── main.js
│   │   ├── script.js
│   │   └── validaciones.js
│   └── img/
│       └── logo.png
├── database.sql                # Script para crear BD
├── import.sql                  # Datos de prueba
└── index.php                   # Página de inicio
```

---

## 🚀 Instalación y Ejecución

### Paso 1: Descargar y Ubicar el Proyecto

```bash
# Copiar la carpeta citas_medicas en htdocs
# C:\xampp\htdocs\citas_medicas\
```

### Paso 2: Iniciar XAMPP

1. Abrir XAMPP Control Panel
2. Iniciar **Apache** y **MySQL**

### Paso 3: Crear la Base de Datos

1. Acceder a phpMyAdmin: `http://localhost/phpmyadmin`
2. Crear nueva base de datos: `citas_medicas`
3. Ejecutar el script SQL:
   - En phpMyAdmin, seleccionar BD `citas_medicas`
   - Ir a pestaña **SQL**
   - Copiar contenido de `database.sql`
   - Ejecutar

**Alternativa rápida:**
```bash
# Ejecutar en terminal
mysql -u root < C:\xampp\htdocs\citas_medicas\database.sql
```

### Paso 4: Acceder a la Aplicación

Abrir navegador e ir a: `http://localhost/citas_medicas`

---

## 👤 Usuarios de Prueba

### Credenciales por Defecto
**Contraseña para todos:** `123456`

#### Administrador
- **Correo:** `admin@hospitalandhuman.com`
- **Rol:** Admin
- **Acceso:** Panel de administración completo

#### Doctor
- **Correo:** `dr.luis@hospitalandhuman.com`
- **Rol:** Doctor
- **Acceso:** Gestión de citas propias

#### Paciente
- **Correo:** testuser@example.com
- **Rol:** User
- **Acceso:** Agendar y ver citas

---

## 🔐 Características de Seguridad

### Implementadas
✅ **Contraseñas Cifradas:** password_hash() + password_verify()
✅ **Prepared Statements:** Protección contra inyección SQL
✅ **Validación de Sesiones:** Verificación de rol y autenticación
✅ **Sanitización de Datos:** htmlspecialchars() en outputs
✅ **Control de Acceso:** Rutas protegidas por rol

### Mejores Prácticas

```php
// Validación de sesión en cada página
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// PDO con Prepared Statements
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->execute([$correo]);

// Cifrado de contraseñas
$hash = password_hash($password, PASSWORD_DEFAULT);
password_verify($password_input, $hash);
```

---

## 📊 Estructura de Datos

### Tablas Principales

#### `usuarios`
Almacena información de pacientes, doctores y administradores
- Campos: id, nombre, cédula, teléfono, correo, password, seguro, rol
- PK: id | Índices: correo, cédula, rol

#### `especialidades`
Especialidades médicas disponibles
- Campos: id, nombre, descripción, precio
- PK: id | Índices: nombre

#### `doctores`
Información de doctores y relaciones
- Campos: id, nombre, id_especialidad, id_usuario
- FK: id_especialidad → especialidades.id, id_usuario → usuarios.id

#### `citas`
Registro de citas agendadas
- Campos: id, id_usuario, id_especialidad, id_doctor, fecha, hora, estado
- FK: id_usuario → usuarios.id, id_doctor → doctores.id
- Estados: pendiente, confirmada, cancelada

---

## 🎯 Funcionalidades por Rol

### 👨‍💼 Administrador
- ✅ Crear, editar, eliminar usuarios
- ✅ Crear, editar, eliminar doctores
- ✅ Crear, editar, eliminar especialidades
- ✅ Ver todas las citas del sistema
- ✅ Cambiar estado de citas

### 👨‍⚕️ Doctor
- ✅ Ver panel personal
- ✅ Ver todas sus citas
- ✅ Agendar citas para pacientes
- ✅ Cambiar estado de sus citas
- ✅ Editar perfil y cambiar contraseña

### 👨‍🔬 Paciente/Usuario
- ✅ Registrarse en el sistema
- ✅ Ver especialidades disponibles
- ✅ Ver doctores por especialidad
- ✅ Agendar citas
- ✅ Ver historial de citas
- ✅ Editar perfil y cambiar contraseña
- ✅ Ver precios con/sin seguro

---

## 🔧 Configuración

### Conexión a Base de Datos

Archivo: `config/db.php`

```php
$host = "localhost";      // Servidor
$db   = "citas_medicas";  // Nombre BD
$user = "root";           // Usuario MySQL
$pass = "";               // Contraseña MySQL
```

**Para cambiar credenciales:**
1. Editar `config/db.php`
2. Actualizar variables `$user` y `$pass`

---

## 📝 Validaciones Implementadas

### Cliente (JavaScript)

```javascript
// Validación de email
EMAIL: /^[^\s@]+@[^\s@]+\.[^\s@]+$/

// Validación de cédula (RD)
CEDULA: /^\d{9,11}$/

// Validación de teléfono
TELEFONO: /^\d{7,15}$/

// Contraseña mínimo 6 caracteres
PASSWORD_MIN: /^.{6,}$/
```

### Servidor (PHP)

Todas las entradas se validan en servidor antes de guardar:
- Campos obligatorios
- Formatos correctos
- Unicidad de emails/cédulas
- Permisos por rol

---

## 🐛 Solución de Problemas

### "Error: base de datos no encontrada"
→ Asegúrate de haber ejecutado `database.sql`

### "Error: conexión denegada"
→ Verifica que MySQL esté iniciado en XAMPP

### "Página en blanco"
→ Revisa permisos de carpeta y configuración en `config/db.php`

### "Contraseña incorrecta"
→ Usa la contraseña de prueba: `123456`

---

## 📈 Mejoras Futuras Sugeridas

1. Envío de correos de confirmación
2. Sistema de calificación de doctores
3. Reporte de citas por período
4. Dashboard de estadísticas mejorado
5. API REST para aplicaciones móviles
6. Sistema de pagos integrado
7. Recordatorios por SMS/Email
8. Historial médico detallado

---

## 📄 Documentación Adicional

- **Diagrama ER:** Ver archivo `ERD_DIAGRAM.txt`
- **Documentación Técnica:** Ver archivo `DOCUMENTACION_TECNICA.md`
- **Base de Datos:** Ver archivo `database.sql`

---

## 👥 Autor

**Hospital & Human Development Team**
Universidad: [Nombre Institución]
Proyecto: SOF-109 - Práctica de Laboratorio
Fecha: 2026

---

## 📞 Soporte

Para reportar errores o sugerencias, contactar al equipo de desarrollo.

**Última actualización:** 20 de marzo de 2026
