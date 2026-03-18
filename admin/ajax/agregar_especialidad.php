<?php
session_start();
include("../config/db.php");

// Verificar sesión y rol admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    echo json_encode(['status'=>'error','message'=>'Acceso denegado']);
    exit();
}

// Solo continuar si hay POST
if($_POST){
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = trim($_POST['precio'] ?? 0);

    if(!$nombre || !$precio){
        echo json_encode(['status'=>'error','message'=>'Nombre y precio son obligatorios']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO especialidades (nombre, descripcion, precio) VALUES (?,?,?)");
        $stmt->execute([$nombre, $descripcion, $precio]);
        echo json_encode(['status'=>'success','message'=>'Especialidad agregada correctamente']);
    } catch(PDOException $e){
        echo json_encode(['status'=>'error','message'=>'Error al agregar especialidad: '.$e->getMessage()]);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Solicitud inválida']);
}
?>