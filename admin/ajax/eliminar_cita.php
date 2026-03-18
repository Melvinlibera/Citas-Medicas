<?php
include("../../config/db.php");
$id=$_POST['id'];
$pdo->prepare("DELETE FROM citas WHERE id=?")->execute([$id]);
echo json_encode(['success'=>true,'message'=>"Cita eliminada"]);