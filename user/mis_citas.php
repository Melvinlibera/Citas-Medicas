<?php
session_start();
include("../config/db.php");

// Verifica login
if(!isset($_SESSION['id'])){
    header("Location: ../auth/login.php");
    exit;
}

// Trae todas las citas del usuario
$stmt = $pdo->prepare("
    SELECT citas.id, citas.fecha, citas.hora, citas.estado, 
           especialidades.nombre AS especialidad, doctores.nombre AS doctor
    FROM citas
    JOIN doctores ON citas.id_doctor = doctores.id
    JOIN especialidades ON doctores.id_especialidad = especialidades.id
    WHERE citas.id_usuario = ?
    ORDER BY citas.fecha DESC, citas.hora DESC
");
$stmt->execute([$_SESSION['id']]);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mis Citas - Hospital & Human</title>
<link rel="stylesheet" href="../assets/css/style.css">

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f0f4f8;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #0a1f44;
    margin-bottom: 20px;
}

.table-container {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-size: 14px;
}

table th {
    background-color: #0a1f44;
    color: white;
    border-radius: 8px;
}

.status {
    padding: 6px 12px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    text-align: center;
    display: inline-block;
}

.status.confirmada { background-color: green; }
.status.pendiente { background-color: orange; }
.status.cancelada { background-color: red; }

@media(max-width: 600px){
    table, thead, tbody, th, td, tr { display: block; }
    th { text-align: right; }
    td { text-align: right; padding-left: 50%; position: relative; }
    td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: 45%;
        font-weight: bold;
        text-align: left;
    }
}
</style>
</head>
<body>

<h2>Mis Citas</h2>

<div class="table-container">
    <?php if(count($citas) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Especialidad</th>
                <th>Doctor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($citas as $c): ?>
            <tr>
                <td data-label="Especialidad"><?php echo htmlspecialchars($c['especialidad']); ?></td>
                <td data-label="Doctor"><?php echo htmlspecialchars($c['doctor']); ?></td>
                <td data-label="Fecha"><?php echo $c['fecha']; ?></td>
                <td data-label="Hora"><?php echo $c['hora']; ?></td>
                <td data-label="Estado">
                    <span class="status <?php echo $c['estado']; ?>">
                        <?php echo ucfirst($c['estado']); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="text-align:center;">No tienes citas agendadas.</p>
    <?php endif; ?>
</div>

</body>
</html>