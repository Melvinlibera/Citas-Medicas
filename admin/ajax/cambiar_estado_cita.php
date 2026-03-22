<?php
/**
 * CAMBIAR ESTADO DE CITA - AJAX
 * 
 * Funcionalidad:
 * - Actualiza el estado de una cita médica
 * - Valida permisos de admin/doctor
 * - Cambia entre estados: pendiente → confirmada/cancelada/completada
 * - Doctores solo pueden cambiar sus propias citas
 * 
 * Parámetros JSON:
 * {
 *   "id": 123,
 *   "estado": "confirmada"
 * }
 * 
 * Respuesta JSON:
 * {
 *   "success": true,
 *   "message": "Estado actualizado a: confirmada",
 *   "data": {
 *     "id": 123,
 *     "estado": "confirmada"
 *   }
 * }
 */
session_start();
include("../../config/db.php");
include("../../config/respuestas.php");
include("../../config/sesiones.php");

// ============================
// VALIDACIÓN: Autenticación
// ============================
if(!estáAutenticado()) {
    responderNoAutenticado("Debe iniciar sesión");
}

// ============================
// VALIDACIÓN: Autorización (admin o doctor)
// ============================
if(!in_array(obtenerRolUsuario(), ['admin', 'doctor'])) {
    responderNoAutorizado("Solo administradores y doctores pueden cambiar estados de citas");
}

try {
    // ============================
    // LEER DATOS JSON
    // ============================
    $data = json_decode(file_get_contents("php://input"), true);
    
    // ============================
    // VALIDACIÓN: Campos requeridos
    // ============================
    if(empty($data['id']) || empty($data['estado'])) {
        responderValidacion("ID y estado son requeridos", []);
    }
    
    // ============================
    // SANITIZAR Y VALIDAR ID
    // ============================
    $id_cita = sanitizarNumero($data['id']);
    if(!$id_cita) {
        responderError("ID de cita inválido", [], 400);
    }
    
    // ============================
    // VALIDACIÓN: Estado válido
    // ============================
    $estados_validos = ['pendiente', 'confirmada', 'cancelada', 'completada'];
    $estado_nuevo = sanitizarTexto($data['estado']);
    
    if(!in_array($estado_nuevo, $estados_validos)) {
        responderError(
            "Estado inválido. Estados válidos: " . implode(", ", $estados_validos),
            [],
            400
        );
    }

    // ============================
    // VALIDACIÓN: Cita existe
    // ============================
    $stmt = $pdo->prepare("SELECT id_doctor FROM citas WHERE id = ?");
    $stmt->execute([$id_cita]);
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$cita) {
        responderNoEncontrado("La cita no existe");
    }

    // ============================
    // VALIDACIÓN: Permiso para cambiar
    // ============================
    // Si es doctor, verificar que sea su propia cita
    if(obtenerRolUsuario() === 'doctor') {
        $stmt = $pdo->prepare("
            SELECT d.id_usuario 
            FROM doctores d 
            WHERE d.id = ?
        ");
        $stmt->execute([$cita['id_doctor']]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$doctor || $doctor['id_usuario'] != obtenerIdUsuario()) {
            responderNoAutorizado("No puedes cambiar el estado de una cita que no es tuya");
        }
    }

    // ============================
    // ACTUALIZAR: Estado de cita
    // ============================
    $stmt = $pdo->prepare("
        UPDATE citas 
        SET estado = ?, fecha_actualizado = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$estado_nuevo, $id_cita]);

    if($stmt->rowCount() === 0) {
        responderError("No se pudo actualizar la cita", [], 500);
    }

    responderExito(
        "Estado actualizado correctamente a: $estado_nuevo",
        [
            'id' => $id_cita,
            'estado' => $estado_nuevo
        ]
    );
    
} catch(PDOException $e) {
    responderError("Error al actualizar el estado: " . $e->getMessage(), [], 500);
}
?>