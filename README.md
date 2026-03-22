
# 🏥 Hospital & Human - Sistema de Citas Médicas

**Versión:** 1.0.0 ✅  
**Proyecto Final SOF-109** - Práctica de Laboratorio en PHP y MySQL  
**Última Actualización:** Marzo 2026

---

## 🗒️ Cambios Realizados (Changelog)

### v1.0.0 (marzo 2026) - RELEASE COMPLETO
✅ **Persistencia de Datos Garantizada**
- Actualización integral de los 15 endpoints AJAX (admin/ajax/)
- Validación de relaciones foráneas en TODAS las operaciones
- Transacciones MySQL para integridad de datos
- Timestamps de auditoría (fecha_creacion, fecha_actualizado)
- Manejo centralizado de errores (PDOException)
- Respuestas JSON estandarizadas
- Prevención de duplicados (correo, cédula)
- Prevención de conflictos de horario en citas
- Funciones centralizadas: respuestas.php, sesiones.php
- Documentación completa de pruebas y cambios
- ⚠️ **CRÍTICO:** Reparación de editar_cita.php (estaba haciendo INSERT en lugar de UPDATE)

✨ **Mejoras de Seguridad**
- 100% Prepared Statements en todas las queries
- Validación de autorización centralizada
- Sanitización consistente de datos
- Mejor manejo de contraseñas
- Logs de errores en backend

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

- ✅ Registro y autenticación de usuarios (pacientes, doctores, administradores)
- ✅ Sistema de roles diferenciado (admin, doctor, user)
- ✅ Gestión de especialidades médicas
- ✅ Cálculo automático de precios (con descuento por seguro)
- ✅ CRUD completo con persistencia garantizada
- ✅ Sistema de citas con estado (pendiente, confirmada, cancelada)
- ✅ Validaciones cliente y servidor (JavaScript + PHP)
- ✅ Contraseñas cifradas con password_hash()
- ✅ Transacciones ACID para integridad
- ✅ Auditoría con timestamps

---

## 🛠️ Requisitos Técnicos

- XAMPP (7.4+) o similar
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Navegador web moderno

### Tecnologías Utilizadas
- Backend: PHP 7.4+ (PDO)
- Base de Datos: MySQL 5.7+
- Frontend: HTML5, CSS3, JavaScript ES6+
- Validaciones: JavaScript + PHP
- Animaciones: Anime.js
- Servidor: Apache (XAMPP)
- Iconos: Boxicons

---

## 📦 Estructura del Proyecto

```
citas_medicas/
├── admin/                      # Panel de administración
│   ├── ajax/                   # ENDPOINTS AJAX ACTUALIZADOS (15 archivos)
│   │   ├── agregar_*.php       # CREATE con validaciones foráneas
│   │   ├── editar_*.php        # UPDATE con transacciones
│   │   ├── eliminar_*.php      # DELETE con cascada inteligente
│   │   ├── cambiar_*.php       # Estados y roles
│   │   └── get_*.php           # SELECT con validaciones
│   ├── dashboard.php          
│   ├── citas.php
│   ├── usuarios.php
│   ├── doctores.php
│   ├── especialidades.php
│   └── sidebar.php
├── auth/                       # Autenticación
│   ├── login.php
│   ├── register.php            # Registro con validaciones mejoradas
│   └── logout.php
├── doctor/                     # Panel del doctor
│   ├── dashboard.php
│   ├── mis_citas.php
│   └── perfil.php
├── user/                       # Panel del usuario/paciente
│   ├── index.php
│   ├── dashboard.php
│   ├── agendar.php
│   ├── agendar_ajax.php        # ENDPOINT AJAX mejorado
│   ├── mis_citas.php
│   ├── get_doctores.php
│   └── perfil.php
├── config/                     # Configuración Centralizada
│   ├── db.php                  # Conexión PDO
│   ├── respuestas.php          # 📌 Funciones JSON estandarizadas
│   └── sesiones.php            # 📌 Validación de sesión centralizada
├── includes/                   # Componentes reutilizables
│   ├── header.php
│   ├── header_dynamic.php
│   ├── footer.php
│   ├── floating_theme_toggle.php
│   └── validaciones_seguros.php
├── assets/                     # Recursos estáticos
│   ├── css/
│   │   ├── style.css
│   │   └── animations.css
│   ├── js/
│   │   ├── animations.js       # Anime.js centralizado
│   │   ├── main.js
│   │   ├── script.js
│   │   └── validaciones.js
│   └── img/
├── database.sql                # Script para crear BD
├── import.sql                  # Datos de prueba
├── index.php                   # Página de inicio
├── README.md                   # Este archivo
├── DOCUMENTACION_TECNICA.md    # Arquitectura y patrones
├── ER_Diagram.d2               # Diagrama entidad-relación
├── ACTUALIZACION_PERSISTENCIA_DATOS.md    # 📌 CAMBIOS FASE 3
├── GUIA_PRUEBA_PERSISTENCIA.md            # 📌 PRUEBAS PASO A PASO
└── RESUMEN_CAMBIOS_FASE3.md               # 📌 RESUMEN EJECUTIVO
```

