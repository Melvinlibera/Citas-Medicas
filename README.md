# 🏥 Hospital & Human - Sistema de Citas Médicas

[![Version](https://img.shields.io/badge/version-2.0-blue.svg)](https://github.com/Melvinlibera/Citas-Medicas)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)

Un sistema completo de gestión de citas médicas desarrollado con PHP, MySQL y JavaScript. Permite a pacientes agendar citas, ver información de especialistas y gestionar sus citas de forma segura y profesional.

## ✨ Características Principales

- ✅ **Autenticación segura** con roles diferenciados (admin, doctor, user)
- ✅ **Agendamiento de citas** con validación de disponibilidad
- ✅ **Cálculo automático de precios** con descuento por seguro (75%)
- ✅ **Listado de especialidades** con médicos asociados
- ✅ **Panel de administración** completo
- ✅ **Validaciones robustas** en cliente y servidor
- ✅ **Diseño responsivo** y profesional
- ✅ **Base de datos normalizada** con relaciones

## 🚀 Inicio Rápido

### Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite
- XAMPP, WAMP o servidor web similar

### Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/Melvinlibera/Citas-Medicas.git
cd Citas-Medicas
```

2. **Configurar la base de datos**
```bash
# Importar el script SQL
mysql -u root -p < database.sql

# O usar phpMyAdmin:
# 1. Abre http://localhost/phpmyadmin
# 2. Ve a "Importar"
# 3. Selecciona database.sql
# 4. Haz clic en "Continuar"
```

3. **Configurar conexión a BD** (si es necesario)
Edita `config/db.php`:
```php
$host = 'localhost';
$db = 'citas_medicas';
$user = 'root';
$password = '';
```

4. **Colocar en servidor web**
- XAMPP: `C:\xampp\htdocs\Citas-Medicas`
- WAMP: `C:\wamp\www\Citas-Medicas`
- Linux: `/var/www/html/Citas-Medicas`

5. **Acceder al proyecto**
```
http://localhost/Citas-Medicas/
```

## 👤 Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@hospitalandhuman.com | 123456 |
| Doctor | dr.luis@hospitalandhuman.com | 123456 |
| Paciente | Crear nuevo registro | - |

## 📁 Estructura del Proyecto

```
Citas-Medicas/
├── admin/              # Panel de administración
├── auth/               # Autenticación (login, register)
├── user/               # Panel de usuario
├── especialidades/     # Vistas de especialidades
├── assets/             # CSS, JS, imágenes
├── config/             # Configuración de BD
├── includes/           # Headers y footers
├── index.php           # Página principal
└── database.sql        # Script de base de datos
```

## 🎯 Funcionalidades

### Para Pacientes

- **Registrarse** con validación de datos
- **Agendar citas** con especialistas
- **Ver mis citas** con estado
- **Información de precios** con y sin seguro
- **Perfil personal** con datos de contacto

### Para Administradores

- **Gestionar doctores** (CRUD)
- **Gestionar especialidades** (CRUD)
- **Gestionar citas** del sistema
- **Gestionar usuarios** y roles
- **Ver estadísticas** del sistema

## 🔐 Seguridad

- Contraseñas cifradas con `password_hash()`
- Prepared statements para prevenir inyección SQL
- Validaciones robustas en cliente y servidor
- Sesiones seguras con validación
- Escapado de salida HTML

## 📊 Tecnologías Utilizadas

| Tecnología | Uso |
|-----------|-----|
| PHP 7.4+ | Backend |
| MySQL 5.7+ | Base de datos |
| HTML5 | Estructura |
| CSS3 | Estilos y animaciones |
| JavaScript ES6+ | Validaciones e interactividad |
| PDO | Acceso a BD |

## 📖 Documentación

Para documentación completa, ver [DOCUMENTACION_PROYECTO.md](DOCUMENTACION_PROYECTO.md)

Incluye:
- Guía de instalación detallada
- Guía de uso para usuarios
- Diagrama Entidad-Relación
- Mejoras implementadas
- Solución de problemas

## 🐛 Solución de Problemas

### Error de conexión a BD
```
Verifica que:
1. MySQL esté corriendo
2. Las credenciales en config/db.php sean correctas
3. La base de datos 'citas_medicas' exista
```

### Página en blanco
```
1. Activa reporte de errores en PHP
2. Revisa los logs del servidor
3. Verifica la sintaxis PHP
```

### Las citas no se guardan
```
1. Verifica que la sesión esté activa
2. Comprueba los permisos de la BD
3. Revisa la validación de datos
```

## 📝 Mejoras Implementadas (v2.0)

- ✅ Validaciones robustas en PHP y JavaScript
- ✅ Página de especialidades completamente refactorizada
- ✅ Sistema de cálculo de precios con descuento
- ✅ Agendamiento integrado desde especialidades
- ✅ Documentación completa del proyecto
- ✅ Diseño profesional y responsivo
- ✅ Seguridad mejorada en todas las operaciones
- ✅ Base de datos optimizada

## 📞 Contacto

- **Email:** info@hospitalandhuman.com
- **Teléfono:** +1 (809) 123-4567
- **Horario:** Lunes a Domingo, 7:00 AM - 10:00 PM

## 📄 Licencia

Este proyecto está bajo licencia MIT. Ver [LICENSE](LICENSE) para más detalles.

## 👨‍💻 Autor

**Melvyn Libera Torres**
- Institución: ITSC - Laboratorio SOF-109
- Versión: 2.0
- Fecha: Marzo 2026

---

**¡Gracias por usar Hospital & Human!** 🏥
