<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$password = $_POST['password'];

if($password){
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, correo=?, password=?, rol=? WHERE id=?");
    $stmt->execute([$nombre,$correo,$password,$rol,$id]);
}else{
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, correo=?, rol=? WHERE id=?");
    $stmt->execute([$nombre,$correo,$rol,$id]);
}

echo json_encode(['message'=>'Usuario actualizado']);