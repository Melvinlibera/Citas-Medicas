<?php
session_start();
header('Content-Type: application/json');

include("../config/db.php");

// SEGURIDAD
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit;
}

// VALIDAR MÉTODO
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $cedula = $_POST['cedula'] ?? null;
    $correo = $_POST['correo'] ?? null;
    $id_especialidad = $_POST['id_especialidad'] ?? null;

    if(!$id || !$nombre || !$cedula || !$correo || !$id_especialidad){
        echo json_encode(['status'=>'error','message'=>'Todos los campos son obligatorios']);
        exit;
    }

    try {

        $pdo->beginTransaction();

        // 1. OBTENER ID_USUARIO DEL DOCTOR
        $stmt = $pdo->prepare("SELECT id_usuario FROM doctores WHERE id = ?");
        $stmt->execute([$id]);
        $doctor = $stmt->fetch();

        if(!$doctor){
            throw new Exception("Doctor no encontrado");
        }

        $id_usuario = $doctor['id_usuario'];

        // 2. ACTUALIZAR USUARIO
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre=?, cedula=?, correo=? 
            WHERE id=?
        ");
        $stmt->execute([$nombre, $cedula, $correo, $id_usuario]);

        // 3. ACTUALIZAR DOCTOR
        $stmt = $pdo->prepare("
            UPDATE doctores 
            SET nombre=?, id_especialidad=? 
            WHERE id=?
        ");
        $stmt->execute([$nombre, $id_especialidad, $id]);

        $pdo->commit();

        echo json_encode([
            'status'=>'success',
            'message'=>'Doctor actualizado correctamente'
        ]);

    } catch (Exception $e){

        if($pdo->inTransaction()){
            $pdo->rollBack();
        }

        echo json_encode([
            'status'=>'error',
            'message'=>$e->getMessage()
        ]);
    }

} else {
    echo json_encode(['status'=>'error','message'=>'Solicitud inválida']);
}
?>