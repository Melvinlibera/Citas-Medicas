<?php
require("../config/db.php");
header("Content-Type: application/json");

try {

    // VALIDAR DATOS
    if(
        empty($_POST['id_usuario']) ||
        empty($_POST['id_doctor']) ||
        empty($_POST['id_especialidad']) ||
        empty($_POST['fecha']) ||
        empty($_POST['hora'])
    ){
        throw new Exception("Datos incompletos");
    }

    $stmt = $pdo->prepare("
        INSERT INTO citas 
        (id_usuario, id_doctor, id_especialidad, fecha, hora, estado)
        VALUES (?, ?, ?, ?, ?, 'pendiente')
    ");

    $stmt->execute([
        $_POST['id_usuario'],
        $_POST['id_doctor'],
        $_POST['id_especialidad'],
        $_POST['fecha'],
        $_POST['hora']
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Cita agregada correctamente"
    ]);

} catch(Exception $e){

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>