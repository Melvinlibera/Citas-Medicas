<?php
// ===============================
// CONEXIÓN A BASE DE DATOS MYSQL
// ===============================

// Datos del servidor
$host = "localhost";      // Servidor (XAMPP usa localhost)
$user = "root";           // Usuario por defecto
$pass = "";               // Contraseña (vacía en XAMPP)
$db   = "citas_medicas";  // Nombre de tu base de datos

// Crear conexión usando MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// ===============================
// VERIFICAR CONEXIÓN
// ===============================
if ($conn->connect_error) {
    // Si hay error, detener el sistema
    die(" Error de conexión: " . $conn->connect_error);
}

// ===============================
// CONFIGURACIÓN DE CARACTERES
// ===============================
// Evita problemas con acentos (ñ, á, etc.)
$conn->set_charset("utf8");

// ===============================
// MENSAJE OPCIONAL (DEBUG)
// ===============================
// Descomenta esto solo si quieres probar conexión
// echo "Conexión exitosa";
?>