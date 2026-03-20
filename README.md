HOSPITAL & HUMAN - SISTEMA DE CITAS MÉDICAS
VERSIÓN 0.8 (BETA FUNCIONAL)

=====================================================
DESCRIPCIÓN DEL PROYECTO
=====================================================
Hospital & Human es una aplicación web desarrollada en PHP y MySQL que permite la gestión de citas médicas de manera eficiente.

El sistema permite a pacientes registrarse, iniciar sesión, agendar citas médicas y consultar su historial, mientras que los doctores pueden visualizar y gestionar sus citas desde un panel dedicado.

El proyecto cuenta con una interfaz moderna, responsiva y orientada a una experiencia de usuario clara y profesional.

=====================================================
FUNCIONALIDADES PRINCIPALES
=====================================================

PACIENTES:
- Registro de usuarios
- Inicio de sesión seguro (uso de contraseñas encriptadas)
- Agendar citas médicas
- Selección de especialidad y doctor
- Visualización de citas agendadas

DOCTORES:
- Panel de control
- Visualización de citas asignadas
- Cambio de estado de citas (pendiente, completada, cancelada)
- Visualización de datos del paciente

SISTEMA GENERAL:
- Conexión a base de datos MySQL mediante PDO
- Validación de datos en formularios
- Manejo de sesiones
- Interfaz responsiva (CSS moderno)
- Animaciones suaves y diseño profesional

=====================================================
TECNOLOGÍAS UTILIZADAS
=====================================================
- PHP
- MySQL
- HTML5
- CSS3
- JavaScript (básico)

=====================================================
ESTRUCTURA DEL PROYECTO
=====================================================

/config
    db.php (conexión a la base de datos)

/doctor
    mis_citas.php (panel de citas del doctor)

/css
    styles.css (estilos globales)

/img
    logo.png (logo del sistema)

/ (raíz)
    index.php (página principal)
    login.php
    register.php
    agendar.php

=====================================================
BASE DE DATOS
=====================================================
Nombre: citas_medicas

Tablas principales:
- usuarios
- citas
- especialidades
- doctores

Características:
- Relaciones entre usuarios, doctores y citas
- Uso de claves primarias y foráneas
- Campos validados (correo único, etc.)

=====================================================
INSTALACIÓN
=====================================================

1. Clonar el repositorio:
   git clone https://github.com/Melvinlibera/Citas-Medicas.git

2. Importar la base de datos:
   - Abrir phpMyAdmin
   - Crear base de datos "citas_medicas"
   - Importar el archivo .sql

3. Configurar la conexión:
   Editar /config/db.php con tus credenciales:
   - host
   - usuario
   - contraseña

4. Ejecutar el proyecto:
   - Colocar en htdocs (XAMPP) o www (WAMP)
   - Abrir en navegador: http://localhost/

=====================================================
ESTADO ACTUAL (v0.8)
=====================================================
- Sistema funcional en entorno local
- Interfaz mejorada con CSS moderno
- Panel de doctor operativo
- Sistema de citas parcialmente optimizado

=====================================================
PENDIENTES / MEJORAS FUTURAS
=====================================================
- Validación avanzada de datos
- Mejora del sistema de roles
- Panel de administración completo
- Notificaciones (correo o sistema interno)
- Optimización del diseño UI/UX
- Mejora en seguridad (tokens, protección CSRF)

=====================================================
AUTOR
=====================================================
Melvin Libera
Proyecto académico - Sistema de Citas Médicas

=====================================================
NOTAS
=====================================================
Este proyecto está en desarrollo y forma parte de un trabajo educativo. No está destinado para uso en producción sin mejoras adicionales de seguridad y escalabilidad.
