<?php
session_start();
require("../../config/db.php");
header("Content-Type: application/json");

// Validar autorización
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("DELETE FROM citas WHERE id=?");
$stmt->execute([$data['id']]);

echo json_encode(["success"=>true,"message"=>"Cita eliminada"]);