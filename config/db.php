<?php
// =========================
//       CONEXIÓN PDO
// =========================

// CONFIGURACIÓN
$host = "localhost";
$db   = "citas_medicas";
$user = "root";
$pass = "";

// MODO DEBUG (cambiar a false en producción)
$debug = true;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        $options
    );

} catch (PDOException $e) {

    if($debug){
        die("Error de conexión: " . $e->getMessage());
    } else {
        die("Error de conexión con la base de datos.");
    }

}
?>