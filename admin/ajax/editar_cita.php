<?php
/**
 * EDITAR CITA - AJAX
 * Actualiza información de cita existente
 * Parámetros: id, id_usuario, id_doctor, id_especialidad, fecha, hora
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede editar citas
 */
session_start();
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
if(
    empty($_POST['id']) ||
    empty($_POST['id_usuario']) ||
    empty($_POST['id_doctor']) ||
    empty($_POST['id_especialidad']) ||
    empty($_POST['fecha']) ||
    empty($_POST['hora'])
) {
    responderValidacion("Todos los campos son obligatorios", []);
}

$id_cita = (int)$_POST['id'];
$id_usuario = (int)$_POST['id_usuario'];
$id_doctor = (int)$_POST['id_doctor'];
$id_especialidad = (int)$_POST['id_especialidad'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

// ============================
// VALIDACIÓN: Formato fecha y hora
// ============================
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    responderValidacion("Formato de fecha inválido (YYYY-MM-DD)", []);
}

if(!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $hora)) {
    responderValidacion("Formato de hora inválido (HH:MM o HH:MM:SS)", []);
}

try {
    // ============================
    // VALIDACIÓN: Cita existe
    // ============================
    $stmt = $pdo->prepare("SELECT id FROM citas WHERE id = ?");
    $stmt->execute([$id_cita]);
    if(!$stmt->fetch()) {
        responderError("La cita no existe", [], 404);
    }

    // ============================
    // VALIDACIÓN: Relaciones foráneas
    // ============================
    // Validar usuario existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);
    if(!$stmt->fetch()) {
        responderError("Usuario no encontrado", [], 404);
    }

    // Validar doctor existe
    $stmt = $pdo->prepare("SELECT id FROM doctores WHERE id = ?");
    $stmt->execute([$id_doctor]);
    if(!$stmt->fetch()) {
        responderError("Doctor no encontrado", [], 404);
    }

    // Validar especialidad existe
    $stmt = $pdo->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmt->execute([$id_especialidad]);
    if(!$stmt->fetch()) {
        responderError("Especialidad no encontrada", [], 404);
    }

    // ============================
    // VALIDACIÓN: No haya conflicto de horario con otro doctor
    // ============================
    $stmt = $pdo->prepare("
        SELECT id FROM citas 
        WHERE id_doctor = ? AND fecha = ? AND hora = ? 
        AND estado != 'cancelada' AND id != ?
    ");
    $stmt->execute([$id_doctor, $fecha, $hora, $id_cita]);
    if($stmt->fetch()) {
        responderError("El doctor ya tiene una cita en esa fecha y hora", [], 409);
    }

    // ============================
    // ACTUALIZAR: Cita
    // ============================
    $stmt = $pdo->prepare("
        UPDATE citas 
        SET id_usuario = ?, id_doctor = ?, id_especialidad = ?, fecha = ?, hora = ?, fecha_actualizado = NOW()
        WHERE id = ?
    ");

    $stmt->execute([$id_usuario, $id_doctor, $id_especialidad, $fecha, $hora, $id_cita]);

    responderExito("Cita actualizada correctamente", ['id' => $id_cita]);

} catch(PDOException $e) {
    responderError("Error al actualizar la cita: " . $e->getMessage(), [], 500);
}
?>