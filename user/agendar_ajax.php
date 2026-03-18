<?php
session_start();
include("../config/db.php");

// Solo usuarios logueados
if(!isset($_SESSION['id'])){
    echo json_encode(['message' => 'Debes iniciar sesión']);
    exit;
}

// Leer POST
$especialidad = $_POST['especialidad'] ?? null;
$doctor = $_POST['doctor'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? '08:00';

if(!$especialidad || !$doctor || !$fecha || !$hora){
    echo json_encode(['message' => 'Todos los campos son obligatorios']);
    exit;
}

// Insertar cita
$stmt = $pdo->prepare("INSERT INTO citas (id_usuario, id_doctor, id_especialidad, fecha, hora, estado)
VALUES (?, ?, ?, ?, ?, 'pendiente')");
$stmt->execute([
    $_SESSION['id'],
    $doctor,
    $especialidad,
    $fecha,
    $hora
]);

echo json_encode(['message' => 'Cita agendada correctamente']);
?>