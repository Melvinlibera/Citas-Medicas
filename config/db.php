<?php
// =========================
// CONEXIÓN A LA BASE DE DATOS
// =========================

// Datos de conexión
$host = "localhost";
$db   = "citas_medicas";
$user = "root";
$pass = "";

// Opciones de PDO para seguridad y manejo de errores
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // lanza excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // devuelve arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // evita inyección SQL
];

try {
    // Conexión principal
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
} catch (PDOException $e) {
    // Si falla, muestra mensaje y termina
    die("Error de conexión con la base de datos: " . $e->getMessage());
}

// Función opcional para reconectar si el PDO se pierde (útil en AJAX largos)
function getPDO() {
    global $host, $db, $user, $pass, $options;
    try {
        return new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
    } catch (PDOException $e) {
        die("Error de conexión al intentar reconectar: " . $e->getMessage());
    }
}
?>