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
    $input = json_decode(file_get_contents("php://input"), true);
    
    // Validar que se proporcione especialidad
    if(empty($input['id_especialidad'])) {
        responderValidacion("ID de especialidad es requerido", []);
    }

    $id_especialidad = (int)$input['id_especialidad'];

    // ============================
    // VALIDAR: Especialidad existe
    // ============================
    $stmt = $pdo->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmt->execute([$id_especialidad]);
    if(!$stmt->fetch()) {
        responderError("La especialidad no existe", [], 404);
    }

    // ============================
    // OBTENER: Doctores de la especialidad
    // ============================
    $stmt = $pdo->prepare("
        SELECT id, nombre 
        FROM doctores 
        WHERE id_especialidad = ?
        ORDER BY nombre ASC
    ");
    $stmt->execute([$id_especialidad]);
    $doctores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $doctores ?: []
    ]);

} catch(PDOException $e) {
    responderError("Error al obtener doctores: " . $e->getMessage(), [], 500);
}
?>