<?php
session_start();
include("../config/db.php");

// Guardar cita
if($_POST){

    $stmt = $pdo->prepare("INSERT INTO citas 
    (usuario_id, especialidad_id, fecha, estado)
    VALUES (?,?,?,?)");

    $stmt->execute([
        $_SESSION['user']['id'],
        $_POST['especialidad'],
        $_POST['fecha'],
        "pendiente"
    ]);

    echo "<p style='color:green'>Cita agendada correctamente</p>";
}
?>

<form method="POST">
<select name="especialidad">
<option value="1">Psicología</option>
<option value="2">Ginecología</option>
</select>

<input type="date" name="fecha">

<button>Agendar</button>
</form>