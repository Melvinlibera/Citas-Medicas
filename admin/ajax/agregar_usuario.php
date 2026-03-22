<?php
/**
 * AGREGAR USUARIO - AJAX
 * Validaciones de servidor para crear nuevo usuario
 * Requiere: nombre, apellido, cedula, telefono, correo, password, rol, genero, seguro
 * Formatea automáticamente cédula (XXX-XXXXXXX-X) y teléfono (XXX-XXX-XXXX)
 * Valida contraseña mínimo 6 caracteres
 * Valida unicidad de correo y cédula
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
    empty($_POST['nombre']) ||
    empty($_POST['apellido']) ||
    empty($_POST['genero']) ||
    empty($_POST['seguro']) ||
    empty($_POST['cedula']) ||
    empty($_POST['telefono']) ||
    empty($_POST['correo']) ||
    empty($_POST['password']) ||
    empty($_POST['confirm_password']) ||
    empty($_POST['rol'])
) {
    responderValidacion("Todos los campos son obligatorios", []);
}

// ============================
// VALIDACIÓN: Contraseñas coinciden
// ============================
if($_POST['password'] !== $_POST['confirm_password']) {
    responderValidacion("Las contraseñas no coinciden", []);
}

// ============================
// VALIDACIÓN: Formato correo
// ============================
if(!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
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
// VALIDACIÓN: Longitud contraseña
// ============================
if(strlen($_POST['password']) < 6) {
    responderValidacion("La contraseña debe tener mínimo 6 caracteres", []);
}

// ============================
// VALIDACIÓN: Rol válido
// ============================
$roles_validos = ['admin', 'doctor', 'user'];
if(!in_array($_POST['rol'], $roles_validos)) {
    responderValidacion("Rol inválido", []);
}

try {
    // ============================
    // VALIDACIÓN: Unicidad correo y cédula
    // ============================
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ? OR cedula = ?");
    $stmt->execute([$_POST['correo'], $cedula_limpia]);
    if($stmt->fetch()) {
        responderError("El correo o cédula ya están registrados en el sistema", [], 409);
    }

    // ============================
    // FORMATEO AUTOMÁTICO
    // ============================
    $cedula_formateada = substr($cedula_limpia, 0, 3) . '-' . substr($cedula_limpia, 3, 7) . '-' . substr($cedula_limpia, 10, 1);
    $telefono_formateado = substr($telefono_limpio, 0, 3) . '-' . substr($telefono_limpio, 3, 3) . '-' . substr($telefono_limpio, 6, 4);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $genero = $_POST['genero'];
    $seguro = trim($_POST['seguro']);

    // ============================
    // INSERTAR: Nuevo usuario
    // ============================
    $stmt = $pdo->prepare("
        INSERT INTO usuarios 
        (nombre, apellido, cedula, telefono, correo, password, seguro, genero, rol, fecha_registro) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $nombre,
        $apellido,
        $cedula_formateada,
        $telefono_formateado,
        $_POST['correo'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $seguro,
        $genero,
        $_POST['rol']
    ]);

    $id_usuario = $pdo->lastInsertId();
    responderExito("Usuario agregado correctamente", ['id' => $id_usuario]);

} catch(PDOException $e) {
    responderError("Error al guardar el usuario: " . $e->getMessage(), [], 500);
}
?>