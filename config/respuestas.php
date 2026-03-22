<?php
/**
 * MANEJADOR DE RESPUESTAS JSON CENTRALIZADO
 * Hospital & Human - Citas Médicas
 * 
 * Este archivo proporciona funciones comunes para responder en formato JSON
 * desde todas las solicitudes AJAX del sistema.
 * 
 * Uso en archivos AJAX:
 * include("../../config/respuestas.php");
 * responderExito("Acción completada");
 * responderError("Algo salió mal");
 */

// Establecer encabezado JSON
header('Content-Type: application/json; charset=UTF-8');

// ========================================================
// FUNCIÓN: Responder con éxito
// ========================================================
function responderExito($mensaje, $datos = [], $codigo = 200) {
    http_response_code($codigo);
    echo json_encode([
        'success' => true,
        'message' => $mensaje,
        'data' => $datos
    ]);
    exit();
}

// ========================================================
// FUNCIÓN: Responder con error
// ========================================================
function responderError($mensaje, $datos = [], $codigo = 400) {
    http_response_code($codigo);
    echo json_encode([
        'success' => false,
        'message' => $mensaje,
        'data' => $datos
    ]);
    exit();
}

// ========================================================
// FUNCIÓN: Responder error de validación
// ========================================================
function responderValidacion($mensaje, $campos = []) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => $mensaje,
        'errors' => $campos
    ]);
    exit();
}

// ========================================================
// FUNCIÓN: Responder no autorizado
// ========================================================
function responderNoAutorizado($mensaje = 'Acceso denegado') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => $mensaje
    ]);
    exit();
}

// ========================================================
// FUNCIÓN: Responder no autenticado
// ========================================================
function responderNoAutenticado($mensaje = 'Debe iniciar sesión') {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => $mensaje
    ]);
    exit();
}

// ========================================================
// FUNCIÓN: Responder no encontrado
// ========================================================
function responderNoEncontrado($mensaje = 'Recurso no encontrado') {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => $mensaje
    ]);
    exit();
}

// ========================================================
// FUNCIÓN: Responder error del servidor
// ========================================================
function responderError500($mensaje = 'Error del servidor') {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $mensaje
    ]);
    exit();
}

// ========================================================
// FUNCIONES DE VALIDACIÓN COMUNES
// ========================================================

/**
 * Validar que todos los campos requeridos existan
 * 
 * @param array $requeridos Lista de nombres de campos
 * @param array $datos Data a validar (por defecto $_POST)
 * @return bool
 */
function validarCamposRequeridos($requeridos, $datos = null) {
    if ($datos === null) {
        $datos = $_POST;
    }
    
    foreach ($requeridos as $campo) {
        if (empty($datos[$campo])) {
            return false;
        }
    }
    return true;
}

/**
 * Validar formato de email
 * 
 * @param string $email
 * @return bool
 */
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validar formato de fecha (YYYY-MM-DD)
 * 
 * @param string $fecha
 * @return bool
 */
function validarFecha($fecha) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha);
}

/**
 * Validar formato de hora (HH:MM o HH:MM:SS)
 * 
 * @param string $hora
 * @return bool
 */
function validarHora($hora) {
    return preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $hora);
}

/**
 * Sanitizar entrada de texto
 * 
 * @param string $texto
 * @return string
 */
function sanitizarTexto($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitizar entrada de email
 * 
 * @param string $email
 * @return string
 */
function sanitizarEmail($email) {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

/**
 * Sanitizar entrada de número
 * 
 * @param mixed $numero
 * @return int|float|null
 */
function sanitizarNumero($numero) {
    if (is_numeric($numero)) {
        return (int)$numero;
    }
    return null;
}

// ========================================================
// FUNCIONES DE LOGGING (Opcional)
// ========================================================

/**
 * Registrar acción en log
 * 
 * @param string $accion
 * @param array $datos
 * @return void
 */
function registrarLog($accion, $datos = []) {
    $timestamp = date('Y-m-d H:i:s');
    $usuario_id = $_SESSION['id'] ?? 'N/A';
    $mensaje = "[{$timestamp}] Usuario: {$usuario_id} | Acción: {$accion}";
    
    // Aquí se podría guardar en base de datos o archivo
    // Por ahora es un placeholder para implementación futura
}

?>
