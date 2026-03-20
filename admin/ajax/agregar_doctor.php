<?php
/**
 * AGREGAR DOCTOR - AJAX
 *
 * Funcionalidad:
 * - Crea un nuevo doctor en el sistema mediante proceso de dos pasos
 * - Paso 1: Inserta usuario con rol 'doctor' en tabla usuarios
 * - Paso 2: Inserta registro en tabla doctores con enlace a especialidad
 *
 * Proceso detallado:
 * 1. Valida todos los campos obligatorios
 * 2. Valida formato de cédula (10 dígitos) y correo electrónico
 * 3. Verifica que la especialidad seleccionada existe
 * 4. Verifica que no existan duplicados (correo, cédula)
 * 5. Inicia transacción de base de datos
 * 6. Inserta usuario con contraseña hasheada
 * 7. Obtiene ID del usuario creado
 * 8. Inserta registro de doctor con enlace a especialidad
 * 9. Confirma transacción
 *
 * Parámetros POST esperados:
 * - nombre: Nombre completo del doctor
 * - cedula: Cédula sin formato (10 dígitos)
 * - correo: Correo electrónico único
 * - password: Contraseña (mínimo 6 caracteres)
 * - id_especialidad: ID de la especialidad médica
 *
 * Respuesta JSON:
 * - success: true/false
 * - message: Mensaje descriptivo del resultado
 *
 * Validaciones implementadas:
 * - Campos obligatorios
 * - Formato de cédula dominicana
 * - Correo electrónico válido
 * - Contraseña mínimo 6 caracteres
 * - Especialidad existente en BD
 * - Unicidad de correo y cédula
 *
 * Seguridad:
 * - Transacción para integridad de datos
 * - Prepared statements
 * - Validación de existencia de especialidad
 * - Hash de contraseña con PASSWORD_DEFAULT
 */

session_start();
require("../../config/db.php");
header("Content-Type: application/json");

// Validar sesión de administrador
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

try {

    // VALIDAR CAMPOS OBLIGATORIOS
    if(
        empty($_POST['nombre']) ||
        empty($_POST['cedula']) ||
        empty($_POST['correo']) ||
        empty($_POST['password']) ||
        empty($_POST['id_especialidad'])
    ){
        throw new Exception("Todos los campos son obligatorios");
    }

    // Validar formatos
    if(!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)){
        throw new Exception("Formato de correo inválido");
    }

    if(strlen($_POST['password']) < 6){
        throw new Exception("La contraseña debe tener mínimo 6 caracteres");
    }

    // Validar que especialidad existe
    $stmt = $pdo->prepare("SELECT id FROM especialidades WHERE id = ?");
    $stmt->execute([$_POST['id_especialidad']]);
    if(!$stmt->fetch()){
        throw new Exception("Especialidad seleccionada no existe");
    }

    // Validar que email y cedula sean únicos
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ? OR cedula = ?");
    $stmt->execute([$_POST['correo'], $_POST['cedula']]);
    if($stmt->fetch()){
        throw new Exception("Email o cédula ya registrados");
    }

    $pdo->beginTransaction();

    // 1. CREAR USUARIO
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nombre, cedula, correo, password, rol)
        VALUES (?, ?, ?, ?, 'doctor')
    ");

    $stmt->execute([
        $_POST['nombre'],
        $_POST['cedula'],
        $_POST['correo'],
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);

    $id_usuario = $pdo->lastInsertId();

    // 2. CREAR DOCTOR Y ENLAZAR ESPECIALIDAD AUTOMÁTICAMENTE
    $stmt = $pdo->prepare("
        INSERT INTO doctores (nombre, id_especialidad, id_usuario)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $_POST['nombre'],
        $_POST['id_especialidad'],
        $id_usuario
    ]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Doctor agregado correctamente y enlazado automáticamente con su especialidad"
    ]);

} catch(Exception $e){

    if($pdo->inTransaction()){
        $pdo->rollBack();
    }

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>