<?php
include("../config/db.php"); // Conexión PDO

// Leer JSON enviado por axios
$data = json_decode(file_get_contents("php://input"), true);
$esp_id = $data['id_especialidad'] ?? 0;

$stmt = $pdo->prepare("SELECT id, nombre FROM doctores WHERE id_especialidad = ?");
$stmt->execute([$esp_id]);
$doctores = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($doctores);
?>