<?php
/**
 * ELIMINAR USUARIO - AJAX
 * Elimina un usuario del sistema
 * Parámetros JSON: {id: 123}
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede eliminar usuarios
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
// VALIDACIÓN: ID requerido (POST)
// ============================
if(empty($_POST['id'])) {
    responderValidacion("ID del usuario es requerido", []);
}

$id = (int)$_POST['id'];

try {
    // ============================
    // VALIDACIÓN: Usuario existe
    // ============================
    if(!db_fetch("SELECT id FROM usuarios WHERE id = ?", [$id])) {
        responderError("El usuario no existe", [], 404);
    }

    // ============================
    // VERIFICAR: Si el usuario es un doctor, eliminar también su registro de doctor
    // ============================
    $es_doctor = db_fetch("SELECT id FROM doctores WHERE id_usuario = ?", [$id]);

    if($es_doctor) {
        // Es un doctor, usar transacción para integridad
        db()->beginTransaction();

        // Eliminar citas de este doctor
        db_execute("DELETE FROM citas WHERE id_doctor IN (SELECT id FROM doctores WHERE id_usuario = ?)", [$id]);

        // Eliminar registro de doctor
        db_delete('doctores', 'id_usuario = ?', [$id]);

        // Eliminar usuario
        db_delete('usuarios', 'id = ?', [$id]);

        db()->commit();
        registrarLog('eliminar_usuario', ['id' => $id, 'id_admin' => $_SESSION['id'] ?? null]);
        responderExito("Doctor y su usuario eliminados correctamente", ['id' => $id]);
    } else {
        // ============================
        // ELIMINAR: Usuario regular
        // ============================
        db_delete('usuarios', 'id = ?', [$id]);
        registrarLog('eliminar_usuario', ['id' => $id, 'id_admin' => $_SESSION['id'] ?? null]);
        responderExito("Usuario eliminado correctamente", ['id' => $id]);
    }

} catch(PDOException $e) {
    if(db()->inTransaction()) {
        db()->rollBack();
    }
    responderError("Error al eliminar el usuario: " . $e->getMessage(), [], 500);
}
?>