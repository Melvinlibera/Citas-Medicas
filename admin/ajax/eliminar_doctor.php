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
    $stmt = $pdo->prepare("SELECT id_usuario FROM doctores WHERE id = ?");
    $stmt->execute([$id_doctor]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$doctor) {
        responderError("El doctor no existe", [], 404);
    }

    $id_usuario = $doctor['id_usuario'];

    // ============================
    // INICIAR TRANSACCIÓN
    // ============================
    $pdo->beginTransaction();

    // ============================
    // PASO 1: Eliminar o marcar citas como canceladas
    // ============================
    // Opción: Marcar como canceladas en lugar de eliminar (conserva historial)
    $stmt = $pdo->prepare("
        UPDATE citas 
        SET estado = 'cancelada', fecha_actualizado = NOW()
        WHERE id_doctor = ?
    ");
    $stmt->execute([$id_doctor]);

    // ============================
    // PASO 2: Eliminar registro de doctor
    // ============================
    $stmt = $pdo->prepare("DELETE FROM doctores WHERE id = ?");
    $stmt->execute([$id_doctor]);

    // ============================
    // PASO 3: Eliminar usuario
    // ============================
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);

    // ============================
    // CONFIRMAR TRANSACCIÓN
    // ============================
    $pdo->commit();

    responderExito(
        "Doctor eliminado correctamente (sus citas fueron marcadas como canceladas)",
        ['id' => $id_doctor, 'id_usuario' => $id_usuario]
    );

} catch(PDOException $e) {
    if($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    responderError("Error al eliminar el doctor: " . $e->getMessage(), [], 500);
}
?>