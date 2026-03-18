<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = $_POST['estado'];

    $stmt = $pdo->prepare("UPDATE citas SET fecha=?, hora=?, estado=? WHERE id=?");
    $stmt->execute([$fecha,$hora,$estado,$id]);

    echo json_encode(['message'=>'Cita actualizada']);
}