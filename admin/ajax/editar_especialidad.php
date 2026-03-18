<?php
include("../../config/db.php");
header('Content-Type: application/json');

if($_POST){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    $stmt = $pdo->prepare("UPDATE especialidades SET nombre=?, descripcion=?, precio=? WHERE id=?");
    $stmt->execute([$nombre,$descripcion,$precio,$id]);

    echo json_encode(['success'=>true,'message'=>'Especialidad actualizada correctamente']);
    exit;
}
echo json_encode(['success'=>false,'message'=>'Datos inválidos']);