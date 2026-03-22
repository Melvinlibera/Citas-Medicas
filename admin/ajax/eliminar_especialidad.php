<?php
/**
 * ELIMINAR ESPECIALIDAD - AJAX
 * Elimina una especialidad médica
 * Parámetros JSON: {id: 123}
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede eliminar especialidades
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

// Obtener datos (JSON)
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

// Validar ID
if (!$id) {
    responderError("ID requerido", [], 400);
}

$id = sanitizarNumero($id);
if (!$id) {
    responderError("ID inválido", [], 400);
}

try {
    // Verificar que exista
    $stmt = $pdo->prepare("SELECT id FROM especialidades WHERE id=?");
    $stmt->execute([$id]);
    
    if (!$stmt->fetch()) {
        responderError("Especialidad no encontrada", [], 404);
    }
    
    // Eliminar
    $stmt = $pdo->prepare("DELETE FROM especialidades WHERE id=?");
    $stmt->execute([$id]);
    
    responderExito("Especialidad eliminada correctamente");
} catch(PDOException $e) {
    responderError("Error al eliminar: " . $e->getMessage(), [], 500);
}
?>