<?php
/**
 * ELIMINAR CITA - AJAX
 * Elimina una cita médica del sistema
 * Parámetros JSON: {id: 123}
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede eliminar citas
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
    responderValidacion("ID de la cita es requerido", []);
}

$id_cita = (int)$data['id'];

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
    // ELIMINAR: Cita
    // ============================
    $stmt = $pdo->prepare("DELETE FROM citas WHERE id = ?");
    $stmt->execute([$id_cita]);

    responderExito("Cita eliminada correctamente", ['id' => $id_cita]);

} catch(PDOException $e) {
    responderError("Error al eliminar la cita: " . $e->getMessage(), [], 500);
}
?>