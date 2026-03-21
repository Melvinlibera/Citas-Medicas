<?php
/**
 * AGREGAR USUARIO - AJAX
 * Validaciones de servidor para crear nuevo usuario
 * Requiere: nombre, cedula, telefono, correo, password, rol
 * Formatea automáticamente cédula (XXX-XXXXXXX-X) y teléfono (XXX-XXX-XXXX)
 * Valida contraseña mínimo 6 caracteres
 * Retorna: JSON con éxito o error
 */

session_start();
require("../../config/db.php");
header("Content-Type: application/json");

// Validar sesión de administrador
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    http_response_code(403);
    echo json_encode([
        "success" => false,
        "message" => "No autorizado. Solo administradores pueden agregar usuarios"
    ]);
    exit;
}

try {
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
    ){
        throw new Exception("Todos los campos son obligatorios");
    }

    if($_POST['password'] !== $_POST['confirm_password']){
        throw new Exception("Las contraseñas no coinciden");
    }

    // ============================
    // VALIDACIÓN: Formato correo
    // ============================
    if(!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)){
        throw new Exception("Formato de correo inválido");
    }

    // ============================
    // VALIDACIÓN: Formato cédula (10 dígitos)
    // ============================
    $cedula_limpia = preg_replace('/[.\-\s()]/i', '', $_POST['cedula']);
    if(!preg_match('/^\d{10}$/', $cedula_limpia)){
        throw new Exception("Cédula inválida (debe tener 10 dígitos)");
    }

    // ============================
    // VALIDACIÓN: Formato teléfono (10 dígitos)
    // ============================
    $telefono_limpio = preg_replace('/[.\-\s()]/i', '', $_POST['telefono']);
    if(!preg_match('/^\d{10}$/', $telefono_limpio)){
        throw new Exception("Teléfono inválido (debe tener 10 dígitos)");
    }

    // ============================
    // VALIDACIÓN: Longitud contraseña
    // ============================
    if(strlen($_POST['password']) < 6){
        throw new Exception("La contraseña debe tener mínimo 6 caracteres");
    }

    // ============================
    // VALIDACIÓN: Rol válido
    // ============================
    $roles_validos = ['admin', 'doctor', 'user'];
    if(!in_array($_POST['rol'], $roles_validos)){
        throw new Exception("Rol inválido");
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

    // PREPARAR QUERY
    $stmt = $pdo->prepare("INSERT INTO usuarios 
        (nombre, apellido, cedula, telefono, correo, password, seguro, genero, rol) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

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

    echo json_encode([
        "success" => true,
        "message" => "Usuario agregado correctamente"
    ]);

} catch(Exception $e){
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>