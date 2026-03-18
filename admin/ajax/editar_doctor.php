<?php
include("../../config/db.php");
header('Content-Type: application/json');

if($_POST){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $id_especialidad = $_POST['id_especialidad'];

    $stmt = $pdo->prepare("UPDATE doctores SET nombre=?, id_especialidad=? WHERE id=?");
    $stmt->execute([$nombre,$id,$id_especialidad]);

    echo json_encode(['success'=>true,'message'=>'Doctor actualizado correctamente']);
    exit;
}
echo json_encode(['success'=>false,'message'=>'Datos inválidos']);