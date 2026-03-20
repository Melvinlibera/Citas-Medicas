<?php
session_start();
header('Content-Type: application/json');
include("../../config/db.php");

// Validar sesión
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

try {

    // VALIDAR CAMPOS
    if(
        empty($_POST['id']) ||
        empty($_POST['nombre']) ||
        empty($_POST['cedula']) ||
        empty($_POST['telefono']) ||
        empty($_POST['correo']) ||
        empty($_POST['rol'])
    ){
        throw new Exception("Faltan datos obligatorios");
    }

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $password = $_POST['password'] ?? null;

    // ============================
    // VALIDACIÓN: Formato cédula (10 dígitos)
    // ============================
    $cedula_limpia = preg_replace('/[.\-\s()]/i', '', $cedula);
    if(!preg_match('/^\d{10}$/', $cedula_limpia)){
        throw new Exception("Cédula inválida (debe tener 10 dígitos)");
    }

    // ============================
    // VALIDACIÓN: Formato teléfono (10 dígitos)
    // ============================
    $telefono_limpio = preg_replace('/[.\-\s()]/i', '', $telefono);
    if(!preg_match('/^\d{10}$/', $telefono_limpio)){
        throw new Exception("Teléfono inválido (debe tener 10 dígitos)");
    }

    // ============================
    // FORMATEO AUTOMÁTICO
    // ============================
    $cedula_formateada = substr($cedula_limpia, 0, 3) . '-' . substr($cedula_limpia, 3, 7) . '-' . substr($cedula_limpia, 10, 1);
    $telefono_formateado = substr($telefono_limpio, 0, 3) . '-' . substr($telefono_limpio, 3, 3) . '-' . substr($telefono_limpio, 6, 4);

    // SI VIENE PASSWORD → ACTUALIZA TODO
    if(!empty($password)){
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre=?, cedula=?, telefono=?, correo=?, password=?, rol=? 
            WHERE id=?
        ");

        $stmt->execute([$nombre,$cedula_formateada,$telefono_formateado,$correo,$passwordHash,$rol,$id]);

    } else {
        // SIN PASSWORD
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre=?, cedula=?, telefono=?, correo=?, rol=? 
            WHERE id=?
        ");

        $stmt->execute([$nombre,$cedula_formateada,$telefono_formateado,$correo,$rol,$id]);
    }

    echo json_encode([
        "success" => true,
        "message" => "Usuario actualizado correctamente"
    ]);

} catch(Exception $e){
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>