<?php
require("../config/db.php");
header("Content-Type: application/json");

try {

    // VALIDAR QUE LLEGAN DATOS
    if(
        empty($_POST['nombre']) ||
        empty($_POST['cedula']) ||
        empty($_POST['correo']) ||
        empty($_POST['password']) ||
        empty($_POST['rol'])
    ){
        throw new Exception("Todos los campos son obligatorios");
    }

    // PREPARAR QUERY
    $stmt = $pdo->prepare("INSERT INTO usuarios 
        (nombre, cedula, correo, password, rol) 
        VALUES (?, ?, ?, ?, ?)");

    $stmt->execute([
        $_POST['nombre'],
        $_POST['cedula'],
        $_POST['correo'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
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