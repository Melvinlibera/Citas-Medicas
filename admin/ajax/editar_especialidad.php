<?php
session_start();
header('Content-Type: application/json');

include("../../config/db.php");

// Validar sesión
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

// Validar admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    echo json_encode(['status'=>'error','message'=>'Acceso denegado']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? 0;

    if(!$id || !$nombre || !$precio){
        echo json_encode(['status'=>'error','message'=>'Datos incompletos']);
        exit;
    }

    try{
        $stmt = $pdo->prepare("UPDATE especialidades SET nombre=?, descripcion=?, precio=? WHERE id=?");
        $stmt->execute([$nombre, $descripcion, $precio, $id]);

        echo json_encode(['status'=>'success','message'=>'Especialidad actualizada']);
    }catch(PDOException $e){
        echo json_encode(['status'=>'error','message'=>'Error: '.$e->getMessage()]);
    }
}