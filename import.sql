-- CREACIÓN DE BASE DE DATOS
CREATE DATABASE citas_medicas;
USE citas_medicas;

-- TABLA USUARIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    cedula VARCHAR(20),
    telefono VARCHAR(20),
    correo VARCHAR(100),
    password VARCHAR(255),
    rol ENUM('admin','user') DEFAULT 'user',
    seguro ENUM('si','no') DEFAULT 'no'
);

-- TABLA ESPECIALIDADES
CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10,2)
);

-- TABLA CITAS
CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    especialidad_id INT,
    fecha DATE,
    estado VARCHAR(50),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (especialidad_id) REFERENCES especialidades(id)
);

-- DATOS INICIALES
INSERT INTO especialidades (nombre, descripcion, precio) VALUES
('Psicología','Salud mental',2000),
('Ginecología','Salud femenina',2500),
('Medicina General','Consulta básica',1500),
('Cardiología','Corazón',3000);