<?php
// ajax/editar_doctor.php
session_start();
header('Content-Type: application/json');

include("../config/db.php");

// Verifica sesión y rol admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit;
}

// Verifica que llegue POST
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $id_especialidad = $_POST['id_especialidad'] ?? null;

    if(!$id || !$nombre || !$id_especialidad){
        echo json_encode(['status'=>'error','message'=>'Todos los campos son obligatorios']);
        exit;
    }

    try {
        // Actualiza el doctor
        $stmt = $pdo->prepare("UPDATE doctores SET nombre = ?, id_especialidad = ? WHERE id = ?");
        $stmt->execute([$nombre, $id_especialidad, $id]);

        echo json_encode(['status'=>'success','message'=>'Doctor actualizado correctamente']);
    } catch (PDOException $e){
        echo json_encode(['status'=>'error','message'=>'Error al actualizar: '.$e->getMessage()]);
    }

} else {
    echo json_encode(['status'=>'error','message'=>'Solicitud inválida']);
}
?>