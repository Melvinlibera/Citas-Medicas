<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $id_paciente = $_POST['id_paciente'];
    $id_doctor = $_POST['id_doctor'];
    $id_especialidad = $_POST['id_especialidad'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    if(!$id_paciente || !$id_doctor || !$fecha || !$hora){
        echo json_encode(['message'=>'Datos incompletos']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO citas (id_paciente,id_doctor,id_especialidad,fecha,hora) VALUES (?,?,?,?,?)");
    $stmt->execute([$id_paciente,$id_doctor,$id_especialidad,$fecha,$hora]);

    echo json_encode(['message'=>'Cita creada']);
}