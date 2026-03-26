<?php
/**
 * ELIMINAR DOCTOR - AJAX
 * Elimina un doctor y su usuario asociado del sistema
 * Parámetros POST: id (id del doctor)
 * Retorna: JSON con éxito o error
 * Nota: Usa transacción para eliminar doctor y su usuario de forma atómica
 * Seguridad: Solo admin puede eliminar doctores
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
// VALIDACIÓN: ID requerido
// ============================
if(empty($_POST['id'])) {
    responderValidacion("ID del doctor es requerido", []);
}

$id_doctor = (int)$_POST['id'];

try {
    // ============================
    // VALIDACIÓN: Doctor existe
    // ============================
    $doctor = db_fetch("SELECT id_usuario FROM doctores WHERE id = ?", [$id_doctor]);

    if(!$doctor) {
        responderError("El doctor no existe", [], 404);
    }

    $id_usuario = $doctor['id_usuario'];

    // ============================
    // INICIAR TRANSACCIÓN
    // ============================
    db()->beginTransaction();

    // ============================
    // PASO 1: Eliminar o marcar citas como canceladas
    // ============================
    // Opción: Marcar como canceladas en lugar de eliminar (conserva historial)
    db_update('citas', ['estado' => 'cancelada', 'fecha_actualizado' => date('Y-m-d H:i:s')], 'id_doctor = ?', [$id_doctor]);

    // ============================
    // PASO 2: Eliminar registro de doctor
    // ============================
    db_delete('doctores', 'id = ?', [$id_doctor]);

    // ============================
    // PASO 3: Eliminar usuario
    // ============================
    db_delete('usuarios', 'id = ?', [$id_usuario]);

    // ============================
    // CONFIRMAR TRANSACCIÓN
    // ============================
    db()->commit();

    registrarLog('eliminar_doctor', ['id_doctor' => $id_doctor, 'id_usuario' => $id_usuario, 'id_admin' => $_SESSION['id'] ?? null]);
    responderExito(
        "Doctor eliminado correctamente (sus citas fueron marcadas como canceladas)",
        ['id' => $id_doctor, 'id_usuario' => $id_usuario]
    );

} catch(PDOException $e) {
    if(db()->inTransaction()) {
        db()->rollBack();
    }
    responderError("Error al eliminar el doctor: " . $e->getMessage(), [], 500);
}
?>