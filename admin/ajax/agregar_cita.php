<?php
/**
 * AGREGAR CITA - AJAX
 * Validaciones de servidor para crear nueva cita
 * Requiere: id_usuario, id_doctor, id_especialidad, fecha, hora
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede crear citas
 */
session_start();
header('Content-Type: application/json');
include("../../config/db.php");

// Validar autorización
if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

// ============================
// VALIDACIÓN: Autorización
// ============================
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin'){
    echo json_encode(['success'=>false,'message'=>'No autorizado - Requiere permisos de admin']);
    exit;
}

// ============================
// VALIDACIÓN: Campos obligatorios
// ============================
if(empty($_POST['id_usuario']) || empty($_POST['id_doctor']) || 
   empty($_POST['id_especialidad']) || empty($_POST['fecha']) || empty($_POST['hora'])){
    echo json_encode(['success'=>false,'message'=>'Todos los campos son obligatorios']);
    exit;
}

// ============================
// VALIDACIÓN: Formato fecha y hora
// ============================
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha'])){
    echo json_encode(['success'=>false,'message'=>'Formato de fecha inválido']);
    exit;
}

if(!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $_POST['hora'])){
    echo json_encode(['success'=>false,'message'=>'Formato de hora inválido']);
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