---

## 🚀 Instalación y Ejecución

### Paso 1: Preparar archivos
```bash
# Copiar la carpeta en htdocs
cp -r citas_medicas /path/to/xampp/htdocs/
```

### Paso 2: Iniciar servicios
- Abrir XAMPP Control Panel
- Iniciar Apache y MySQL

### Paso 3: Crear Base de Datos
1. Abrir http://localhost/phpmyadmin
2. Crear base de datos: `citas_medicas`
3. Importar script: `database.sql`
4. (Opcional) Cargar datos de prueba: `import.sql`

### Paso 4: Acceder
- http://localhost/citas_medicas

---

## 👤 Usuarios de Prueba

| Usuario | Email | Contraseña | Rol |
|---------|-------|-----------|-----|
| Admin | admin@hospitalandhuman.com | 123456 | admin |
| Doctor | dr.luis@hospitalandhuman.com | 123456 | doctor |
| Paciente | testuser@example.com | 123456 | user |

---

## 🔐 Seguridad Implementada

### Protección de Datos
- ✅ Contraseñas cifradas (password_hash con PASSWORD_DEFAULT)
- ✅ Prepared Statements 100% (PDO con ? placeholders)
- ✅ Validación de sesiones en cada endpoint
- ✅ Sanitización de entrada (trim, filter_var, preg_replace)
- ✅ Control de acceso por rol
- ✅ Validación de relaciones foráneas
- ✅ Prevención de duplicados (correo, cédula)

### Integridad de Datos
- ✅ Transacciones MySQL (BEGIN/COMMIT/ROLLBACK)
- ✅ Timestamps de auditoría (fecha_creacion, fecha_actualizado)
- ✅ Cascada inteligente en eliminaciones
- ✅ Prevención de conflictos de horario

### Manejo de Errores
- ✅ Try-catch con PDOException específica
- ✅ Logging de errores en backend
- ✅ Respuestas JSON consistentes
- ✅ Mensajes de error seguros

---

## 📊 Estructura de Datos - Esquema BD

```sql
-- Tabla: usuarios
userid (PK), nombre, apellido, cedula (UNIQUE), telefono, 
correo (UNIQUE), password, seguro, genero, rol (admin|doctor|user),
fecha_registro, fecha_actualizado

-- Tabla: especialidades
id (PK), nombre, descripcion, precio, fecha_creacion, fecha_actualizado

-- Tabla: doctores
id (PK), nombre, id_especialidad (FK), id_usuario (FK), 
fecha_creacion, fecha_actualizado

-- Tabla: citas
id (PK), id_usuario (FK), id_doctor (FK), id_especialidad (FK),
fecha, hora, estado (pendiente|confirmada|cancelada|completada),
fecha_creacion, fecha_actualizado
```

---

## 🎯 Funcionalidades por Rol

