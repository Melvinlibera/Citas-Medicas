
# 🏥 Hospital & Human - Sistema de Citas Médicas

**Versión:** 0.9.7  
**Proyecto Final SOF-109** - Práctica de Laboratorio en PHP y MySQL
---

## 🗒️ Cambios Realizados (Changelog)

### v0.9.7 (marzo 2026)
- Nueva validación de datos en cliente y servidor (JavaScript y PHP)
- Mejoras en la seguridad: contraseñas cifradas, prepared statements, control de sesiones y roles
- CRUD completo para usuarios, doctores, especialidades y citas
- Implementación de roles diferenciados (admin, doctor, user)
- Gestión de especialidades médicas y cálculo automático de precios
- Sistema de citas con estados (pendiente, confirmada, cancelada)
- Paneles independientes para administrador, doctor y paciente
- Mejoras visuales y de usabilidad en el frontend
- Scripts de base de datos y datos de prueba incluidos
- Documentación técnica y diagrama ER añadidos
- Corrección de bugs menores y mejoras de rendimiento

---

## 📋 Descripción General

Sistema de gestión de citas médicas desarrollado en PHP y MySQL que permite a pacientes agendar citas con doctores especializados, a doctores gestionar sus citas, y a administradores supervizar todo el sistema.

### Características Principales

- Registro y autenticación de usuarios (pacientes, doctores, administradores)
- Sistema de roles diferenciado (admin, doctor, user)
- Gestión de especialidades médicas
- Cálculo automático de precios (con descuento por seguro)
- CRUD completo (Crear, Leer, Actualizar, Eliminar)
- Sistema de citas con estado (pendiente, confirmada, cancelada)
- Validaciones cliente y servidor (JavaScript + PHP)
- Contraseñas cifradas con password_hash()

---

## 🛠️ Requisitos Técnicos

- XAMPP (7.4+) o similar
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Navegador web moderno

### Tecnologías Utilizadas
- Backend: PHP (PDO)
- Base de Datos: MySQL
- Frontend: HTML5, CSS3
- Validaciones: JavaScript
- Servidor: Apache (XAMPP)

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

1. Copiar la carpeta `citas_medicas` en `htdocs` de XAMPP
2. Iniciar XAMPP y levantar Apache y MySQL
3. Crear la base de datos `citas_medicas` en phpMyAdmin y ejecutar el script `database.sql`
4. Acceder a `http://localhost/citas_medicas` en el navegador

---

## 👤 Usuarios de Prueba

- Contraseña para todos: `123456`
- Administrador: admin@hospitalandhuman.com
- Doctor: dr.luis@hospitalandhuman.com
- Paciente: testuser@example.com

---

## 🔐 Seguridad

- Contraseñas cifradas (password_hash)
- Prepared Statements (PDO)
- Validación de sesiones y roles
- Sanitización de datos
- Control de acceso por rol

---

## 📊 Estructura de Datos

- `usuarios`: id, nombre, cédula, teléfono, correo, password, seguro, rol
- `especialidades`: id, nombre, descripción, precio
- `doctores`: id, nombre, id_especialidad, id_usuario
- `citas`: id, id_usuario, id_especialidad, id_doctor, fecha, hora, estado

---

## 🎯 Funcionalidades por Rol

- **Administrador:** Gestión total de usuarios, doctores, especialidades y citas
- **Doctor:** Panel personal, gestión de citas propias, edición de perfil
- **Paciente:** Registro, agendamiento y consulta de citas, edición de perfil

---

## 📝 Validaciones

- Cliente: JavaScript (email, cédula, teléfono, contraseña)
- Servidor: PHP (campos obligatorios, unicidad, permisos)

---

## 🐛 Solución de Problemas

- Error de base de datos: ejecutar `database.sql`
- Conexión denegada: verificar MySQL en XAMPP
- Página en blanco: revisar permisos y configuración en `config/db.php`
- Contraseña incorrecta: usar `123456`

---

## 📈 Mejoras Futuras

- Envío de correos de confirmación
- Sistema de calificación de doctores
- Reportes y estadísticas
- API REST y recordatorios

---

## 📄 Documentación Adicional

- Diagrama ER: `ERD_DIAGRAM.txt`
- Documentación técnica: `DOCUMENTACION_TECNICA.md`
- Script BD: `database.sql`

---

## 👥 Autoría

Hospital & Human Development Team
Universidad: [Nombre Institución]
Proyecto: SOF-109 - Práctica de Laboratorio
Fecha: 2026

---

## 📞 Soporte

Para reportar errores o sugerencias, contactar al equipo de desarrollo.

**Última actualización:** 20 de marzo de 2026
