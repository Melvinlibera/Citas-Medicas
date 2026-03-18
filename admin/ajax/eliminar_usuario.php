<?php
require("../config/db.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
$stmt->execute([$data['id']]);

echo json_encode(["success"=>true,"message"=>"Usuario eliminado"]);