### 👨‍💼 Administrador
- ✅ Ver/Crear/Editar/Eliminar usuarios
- ✅ Ver/Crear/Editar/Eliminar doctores
- ✅ Ver/Crear/Editar/Eliminar especialidades
- ✅ Ver/Crear/Editar/Eliminar citas
- ✅ Cambiar roles y estados
- ✅ Dashboard con estadísticas

### 👨‍⚕️ Doctor
- ✅ Ver citas asignadas
- ✅ Cambiar estado de citas (pendiente → confirmada/cancelada/completada)
- ✅ Ver perfil y editar información
- ✅ Dashboard personal

### 🧑‍🤝‍🧑 Paciente
- ✅ Registrarse en el sistema
- ✅ Agendar citas con doctores
- ✅ Ver citas propias
- ✅ Cancelar citas
- ✅ Ver perfil y editar información
- ✅ Dashboard personal

---

## 🧪 Pruebas de Persistencia

**Ver:** [GUIA_PRUEBA_PERSISTENCIA.md](GUIA_PRUEBA_PERSISTENCIA.md)

Pasos rápidos:
1. Crear registro en Admin Panel
2. Abrir PhpMyAdmin → Examinar tabla
3. ✅ Confirmar que aparece el registro
4. Editar → ✅ Confirma actualización persiste
5. Eliminar → ✅ Desaparece de BD

---

## 📖 Documentación

| Archivo | Contenido |
|---------|-----------|
| **DOCUMENTACION_TECNICA.md** | Arquitectura, patrones, funciones |
| **ACTUALIZACION_PERSISTENCIA_DATOS.md** | Cambios Fase 3 detallados |
| **GUIA_PRUEBA_PERSISTENCIA.md** | Instrucciones paso a paso para pruebas |
| **RESUMEN_CAMBIOS_FASE3.md** | Resumen ejecutivo del release v1.0.0 |
| **ER_Diagram.d2** | Diagrama entidad-relación D2 |

---

## ✨ Archivos Clave

### Funciones Centralizadas
- **config/respuestas.php** - JSON responses (responderExito, responderError, etc.)
- **config/sesiones.php** - Session validation (verificarSesionAdmin, etc.)
- **assets/js/animations.js** - Anime.js animations centralizadas

### Endpoints AJAX Mejorados
Todos en `admin/ajax/` con patrón:
1. Validar autorización
2. Validar campos
3. Sanitizar datos
4. Validar relaciones foráneas
5. Try-catch(PDOException)
6. Responder JSON consistente

---

## 🐛 Bugs Corregidos en v1.0.0

| Bug | Severidad | Estado |
|-----|-----------|--------|
| editar_cita.php hacía INSERT en lugar de UPDATE | 🔴 CRÍTICO | ✅ SOLUCIONADO |
| Sin validación de duplicados en agregar_usuario | 🟡 ALTO | ✅ SOLUCIONADO |
| Conflictos de horario no detectados | 🟡 ALTO | ✅ SOLUCIONADO |
| Estructura try/catch incompleta en register.php | 🟡 ALTO | ✅ SOLUCIONADO |
| Manejo de errores inconsistente | 🟠 MEDIO | ✅ SOLUCIONADO |

---

## 🚀 Próximas Mejoras

- [ ] Autenticación de dos factores (2FA)
- [ ] Tokens CSRF en formularios
- [ ] Notificaciones por email
- [ ] Sistema de historiales de citas
- [ ] Búsqueda y filtrado avanzado
- [ ] Exportar reportes (PDF, CSV)
- [ ] API REST para integración
- [ ] Dashboard mejorado con gráficos
- [ ] Recordatorios automáticos

---

## 📞 Contacto y Soporte

- Proyecto: SOF-109 - Práctica de Laboratorio
- Tecnología: PHP + MySQL
- Soporte: Ver documentación o archivos .md

---

**Estado:** ✅ RELEASE 1.0.0 - PRODUCCIÓN  
**Garantía:** ✅ Persistencia de datos 100%  
**Última verificación:** Marzo 2026

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
