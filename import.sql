-- =========================
-- BASE DE DATOS CITAS_MEDICAS
-- =========================
DROP DATABASE IF EXISTS citas_medicas;
CREATE DATABASE citas_medicas;
USE citas_medicas;

-- =========================
-- TABLA USUARIOS
-- =========================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20),
    telefono VARCHAR(20),
    correo VARCHAR(100) UNIQUE NOT NULL,  -- mantiene tu campo original
    password VARCHAR(255) NOT NULL,
    seguro VARCHAR(10),
    rol ENUM('admin','user') DEFAULT 'user',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLA ESPECIALIDADES
-- =========================
CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL
);

-- =========================
-- TABLA DOCTORES
-- =========================
CREATE TABLE doctores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_especialidad INT,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id)
);

-- =========================
-- TABLA CITAS
-- =========================
CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_doctor INT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_doctor) REFERENCES doctores(id)
);

-- =========================
-- DATOS INICIALES
-- =========================
INSERT INTO especialidades (nombre, descripcion, precio) VALUES
('Psicología','Atención y apoyo emocional',2000),
('Medicina General','Consulta general básica',1500),
('Cardiología','Corazón y sistema circulatorio',3000),
('Ginecología y Obstetricia','Salud femenina y embarazo',2800),
('Urología','Sistema urinario masculino/femenino',2700),
('Oncología','Diagnóstico y tratamiento de cáncer',3500),
('Nefrología','Salud de los riñones',2600),
('Endocrinología','Hormonas y metabolismo',2400),
('Traumatología y Ortopedia','Huesos y músculos',2500),
('Pediatría','Salud infantil',2200),
('Neonatología','Cuidados de recién nacidos',2300),
('Medicina Intensiva (UCI)','Cuidados intensivos críticos',4000),
('Radiología','Imágenes médicas diagnósticas',2100),
('Dermatología','Piel, cabello y uñas',2000),
('Oftalmología','Ojos y visión',2200);

INSERT INTO doctores (nombre, id_especialidad) VALUES
('Dr. Luis Fernández', 1),
('Dra. Carmen Rodríguez', 2),
('Dr. José Martínez', 3),
('Dra. Laura Gómez', 4),
('Dr. Ricardo Sánchez', 5),
('Dra. Patricia Díaz', 6),
('Dr. Manuel Herrera', 7),
('Dra. Andrea Castillo', 8),
('Dr. Javier Morales', 9),
('Dra. Daniela Ruiz', 10),
('Dr. Fernando Navarro', 11),
('Dra. Sofía Méndez', 12),
('Dr. Alberto Cruz', 13),
('Dra. Valeria Peña', 14),
('Dr. Miguel Ortega', 15);

-- =========================
-- USUARIO ADMIN
-- =========================
-- Reemplaza la contraseña hash por tu propia contraseña segura generada con password_hash()
INSERT INTO usuarios (nombre, cedula, telefono, correo, password, seguro, rol)
VALUES ('Admin Principal','0000000000','0000000000','admin@clinica.com', 
       '$2y$12$dprrmAro02bLkqoY2qcImuzBQOqMx0753bfllw5S1pQ0UCanDD2h.', 'si', 'admin'); -- usuario-admin@clinica.com contraseña-admin123