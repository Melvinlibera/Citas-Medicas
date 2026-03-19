-- =====================================================================
-- BASE DE DATOS: CITAS MÉDICAS - HOSPITAL & HUMAN
-- =====================================================================
-- Descripción: Script SQL para crear la estructura completa de la base de datos
--
-- Tablas:
-- 1. usuarios - Almacena información de pacientes y doctores
-- 2. especialidades - Especialidades médicas disponibles
-- 3. doctores - Información de los doctores
-- 4. citas - Registro de citas médicas agendadas
--
-- Relaciones:
-- - doctores.id_especialidad -> especialidades.id
-- - doctores.id_usuario -> usuarios.id
-- - citas.id_usuario -> usuarios.id
-- - citas.id_doctor -> doctores.id
-- - citas.id_especialidad -> especialidades.id
-- =====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- =====================================================================
-- CREAR BASE DE DATOS
-- =====================================================================
DROP DATABASE IF EXISTS `citas_medicas`;
CREATE DATABASE IF NOT EXISTS `citas_medicas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `citas_medicas`;

-- =====================================================================
-- TABLA: USUARIOS
-- =====================================================================
-- Almacena información de pacientes, doctores y administradores
-- Campos:
-- - id: Identificador único
-- - nombre: Nombre completo del usuario
-- - cedula: Cédula de identidad (única)
-- - telefono: Número de teléfono
-- - correo: Correo electrónico (único)
-- - password: Contraseña cifrada con password_hash()
-- - seguro: Tipo de seguro médico (nombre del ARS o 'privado')
-- - rol: Rol del usuario (user, doctor, admin)
-- - fecha_registro: Fecha de registro en el sistema
-- =====================================================================
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
  UNIQUE KEY `cedula_unique` (`cedula`),
  INDEX `idx_rol` (`rol`),
  INDEX `idx_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- TABLA: ESPECIALIDADES
-- =====================================================================
-- Almacena las especialidades médicas disponibles
-- Campos:
-- - id: Identificador único
-- - nombre: Nombre de la especialidad
-- - descripcion: Descripción detallada de la especialidad
-- - precio: Precio de la consulta en RD$ (sin seguro)
-- =====================================================================
CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- TABLA: DOCTORES
-- =====================================================================
-- Almacena información de los doctores
-- Campos:
-- - id: Identificador único
-- - nombre: Nombre del doctor
-- - id_especialidad: Referencia a la especialidad (FK)
-- - id_usuario: Referencia al usuario doctor (FK)
-- =====================================================================
CREATE TABLE `doctores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_especialidad` (`id_especialidad`),
  KEY `fk_usuario` (`id_usuario`),
  CONSTRAINT `doctores_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON DELETE SET NULL,
  CONSTRAINT `doctores_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- TABLA: CITAS
