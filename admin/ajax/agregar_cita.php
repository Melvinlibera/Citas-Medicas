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
include("../../config/respuestas.php");
include("../../config/sesiones.php");

// ============================
// VALIDACIÓN: Autorización
// ============================
if(!verificarSesionAdmin()) {
    responderError("No autorizado - Requiere permisos de admin", [], 403);
}

// ============================
// VALIDACIÓN: Campos obligatorios
// ============================
if(empty($_POST['id_usuario']) || empty($_POST['id_doctor']) || 
   empty($_POST['id_especialidad']) || empty($_POST['fecha']) || empty($_POST['hora'])) {
    responderValidacion("Todos los campos son obligatorios", []);
}

// ============================
// VALIDACIÓN: Formato fecha y hora
// ============================
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha'])) {
    responderValidacion("Formato de fecha inválido (YYYY-MM-DD)", []);
}

if(!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $_POST['hora'])) {
    responderValidacion("Formato de hora inválido (HH:MM o HH:MM:SS)", []);
}

try {
    // Validar que el usuario existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ?");
    $stmt->execute([$_POST['id_usuario']]);
    if (!$stmt->fetch()) {
        responderError("Usuario no encontrado", [], 404);
    }
    
    // Validar que el doctor existe
    $stmt = $pdo->prepare("SELECT id FROM doctores WHERE id = ?");
    $stmt->execute([$_POST['id_doctor']]);
    if (!$stmt->fetch()) {
        responderError("Doctor no encontrado", [], 404);
    }
    
    // Validar que la especialidad existe
    $stmt = $pdo->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmt->execute([$_POST['id_especialidad']]);
    if (!$stmt->fetch()) {
        responderError("Especialidad no encontrada", [], 404);
    }
    
    // Validar que no exista cita en el mismo horario
    $stmt = $pdo->prepare("
        SELECT id FROM citas 
        WHERE id_doctor = ? AND fecha = ? AND hora = ? AND estado != 'cancelada'
    ");
    $stmt->execute([$_POST['id_doctor'], $_POST['fecha'], $_POST['hora']]);
    if ($stmt->fetch()) {
        responderError("El doctor ya tiene una cita en esa fecha y hora", [], 409);
    }

    // Insertar cita
    $id_usuario = sanitizarNumero($_POST['id_usuario']);
    $id_doctor = sanitizarNumero($_POST['id_doctor']);
    $id_especialidad = sanitizarNumero($_POST['id_especialidad']);
    $fecha = sanitizarTexto($_POST['fecha']);
    $hora = sanitizarTexto($_POST['hora']);

    $stmt = $pdo->prepare("
        INSERT INTO citas 
        (id_usuario, id_doctor, id_especialidad, fecha, hora, estado, fecha_creacion) 
        VALUES (?, ?, ?, ?, ?, 'pendiente', NOW())
    ");

    $stmt->execute([$id_usuario, $id_doctor, $id_especialidad, $fecha, $hora]);

    $id_cita = $pdo->lastInsertId();
    responderExito("Cita agregada correctamente", ['id_cita' => $id_cita]);

} catch(PDOException $e){
    responderError("Error al guardar la cita: " . $e->getMessage(), [], 500);
}
?>