<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    $id = $_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM citas WHERE id=?");
    $stmt->execute([$id]);

    echo json_encode(['message'=>'Cita eliminada']);
}