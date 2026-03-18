-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-03-2026 a las 00:30:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `citas_medicas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_doctor` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctores`
--

CREATE TABLE `doctores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `id_especialidad` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `doctores`
--

INSERT INTO `doctores` (`id`, `nombre`, `id_especialidad`) VALUES
(1, 'Dr. Luis Fernández', 1),
(2, 'Dra. Carmen Rodríguez', 2),
(3, 'Dr. José Martínez', 3),
(4, 'Dra. Laura Gómez', 4),
(5, 'Dr. Ricardo Sánchez', 5),
(6, 'Dra. Patricia Díaz', 6),
(7, 'Dr. Manuel Herrera', 7),
(8, 'Dra. Andrea Castillo', 8),
(9, 'Dr. Javier Morales', 9),
(10, 'Dra. Daniela Ruiz', 10),
(11, 'Dr. Fernando Navarro', 11),
(12, 'Dra. Sofía Méndez', 12),
(13, 'Dr. Alberto Cruz', 13),
(14, 'Dra. Valeria Peña', 14),
(15, 'Dr. Miguel Ortega', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `descripcion`, `precio`) VALUES
(1, 'Psicología', 'Atención psicológica integral para salud mental.', 2000.00),
(2, 'Medicina General', 'Consulta médica básica y diagnóstico inicial.', 1500.00),
(3, 'Cardiología', 'Diagnóstico y tratamiento del corazón.', 3500.00),
(4, 'Ginecología y Obstetricia', 'Salud femenina y embarazo.', 3000.00),
(5, 'Urologia', 'Sistema urinario y reproductor masculino.', 2800.00),
(6, 'Oncología', 'Tratamiento de cáncer.', 5000.00),
(7, 'Nefrología', 'Enfermedades del riñón.', 3200.00),
(8, 'Endocrinología', 'Trastornos hormonales.', 3000.00),
(9, 'Traumatología y Ortopedia', 'Lesiones óseas y musculares.', 3500.00),
(10, 'Pediatría', 'Atención médica infantil.', 2000.00),
(11, 'Neonatología', 'Cuidado de recién nacidos.', 4000.00),
(12, 'Medicina Intensiva (UCI)', 'Pacientes críticos.', 6000.00),
(13, 'Radiología', 'Diagnóstico por imágenes.', 2500.00),
(14, 'Dermatología', 'Salud de la piel.', 2200.00),
(15, 'Oftalmología', 'Salud visual.', 2700.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `seguro` varchar(10) DEFAULT NULL,
  `rol` varchar(20) DEFAULT 'user',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `cedula`, `telefono`, `correo`, `password`, `seguro`, `rol`, `fecha_registro`) VALUES
(1, 'Melvyn Liberata Torres', '40236101388', '8493509603', 'melvinliberata@hotmail.com', '$2y$10$xLh6cSo7zOOwMU/4U/Zlr.tDPZN22EovHGDuWfsUDYzII5gF5emLy', 'si', 'user', '2026-03-17 23:05:37'),
(2, 'Admin', '00000000000', '0000000000', 'admin@admin.com', '$2y$10$wH1QYvCz4XxF9JcFhG8lAezJ0Yg7G5mPpW3YQYb5Q4xkLrJ8GkZ6e', 'no', 'admin', '2026-03-17 23:10:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`id_usuario`),
  ADD KEY `fk_doctor` (`id_doctor`);

--
-- Indices de la tabla `doctores`
--
ALTER TABLE `doctores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `doctores`
--
ALTER TABLE `doctores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id`),
  ADD CONSTRAINT `fk_doctor` FOREIGN KEY (`id_doctor`) REFERENCES `doctores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `doctores`
--
ALTER TABLE `doctores`
  ADD CONSTRAINT `doctores_ibfk_1` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
