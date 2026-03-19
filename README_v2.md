# Hospital & Human - Sistema de Citas Médicas v2.0

Un sistema completo de gestión de citas médicas desarrollado con PHP, MySQL y JavaScript.

## Características Principales

- Autenticación segura con roles diferenciados (admin, doctor, user)
- Agendamiento de citas con validación de disponibilidad
- Cálculo automático de precios con descuento por seguro (75%)
- Listado de especialidades con médicos asociados
- Panel de administración completo
- Validaciones robustas en cliente y servidor
- Diseño responsivo y profesional
- Base de datos normalizada con relaciones

## Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite
- XAMPP, WAMP o servidor web similar

## Instalación Rápida

### 1. Importar Base de Datos

```bash
mysql -u root -p < database.sql
```

### 2. Configurar Conexión (si es necesario)

Edita `config/db.php` con tus credenciales de MySQL.

### 3. Colocar en Servidor Web

- XAMPP: `C:\xampp\htdocs\Citas-Medicas`
- WAMP: `C:\wamp\www\Citas-Medicas`
- Linux: `/var/www/html/Citas-Medicas`

### 4. Acceder

```
http://localhost/Citas-Medicas/
```

## Credenciales de Prueba

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | admin@hospitalandhuman.com | 123456 |
| Doctor | dr.luis@hospitalandhuman.com | 123456 |

## Mejoras Implementadas v2.0

- Validaciones robustas en PHP y JavaScript
- Página de especialidades completamente refactorizada
- Sistema de cálculo de precios con descuento
- Agendamiento integrado desde especialidades
- Documentación completa del proyecto
- Diseño profesional y responsivo
- Seguridad mejorada en todas las operaciones
- Base de datos optimizada

## Documentación Completa

Ver [DOCUMENTACION_PROYECTO.md](DOCUMENTACION_PROYECTO.md) para:
- Guía de instalación detallada
- Guía de uso para usuarios
- Diagrama Entidad-Relación
- Solución de problemas

## Seguridad

- Contraseñas cifradas con password_hash()
- Prepared statements para prevenir inyección SQL
- Validaciones en cliente y servidor
- Sesiones seguras
- Escapado de salida HTML

## Tecnologías

- PHP 7.4+
- MySQL 5.7+
- HTML5 / CSS3
- JavaScript ES6+
- PDO

## Contacto

- Email: info@hospitalandhuman.com
- Teléfono: +1 (809) 123-4567

## Licencia

MIT License - Todos los derechos reservados

---

Versión 2.0 - Marzo 2026
