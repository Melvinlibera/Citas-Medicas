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

try {

    if(empty($_POST['id'])){
        throw new Exception("ID requerido");
    }

    $id = $_POST['id'];

    $pdo->beginTransaction();

    // 1. OBTENER ID_USUARIO
    $stmt = $pdo->prepare("SELECT id_usuario FROM doctores WHERE id = ?");
    $stmt->execute([$id]);
    $doctor = $stmt->fetch();

    if(!$doctor){
        throw new Exception("Doctor no encontrado");
    }

    $id_usuario = $doctor['id_usuario'];

    // 2. ELIMINAR DOCTOR
    $stmt = $pdo->prepare("DELETE FROM doctores WHERE id = ?");
    $stmt->execute([$id]);

    // 3. ELIMINAR USUARIO
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Doctor eliminado correctamente"
    ]);

} catch(Exception $e){

    if($pdo->inTransaction()){
        $pdo->rollBack();
    }

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>