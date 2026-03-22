<?php
/**
 * CAMBIAR ROL DE USUARIO - AJAX
 * Cambia el rol de un usuario en el sistema
 * Parámetros JSON: {id: 123, rol: "admin"|"doctor"|"user"}
 * Retorna: JSON con éxito o error
 * Seguridad: Solo admin puede cambiar roles
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
// VALIDACIÓN: Campos requeridos
// ============================
if(empty($data['id']) || empty($data['rol'])) {
    responderValidacion("ID y rol son requeridos", []);
}

$id_usuario = (int)$data['id'];
$rol_nuevo = $data['rol'];

// ============================
// VALIDACIÓN: Rol válido
// ============================
$roles_validos = ['admin', 'doctor', 'user'];
if(!in_array($rol_nuevo, $roles_validos)) {
    responderValidacion("Rol inválido. Válidos: " . implode(", ", $roles_validos), []);
}

try {
    // ============================
    // VALIDACIÓN: Usuario existe
    // ============================
    $stmt = $pdo->prepare("SELECT id, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$usuario) {
        responderError("El usuario no existe", [], 404);
    }

    $rol_actual = $usuario['rol'];

    // Si el rol es el mismo, no hacer nada
    if($rol_actual === $rol_nuevo) {
        responderExito("El usuario ya tiene este rol", ['id' => $id_usuario, 'rol' => $rol_nuevo]);
    }

    // ============================
    // ACTUALIZAR: Rol del usuario
    // ============================
    $stmt = $pdo->prepare("
        UPDATE usuarios 
        SET rol = ?, fecha_actualizado = NOW()
        WHERE id = ?
    ");

    $stmt->execute([$rol_nuevo, $id_usuario]);

    responderExito(
        "Rol actualizado correctamente de '$rol_actual' a '$rol_nuevo'",
        ['id' => $id_usuario, 'rol_anterior' => $rol_actual, 'rol_nuevo' => $rol_nuevo]
    );

} catch(PDOException $e) {
    responderError("Error al cambiar el rol: " . $e->getMessage(), [], 500);
}
?>