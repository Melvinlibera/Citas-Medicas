-- ======= CREAR BASE DE DATOS y USARLA =======
DROP DATABASE IF EXISTS citas_medicas;
CREATE DATABASE citas_medicas;
USE citas_medicas;

-- ======= USUARIOS =======
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20),
    telefono VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','user') DEFAULT 'user',
    seguro ENUM('si','no') DEFAULT 'no'
);

-- ======= ESPECIALIDADES =======
CREATE TABLE especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL
);

-- ======= DOCTORES =======
CREATE TABLE doctores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_especialidad INT,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id)
);

-- ======= CITAS =======
CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    id_doctor INT,
    fecha DATE NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (id_doctor) REFERENCES doctores(id)
);

-- ======= INSERTAR ESPECIALIDADES =======
INSERT INTO especialidades (nombre, descripcion, precio) VALUES
('Psicología','Atención psicológica y terapia',2000),
('Medicina General','Consulta básica general',1500),
('Cardiología','Especialista en corazón',3000),
('Ginecología y Obstetricia','Salud femenina y embarazo',2800),
('Urología','Salud del sistema urinario',2700),
('Oncología','Cáncer y tumores',3500),
('Nefrología','Riñones y filtración renal',2600),
('Endocrinología','Hormonas y metabolismo',2400),
('Traumatología y Ortopedia','Huesos y músculos',2500),
('Pediatría','Niños y adolescentes',2200),
('Neonatología','Recien nacidos',2300),
('Medicina Intensiva (UCI)','Cuidado crítico',4000),
('Radiología','Imágenes médicas',2100),
('Dermatología','Piel',2000),
('Oftalmología','Ojos y visión',2200);

-- ======= INSERTAR DOCTORES =======
INSERT INTO doctores (nombre, id_especialidad) VALUES
('Dr. Luis Fernández', 2),
('Dra. Carmen Rodríguez', 3),
('Dr. José Martínez', 4),
('Dra. Laura Gómez', 5),
('Dr. Ricardo Sánchez', 6),
('Dra. Patricia Díaz', 7),
('Dr. Manuel Herrera', 8),
('Dra. Andrea Castillo', 9),
('Dr. Javier Morales', 10),
('Dra. Daniela Ruiz', 11),
('Dr. Fernando Navarro', 12),
('Dra. Sofía Méndez', 13),
('Dr. Alberto Cruz', 14),
('Dra. Valeria Peña', 15),
('Dr. Miguel Ortega', 1);