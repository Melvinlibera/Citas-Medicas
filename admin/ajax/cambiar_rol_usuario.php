<?php
require("../config/db.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("UPDATE usuarios SET rol=? WHERE id=?");
$stmt->execute([$data['rol'], $data['id']]);

echo json_encode(["success"=>true,"message"=>"Rol actualizado"]);