<?php
require("../config/db.php");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $pdo->prepare("UPDATE citas SET estado=? WHERE id=?");
$stmt->execute([$data['estado'], $data['id']]);

echo json_encode(["success"=>true,"message"=>"Estado actualizado"]);