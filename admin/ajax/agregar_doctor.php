<?php
require("../config/db.php");
header("Content-Type: application/json");

try {

    // VALIDAR CAMPOS
    if(
        empty($_POST['nombre']) ||
        empty($_POST['cedula']) ||
        empty($_POST['correo']) ||
        empty($_POST['password']) ||
        empty($_POST['id_especialidad'])
    ){
        throw new Exception("Todos los campos son obligatorios");
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

    // 2. CREAR DOCTOR
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
        "message" => "Doctor agregado correctamente"
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