<?php
// =========================
// CONEXIÓN A LA BASE DE DATOS
// =========================
$host = "localhost";
$db   = "citas_medicas";
$user = "root";
$pass = "";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores reales
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch limpio
    PDO::ATTR_EMULATE_PREPARES   => false,                  // seguridad real
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
} catch (PDOException $e) {
    die("Error de conexión con la base de datos: " . $e->getMessage());
}
?>