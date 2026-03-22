<?php
/**
 * EDITAR USUARIO - AJAX
 * Actualiza información de usuario existente
 * Parámetros: id, nombre, apellido, genero, seguro, cedula, telefono, correo, rol
 * Opcionales: password, confirm_password (para cambiar contraseña)
 * Retorna: JSON con éxito o error
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
// VALIDACIÓN: Campos obligatorios
// ============================
if(
    empty($_POST['id']) ||
    empty($_POST['nombre']) ||
    empty($_POST['apellido']) ||
    empty($_POST['genero']) ||
    empty($_POST['seguro']) ||
    empty($_POST['cedula']) ||
    empty($_POST['telefono']) ||
    empty($_POST['correo']) ||
    empty($_POST['rol'])
) {
    responderValidacion("Todos los campos son obligatorios", []);
}

$id = (int)$_POST['id'];
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$genero = $_POST['genero'];
$seguro = trim($_POST['seguro']);
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$password = $_POST['password'] ?? null;
$confirm_password = $_POST['confirm_password'] ?? null;

// ============================
// VALIDACIÓN: Contraseñas coinciden (si se proporcionan)
// ============================
if($password && $password !== $confirm_password) {
    responderValidacion("Las contraseñas no coinciden", []);
}

// ============================
// VALIDACIÓN: Formato correo
// ============================
if(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    responderValidacion("Formato de correo inválido", []);
}

// ============================
// VALIDACIÓN: Formato cédula (10 dígitos)
// ============================
$cedula_limpia = preg_replace('/[.\-\s()]/i', '', $_POST['cedula']);
if(!preg_match('/^\d{10}$/', $cedula_limpia)) {
    responderValidacion("Cédula inválida (debe tener 10 dígitos)", []);
}

// ============================
// VALIDACIÓN: Formato teléfono (10 dígitos)
// ============================
$telefono_limpio = preg_replace('/[.\-\s()]/i', '', $_POST['telefono']);
if(!preg_match('/^\d{10}$/', $telefono_limpio)) {
    responderValidacion("Teléfono inválido (debe tener 10 dígitos)", []);
}

// ============================
// VALIDACIÓN: Género válido
// ============================
if(!in_array($genero, ['masculino', 'femenino'])) {
    responderValidacion("Género inválido", []);
}

// ============================
// VALIDACIÓN: Rol válido
// ============================
if(!in_array($rol, ['admin', 'doctor', 'user'])) {
    responderValidacion("Rol inválido", []);
}

// ============================
// VALIDACIÓN: Longitud contraseña (si se proporciona)
// ============================
if($password && strlen($password) < 6) {
    responderValidacion("La contraseña debe tener mínimo 6 caracteres", []);
}

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
    // VALIDACIÓN: Unicidad correo y cédula (excepto el usuario actual)
    // ============================
    $stmt = $pdo->prepare("
        SELECT id FROM usuarios 
        WHERE (correo = ? OR cedula = ?) AND id != ?
    ");
    $stmt->execute([$correo, $cedula_limpia, $id]);
    if($stmt->fetch()) {
        responderError("El correo o cédula ya están en uso por otro usuario", [], 409);
    }

    // ============================
    // FORMATEO AUTOMÁTICO
    // ============================
    $cedula_formateada = substr($cedula_limpia, 0, 3) . '-' . substr($cedula_limpia, 3, 7) . '-' . substr($cedula_limpia, 10, 1);
    $telefono_formateado = substr($telefono_limpio, 0, 3) . '-' . substr($telefono_limpio, 3, 3) . '-' . substr($telefono_limpio, 6, 4);

    // ============================
    // ACTUALIZAR: Usuario con o sin password
    // ============================
    if(!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre = ?, apellido = ?, genero = ?, seguro = ?, cedula = ?, 
                telefono = ?, correo = ?, password = ?, rol = ?, fecha_actualizado = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $nombre, $apellido, $genero, $seguro, $cedula_formateada,
            $telefono_formateado, $correo, $passwordHash, $rol, $id
        ]);

        responderExito("Usuario actualizado correctamente (con cambio de contraseña)", ['id' => $id]);

    } else {
        // Sin actualizar password
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre = ?, apellido = ?, genero = ?, seguro = ?, cedula = ?, 
                telefono = ?, correo = ?, rol = ?, fecha_actualizado = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $nombre, $apellido, $genero, $seguro, $cedula_formateada,
            $telefono_formateado, $correo, $rol, $id
        ]);

        responderExito("Usuario actualizado correctamente", ['id' => $id]);
    }

} catch(PDOException $e) {
    responderError("Error al actualizar el usuario: " . $e->getMessage(), [], 500);
}
?>