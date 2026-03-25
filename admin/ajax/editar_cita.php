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

try {
    validarTokenPost();
} catch (Exception $e) {
    responderNoAutorizado($e->getMessage());
}

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
    if (!db_fetch("SELECT id FROM citas WHERE id = ?", [$id_cita])) {
        responderError("La cita no existe", [], 404);
    }

    // ============================
    // VALIDACIÓN: Relaciones foráneas
    // ============================
    if (!db_fetch("SELECT id FROM usuarios WHERE id = ?", [$id_usuario])) {
        responderError("Usuario no encontrado", [], 404);
    }
    if (!db_fetch("SELECT id FROM doctores WHERE id = ?", [$id_doctor])) {
        responderError("Doctor no encontrado", [], 404);
    }
    if (!db_fetch("SELECT id FROM especialidades WHERE id = ?", [$id_especialidad])) {
        responderError("Especialidad no encontrada", [], 404);
    }

    // ============================
    // VALIDACIÓN: No haya conflicto de horario con otro doctor
    // ============================
    if (db_fetch("SELECT id FROM citas WHERE id_doctor = ? AND fecha = ? AND hora = ? AND estado != 'cancelada' AND id != ?", [$id_doctor, $fecha, $hora, $id_cita])) {
        responderError("El doctor ya tiene una cita en esa fecha y hora", [], 409);
    }

    // ============================
    // ACTUALIZAR: Cita
    // ============================
    $updated = db_update('citas', [
        'id_usuario' => $id_usuario,
        'id_doctor' => $id_doctor,
        'id_especialidad' => $id_especialidad,
        'fecha' => $fecha,
        'hora' => $hora,
        'fecha_actualizado' => date('Y-m-d H:i:s')
    ], 'id = ?', [$id_cita]);

    if (!$updated) {
        responderError("No se pudo actualizar la cita", [], 500);
    }

    registrarLog('editar_cita', ['id_cita' => $id_cita, 'id_admin' => $_SESSION['id'] ?? null]);
    responderExito("Cita actualizada correctamente", ['id' => $id_cita]);

} catch(PDOException $e) {
    responderError("Error al actualizar la cita: " . $e->getMessage(), [], 500);
}
?>