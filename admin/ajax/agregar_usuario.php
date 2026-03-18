<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    if(!$nombre || !$correo || !$password){
        echo json_encode(['message'=>'Datos incompletos']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre,correo,password,rol) VALUES (?,?,?,?)");
    $stmt->execute([$nombre,$correo,$password,$rol]);

    echo json_encode(['message'=>'Usuario creado']);
}