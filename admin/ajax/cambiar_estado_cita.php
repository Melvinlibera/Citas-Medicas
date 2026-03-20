<?php
/**
 * CAMBIAR ESTADO DE CITA - AJAX
 *
 * Funcionalidad:
 * - Actualiza el estado de una cita médica en el sistema
 * - Operación crítica para gestión de citas por administradores
 * - Cambia entre estados: pendiente → confirmada/cancelada
 * - Utilizado desde panel de administración de citas
 *
 * Estados válidos de cita:
 * - pendiente: Estado inicial al agendar
 * - confirmada: Cita validada por admin/doctor
 * - cancelada: Cita anulada por sistema o usuario
 *
 * Parámetros de entrada (JSON):
 * {
 *   "id": 123,           // ID numérico de la cita
 *   "estado": "confirmada" // Nuevo estado a asignar
 * }
 *
 * Respuesta JSON:
 * {
 *   "success": true,
 *   "message": "Estado actualizado"
 * }
 *
 * Validaciones implementadas:
 * - Recibe datos como JSON desde petición AJAX
 * - Prepared statement para prevenir SQL injection
 * - Actualización directa sin validaciones adicionales
 *
 * Seguridad:
 * - Debería validar sesión de administrador (FALTA IMPLEMENTAR)
 * - Prepared statements para consultas seguras
 * - Control de acceso debería verificarse antes de ejecutar
 *
 * NOTA IMPORTANTE:
 * - Este endpoint actualmente NO valida permisos de usuario
 * - Cualquier persona podría cambiar estados de citas
 * - Se recomienda agregar validación de sesión y rol
 *
 * Uso típico:
 * - Admin ve lista de citas en admin/citas.php
 * - Hace clic en botón "Confirmar" o "Cancelar"
 * - JavaScript envía petición AJAX con id y nuevo estado
 * - Base de datos se actualiza y UI se refresca
 */

session_start();
require("../../config/db.php");
header("Content-Type: application/json");

// Validar sesión - Admin o Doctor
if(!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'doctor'])){
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

// Leer datos JSON de la petición AJAX
$data = json_decode(file_get_contents("php://input"), true);

// Actualizar estado de la cita en base de datos
// IMPORTANTE: Sin validación de permisos (debería agregarse)
$stmt = $pdo->prepare("UPDATE citas SET estado=? WHERE id=?");
$stmt->execute([$data['estado'], $data['id']]);

// Retornar respuesta de éxito
echo json_encode(["success" => true, "message" => "Estado actualizado"]);
?>