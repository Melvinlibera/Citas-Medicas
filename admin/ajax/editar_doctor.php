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

// SEGURIDAD
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit;
}

// VALIDAR MÉTODO
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $id = $_POST['id'] ?? null;
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $genero = $_POST['genero'] ?? null;
    $rol = $_POST['rol'] ?? null;
    $id_especialidad = $_POST['id_especialidad'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm_password = $_POST['confirm_password'] ?? null;

    if(!$id || !$nombre || !$apellido || !$cedula || !$telefono || !$correo || !$genero || !$rol || !$id_especialidad){
        echo json_encode(['status'=>'error','message'=>'Todos los campos son obligatorios']);
        exit;
    }

    if($password && $password !== $confirm_password){
        echo json_encode(['status'=>'error','message'=>'Las contraseñas no coinciden']);
        exit;
    }

    if(!in_array($genero, ['masculino', 'femenino'])){
        echo json_encode(['status'=>'error','message'=>'Género inválido']);
        exit;
    }

    if(!in_array($rol, ['user', 'doctor', 'admin'])){
        echo json_encode(['status'=>'error','message'=>'Rol inválido']);
        exit;
    }

    try {

        $pdo->beginTransaction();

        // 1. OBTENER ID_USUARIO DEL DOCTOR
        $stmt = $pdo->prepare("SELECT id_usuario FROM doctores WHERE id = ?");
        $stmt->execute([$id]);
        $doctor = $stmt->fetch();

        if(!$doctor){
            throw new Exception("Doctor no encontrado");
        }

        $id_usuario = $doctor['id_usuario'];
        $nombreCompleto = trim($nombre . ' ' . $apellido);
        $telefono_limpio = preg_replace('/[^0-9]/', '', $telefono);
        $telefonoFormateado = substr($telefono_limpio, 0, 3) . '-' . substr($telefono_limpio, 3, 3) . '-' . substr($telefono_limpio, 6, 4);

        // 2. ACTUALIZAR USUARIO
        if(!empty($password)){
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nombre=?, apellido=?, cedula=?, telefono=?, correo=?, genero=?, rol=?, password=? 
                WHERE id=?
            ");
            $stmt->execute([$nombre, $apellido, $cedula, $telefonoFormateado, $correo, $genero, $rol, $passwordHash, $id_usuario]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE usuarios 
                SET nombre=?, apellido=?, cedula=?, telefono=?, correo=?, genero=?, rol=? 
                WHERE id=?
            ");
            $stmt->execute([$nombre, $apellido, $cedula, $telefonoFormateado, $correo, $genero, $rol, $id_usuario]);
        }

        // 3. ACTUALIZAR DOCTOR
        $stmt = $pdo->prepare("
            UPDATE doctores 
            SET nombre=?, id_especialidad=? 
            WHERE id=?
        ");
        $stmt->execute([$nombreCompleto, $id_especialidad, $id]);

        $pdo->commit();

        echo json_encode([
            'status'=>'success',
            'message'=>'Doctor actualizado correctamente'
        ]);

    } catch (Exception $e){

        if($pdo->inTransaction()){
            $pdo->rollBack();
        }

        echo json_encode([
            'status'=>'error',
            'message'=>$e->getMessage()
        ]);
    }

} else {
    echo json_encode(['status'=>'error','message'=>'Solicitud inválida']);
}
?>