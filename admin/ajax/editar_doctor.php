<?php
/**
 * EDITAR DOCTOR - AJAX
 * Actualiza información de doctor y su usuario asociado
 * Parámetros: id (id del doctor), nombre, apellido, cedula, telefono, correo, genero, rol, id_especialidad
 * Opcionales: password, confirm_password (para cambiar contraseña)
 * Retorna: JSON con éxito o error
 * Nota: Requiere transacción para actualizar usuario y doctor
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
// VALIDACIÓN: Método POST
// ============================
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderError("Método no permitido (requerido POST)", [], 405);
}

// ============================
// VALIDACIÓN: Campos obligatorios
// ============================
if(
    empty($_POST['id']) ||
    empty($_POST['nombre']) ||
    empty($_POST['apellido']) ||
    empty($_POST['cedula']) ||
    empty($_POST['telefono']) ||
    empty($_POST['correo']) ||
    empty($_POST['genero']) ||
    empty($_POST['id_especialidad'])
) {
    responderValidacion("Todos los campos requeridos están vacíos", []);
}

$id_doctor = (int)$_POST['id'];
$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$correo = $_POST['correo'];
$genero = $_POST['genero'];
$id_especialidad = (int)$_POST['id_especialidad'];
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
// VALIDACIÓN: Longitud contraseña (si se proporciona)
// ============================
if($password && strlen($password) < 6) {
    responderValidacion("La contraseña debe tener mínimo 6 caracteres", []);
}

try {
    // ============================
    // VALIDACIÓN: Doctor existe y obtener id_usuario
    // ============================
    $stmt = $pdo->prepare("SELECT id_usuario FROM doctores WHERE id = ?");
    $stmt->execute([$id_doctor]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$doctor) {
        responderError("Doctor no encontrado", [], 404);
    }

    $id_usuario = $doctor['id_usuario'];

    // ============================
    // VALIDACIÓN: Especialidad existe
    // ============================
    $stmt = $pdo->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmt->execute([$id_especialidad]);
    if(!$stmt->fetch()) {
        responderError("La especialidad seleccionada no existe", [], 404);
    }

    // ============================
    // VALIDACIÓN: Unicidad correo y cédula (excepto el usuario actual)
    // ============================
    $stmt = $pdo->prepare("
        SELECT id FROM usuarios 
        WHERE (correo = ? OR cedula = ?) AND id != ?
    ");
    $stmt->execute([$correo, $cedula_limpia, $id_usuario]);
    if($stmt->fetch()) {
        responderError("El correo o cédula ya están en uso por otro usuario", [], 409);
    }

    // ============================
    // INICIAR TRANSACCIÓN
    // ============================
    $pdo->beginTransaction();

    $cedula_formateada = substr($cedula_limpia, 0, 3) . '-' . substr($cedula_limpia, 3, 7) . '-' . substr($cedula_limpia, 10, 1);
    $telefono_formateado = substr($telefono_limpio, 0, 3) . '-' . substr($telefono_limpio, 3, 3) . '-' . substr($telefono_limpio, 6, 4);
    $nombreCompleto = $nombre . ' ' . $apellido;

    // ============================
    // PASO 1: Actualizar usuario
    // ============================
    if(!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, correo = ?, genero = ?, password = ?, fecha_actualizado = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $apellido, $cedula_formateada, $telefono_formateado, $correo, $genero, $passwordHash, $id_usuario]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, correo = ?, genero = ?, fecha_actualizado = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $apellido, $cedula_formateada, $telefono_formateado, $correo, $genero, $id_usuario]);
    }

    // ============================
    // PASO 2: Actualizar doctor
    // ============================
    $stmt = $pdo->prepare("
        UPDATE doctores 
        SET nombre = ?, id_especialidad = ?, fecha_actualizado = NOW()
        WHERE id = ?
    ");
    $stmt->execute([$nombreCompleto, $id_especialidad, $id_doctor]);

    // ============================
    // CONFIRMAR TRANSACCIÓN
    // ============================
    $pdo->commit();

    $mensaje = empty($password) ? 
        "Doctor actualizado correctamente" : 
        "Doctor actualizado correctamente (con cambio de contraseña)";

    responderExito($mensaje, ['id' => $id_doctor, 'id_usuario' => $id_usuario]);

} catch(PDOException $e) {
    if($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    responderError("Error al actualizar el doctor: " . $e->getMessage(), [], 500);
}
?>