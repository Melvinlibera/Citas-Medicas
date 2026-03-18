<?php
session_start();
include("../config/db.php");

// Verifica login
if(!isset($_SESSION['user'])){
    header("Location: ../auth/login.php");
    exit;
}

// Consulta de citas del usuario
$stmt = $pdo->prepare("
SELECT citas.*, especialidades.nombre 
FROM citas 
JOIN especialidades ON citas.especialidad_id = especialidades.id
WHERE usuario_id = ?
");

$stmt->execute([$_SESSION['user']['id']]);
$citas = $stmt->fetchAll();
?>

<h2>Mis Citas</h2>

<table border="1">
<tr>
    <th>Especialidad</th>
    <th>Fecha</th>
    <th>Estado</th>
</tr>

<?php foreach($citas as $c): ?>
<tr>
    <td><?php echo $c['nombre']; ?></td>
    <td><?php echo $c['fecha']; ?></td>
    <td><?php echo $c['estado']; ?></td>
</tr>
<?php endforeach; ?>

</table>