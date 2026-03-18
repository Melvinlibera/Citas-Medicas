<?php
session_start();
header('Content-Type: application/json');
include("../config/db.php");

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
$stmt->execute([$id]);

echo json_encode(['message'=>'Usuario eliminado']);