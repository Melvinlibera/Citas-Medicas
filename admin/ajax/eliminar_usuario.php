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

// ============================
// VALIDACIÓN: Autorización
// ============================
if(!verificarSesionAdmin()) {
    responderError("No autorizado - Requiere permisos de admin", [], 403);
}

// ============================
// LEER DATOS JSON
// ============================
$data = json_decode(file_get_contents("php://input"), true);

// ============================
// VALIDACIÓN: ID requerido
// ============================
if(empty($data['id'])) {
    responderValidacion("ID del usuario es requerido", []);
}

$id = (int)$data['id'];

try {
    // ============================
    // VALIDACIÓN: Usuario existe
    // ============================
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    if(!$stmt->fetch()) {
        responderError("El usuario no existe", [], 404);
    }

    // ============================
    // VERIFICAR: Si el usuario es un doctor, eliminar también su registro de doctor
    // ============================
    $stmt = $pdo->prepare("SELECT id FROM doctores WHERE id_usuario = ?");
    $stmt->execute([$id]);
    $es_doctor = $stmt->fetch();

    if($es_doctor) {
        // Es un doctor, usar transacción para integridad
        $pdo->beginTransaction();

        // Eliminar citas de este doctor
        $stmt = $pdo->prepare("DELETE FROM citas WHERE id_doctor IN (SELECT id FROM doctores WHERE id_usuario = ?)");
        $stmt->execute([$id]);

        // Eliminar registro de doctor
        $stmt = $pdo->prepare("DELETE FROM doctores WHERE id_usuario = ?");
        $stmt->execute([$id]);

        // Eliminar usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        $pdo->commit();
        responderExito("Doctor y su usuario eliminados correctamente", ['id' => $id]);
    } else {
        // ============================
        // ELIMINAR: Usuario regular
        // ============================
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        responderExito("Usuario eliminado correctamente", ['id' => $id]);
    }

} catch(PDOException $e) {
    if($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    responderError("Error al eliminar el usuario: " . $e->getMessage(), [], 500);
}
?>