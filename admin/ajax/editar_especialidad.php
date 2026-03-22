<?php
/**
 * EDITAR ESPECIALIDAD - AJAX
 * Actualiza información de especialidad existente
 * Parámetros POST: id, nombre, descripcion, precio
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede editar especialidades
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

// Obtener datos del formulario
$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$descripcion = $_POST['descripcion'] ?? null;
$precio = $_POST['precio'] ?? null;

// Validar campos
if (!validarCamposRequeridos(['id', 'nombre', 'descripcion', 'precio'], $_POST)) {
    responderValidacion("Todos los campos son requeridos");
}

$id = sanitizarNumero($id);
if (!$id) {
    responderError("ID inválido", [], 400);
}

try {
    $stmt = $pdo->prepare("UPDATE especialidades SET nombre=?, descripcion=?, precio=? WHERE id=?");
    $stmt->execute([
        sanitizarTexto($nombre),
        sanitizarTexto($descripcion),
        floatval($precio),
        $id
    ]);
    
    if ($stmt->rowCount() > 0) {
        responderExito("Especialidad actualizada correctamente");
    } else {
        responderError("No se encontró la especialidad", [], 404);
    }
} catch(PDOException $e) {
    responderError("Error al actualizar: " . $e->getMessage(), [], 500);
}
?>