<?php
include("../../config/db.php");
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$id_especialidad = $input['id_especialidad'] ?? 0;

$stmt = $pdo->prepare("SELECT id,nombre FROM doctores WHERE id_especialidad=?");
$stmt->execute([$id_especialidad]);
$docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($docs);