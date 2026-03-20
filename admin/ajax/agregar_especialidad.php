<?php
session_start();
require("../../config/db.php");
header("Content-Type: application/json");

// Validar sesión
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("INSERT INTO especialidades (nombre, descripcion, precio)
VALUES (?, ?, ?)");

$stmt->execute([
    $data['nombre'],
    $data['descripcion'],
    $data['precio']
]);

echo json_encode(["success"=>true,"message"=>"Especialidad agregada"]);