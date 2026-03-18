<?php
session_start();
include("../config/db.php");

// Verificar sesión y rol admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    echo json_encode(['status'=>'error','message'=>'Acceso denegado']);
    exit();
}

// Solo aceptar POST
if($_POST){
    $id = intval($_POST['id'] ?? 0);

    if(!$id){
        echo json_encode(['status'=>'error','message'=>'ID de especialidad inválido']);
        exit();
    }

    try {
        // Eliminar la especialidad
        $stmt = $pdo->prepare("DELETE FROM especialidades WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status'=>'success','message'=>'Especialidad eliminada correctamente']);
    } catch(PDOException $e){
        // Esto puede ocurrir si hay citas o doctores asociados debido a FK
        echo json_encode([
            'status'=>'error',
            'message'=>'No se pudo eliminar. Puede haber doctores o citas asociadas a esta especialidad.'
        ]);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Solicitud inválida']);
}