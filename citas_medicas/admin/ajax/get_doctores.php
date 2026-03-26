<?php
/**
 * GET DOCTORES - AJAX
 * Obtiene lista de doctores por especialidad
 * Parámetros JSON: {id_especialidad: 123}
 * Retorna: JSON con array de doctores [{id, nombre}, ...]
 */
session_start();
include("../../config/db.php");
include("../../config/respuestas.php");

try {
    // Leer datos JSON
    // Datos vienen en POST
    
    // Validar que se proporcione especialidad
    if(empty($_POST['id_especialidad'])) {
        responderValidacion("ID de especialidad es requerido", []);
    }

    $id_especialidad = (int)$_POST['id_especialidad'];

    // ============================
    // VALIDAR: Especialidad existe
    // ============================
    if(!db_fetch("SELECT id FROM especialidades WHERE id = ?", [$id_especialidad])) {
        responderError("La especialidad no existe", [], 404);
    }

    // ============================
    // OBTENER: Doctores de la especialidad
    // ============================
    $doctores = db_fetch_all(
        "SELECT id, nombre FROM doctores WHERE id_especialidad = ? ORDER BY nombre ASC",
        [$id_especialidad]
    );

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $doctores ?: []
    ]);

} catch(PDOException $e) {
    responderError("Error al obtener doctores: " . $e->getMessage(), [], 500);
}
?>