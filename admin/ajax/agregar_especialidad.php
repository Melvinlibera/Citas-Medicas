<?php
require("../config/db.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("INSERT INTO especialidades (nombre, descripcion, precio)
VALUES (?, ?, ?)");

$stmt->execute([
    $data['nombre'],
    $data['descripcion'],
    $data['precio']
]);

echo json_encode(["success"=>true,"message"=>"Especialidad agregada"]);