<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

// =========================
// SEGURIDAD
// =========================
if(!isset($_SESSION['usuario'])){
    echo json_encode(['success'=>false,'message'=>'No autorizado']);
    exit;
}

try {

    // =========================
    // VALIDAR DATOS
    // =========================
    if(
        empty($_POST['id_doctor']) ||
        empty($_POST['fecha']) ||
        empty($_POST['hora'])
    ){
        throw new Exception("Datos incompletos");
    }

    // ⚠️ EL USUARIO SE TOMA DE LA SESIÓN (NO DEL POST)
    $id_usuario = $_SESSION['id'];
    $id_doctor  = $_POST['id_doctor'];
    $fecha      = $_POST['fecha'];
    $hora       = $_POST['hora'];

    // =========================
    // VALIDAR DUPLICADO
    // =========================
    $check = $pdo->prepare("
        SELECT id FROM citas 
        WHERE id_doctor = ? AND fecha = ? AND hora = ?
    ");
    $check->execute([$id_doctor, $fecha, $hora]);

    if($check->fetch()){
        throw new Exception("Ese horario ya está ocupado");
    }

    // =========================
    // INSERT CORRECTO (SIN id_especialidad)
    // =========================
    $stmt = $pdo->prepare("
        INSERT INTO citas 
        (id_usuario, id_doctor, fecha, hora, estado) 
        VALUES (?, ?, ?, ?, 'pendiente')
    ");

    $stmt->execute([
        $id_usuario,
        $id_doctor,
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