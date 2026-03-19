<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

try {

    // VALIDAR CAMPOS
    if(
        empty($_POST['id']) ||
        empty($_POST['nombre']) ||
        empty($_POST['cedula']) ||
        empty($_POST['correo']) ||
        empty($_POST['rol'])
    ){
        throw new Exception("Faltan datos obligatorios");
    }

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $password = $_POST['password'] ?? null;

    // SI VIENE PASSWORD → ACTUALIZA TODO
    if(!empty($password)){
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre=?, cedula=?, correo=?, password=?, rol=? 
            WHERE id=?
        ");

        $stmt->execute([$nombre,$cedula,$correo,$passwordHash,$rol,$id]);

    } else {
        // SIN PASSWORD
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nombre=?, cedula=?, correo=?, rol=? 
            WHERE id=?
        ");

        $stmt->execute([$nombre,$cedula,$correo,$rol,$id]);
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