-- =====================================================================
-- Almacena las citas médicas agendadas
-- Campos:
-- - id: Identificador único
-- - id_usuario: Referencia al usuario/paciente (FK)
-- - id_especialidad: Referencia a la especialidad (FK)
-- - id_doctor: Referencia al doctor (FK)
-- - fecha: Fecha de la cita (YYYY-MM-DD)
-- - hora: Hora de la cita (HH:MM:SS)
-- - estado: Estado de la cita (pendiente, confirmada, cancelada)
-- - fecha_creacion: Fecha de creación del registro
-- =====================================================================
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
  KEY `fk_usuario` (`id_usuario`),
  KEY `fk_especialidad` (`id_especialidad`),
  KEY `fk_doctor` (`id_doctor`),
  KEY `idx_fecha` (`fecha`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`) ON DELETE SET NULL,
  CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- INSERCIÓN DE DATOS DE PRUEBA
-- =====================================================================

-- =====================================================================
-- INSERTAR USUARIOS (Admin y Doctores)
-- =====================================================================
-- Contraseña de prueba: "123456" (cifrada con password_hash)
-- Hash: $2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.

INSERT INTO `usuarios` (`id`, `nombre`, `cedula`, `telefono`, `correo`, `password`, `seguro`, `rol`, `fecha_registro`) VALUES
-- Admin
(1, 'Admin Principal', '0000000000', '0000000000', 'admin@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', 'si', 'admin', NOW()),

-- Doctores
(2, 'Dr. Luis Fernández', NULL, NULL, 'dr.luis@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(3, 'Dra. Carmen Rodríguez', NULL, NULL, 'dra.carmen@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(4, 'Dr. José Martínez', NULL, NULL, 'dr.jose@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(5, 'Dra. Laura Gómez', NULL, NULL, 'dra.laura@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(6, 'Dr. Ricardo Sánchez', NULL, NULL, 'dr.ricardo@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(7, 'Dra. Patricia Díaz', NULL, NULL, 'dra.patricia@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(8, 'Dr. Manuel Herrera', NULL, NULL, 'dr.manuel@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(9, 'Dra. Andrea Castillo', NULL, NULL, 'dra.andrea@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(10, 'Dr. Javier Morales', NULL, NULL, 'dr.javier@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(11, 'Dra. Daniela Ruiz', NULL, NULL, 'dra.daniela@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(12, 'Dr. Fernando Navarro', NULL, NULL, 'dr.fernando@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(13, 'Dra. Sofía Méndez', NULL, NULL, 'dra.sofia@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(14, 'Dr. Alberto Cruz', NULL, NULL, 'dr.alberto@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(15, 'Dra. Valeria Peña', NULL, NULL, 'dra.valeria@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW()),
(16, 'Dr. Miguel Ortega', NULL, NULL, 'dr.miguel@hospitalandhuman.com', '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', NULL, 'doctor', NOW());

-- =====================================================================
-- INSERTAR ESPECIALIDADES
-- =====================================================================
INSERT INTO `especialidades` (`id`, `nombre`, `descripcion`, `precio`) VALUES
(1, 'Psicología', 'Atención psicológica integral para salud mental, estrés, ansiedad y bienestar emocional.', 2000.00),
(2, 'Medicina General', 'Consulta médica básica, diagnóstico inicial y derivación a especialistas.', 1500.00),
(3, 'Cardiología', 'Diagnóstico y tratamiento de enfermedades del corazón y sistema cardiovascular.', 3500.00),
(4, 'Ginecología y Obstetricia', 'Salud femenina, embarazo, parto y cuidados postparto.', 3000.00),
(5, 'Urología', 'Tratamiento del sistema urinario y reproductor masculino.', 2800.00),
(6, 'Oncología', 'Tratamiento y seguimiento de cáncer y tumores malignos.', 5000.00),
(7, 'Nefrología', 'Diagnóstico y tratamiento de enfermedades del riñón.', 3200.00),
(8, 'Endocrinología', 'Tratamiento de trastornos hormonales y metabólicos.', 3000.00),
(9, 'Traumatología y Ortopedia', 'Tratamiento de lesiones óseas, articulares y musculares.', 3500.00),
(10, 'Pediatría', 'Atención médica integral de niños y adolescentes.', 2000.00),
(11, 'Neonatología', 'Cuidado especializado de recién nacidos.', 4000.00),
(12, 'Medicina Intensiva (UCI)', 'Atención de pacientes críticos en cuidados intensivos.', 6000.00),
(13, 'Radiología', 'Diagnóstico por imágenes (rayos X, tomografía, resonancia).', 2500.00),
(14, 'Dermatología', 'Tratamiento de enfermedades de la piel.', 2200.00),
(15, 'Oftalmología', 'Diagnóstico y tratamiento de problemas visuales.', 2700.00);

-- =====================================================================
-- INSERTAR DOCTORES
-- =====================================================================
INSERT INTO `doctores` (`id`, `nombre`, `id_especialidad`, `id_usuario`) VALUES
(1, 'Dr. Luis Fernández', 1, 2),
(2, 'Dra. Carmen Rodríguez', 2, 3),
(3, 'Dr. José Martínez', 3, 4),
(4, 'Dra. Laura Gómez', 4, 5),
(5, 'Dr. Ricardo Sánchez', 5, 6),
(6, 'Dra. Patricia Díaz', 6, 7),
(7, 'Dr. Manuel Herrera', 7, 8),
(8, 'Dra. Andrea Castillo', 8, 9),
(9, 'Dr. Javier Morales', 9, 10),
(10, 'Dra. Daniela Ruiz', 10, 11),
(11, 'Dr. Fernando Navarro', 11, 12),
(12, 'Dra. Sofía Méndez', 12, 13),
(13, 'Dr. Alberto Cruz', 13, 14),
(14, 'Dra. Valeria Peña', 14, 15),
(15, 'Dr. Miguel Ortega', 15, 16);

-- =====================================================================
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================================
ALTER TABLE `usuarios` ADD INDEX `idx_cedula` (`cedula`);
ALTER TABLE `citas` ADD INDEX `idx_doctor_fecha` (`id_doctor`, `fecha`);
ALTER TABLE `citas` ADD INDEX `idx_usuario_fecha` (`id_usuario`, `fecha`);

-- =====================================================================
-- CONFIGURACIÓN FINAL
-- =====================================================================
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- =====================================================================
-- FIN DEL SCRIPT SQL
-- =====================================================================
-- importar este archivo en phpMyAdmin:
-- 1. Abre phpMyAdmin
-- 2. Ve a la pestaña "Importar"
-- 3. Selecciona este archivo
-- 4. Haz clic en "Continuar"
--
-- O desde la línea de comandos:
-- mysql -u root -p < database.sql
-- =====================================================================
