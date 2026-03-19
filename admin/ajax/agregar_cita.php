<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

// SEGURIDAD
if(!isset($_SESSION['usuario'])){
    echo json_encode(['success'=>false,'message'=>'No autorizado']);
    exit;
}

try {

    // VALIDAR DATOS
    if(
        empty($_POST['id_usuario']) ||
        empty($_POST['id_doctor']) ||
        empty($_POST['id_especialidad']) ||
        empty($_POST['fecha']) ||
        empty($_POST['hora'])
    ){
        throw new Exception("Datos incompletos");
    }

    $id_usuario = $_POST['id_usuario'];
    $id_doctor = $_POST['id_doctor'];
    $id_especialidad = $_POST['id_especialidad'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // INSERTAR CITA
    $stmt = $pdo->prepare("
        INSERT INTO citas 
        (id_usuario, id_doctor, id_especialidad, fecha, hora, estado) 
        VALUES (?, ?, ?, ?, ?, 'pendiente')
    ");

    $stmt->execute([
        $id_usuario,
        $id_doctor,
        $id_especialidad,
        $fecha,
        $hora
    ]);

    echo json_encode([
        'success'=>true,
        'message'=>'Cita creada correctamente'
    ]);

} catch(Exception $e){

    echo json_encode([
        'success'=>false,
        'message'=>$e->getMessage()
    ]);
}
?>