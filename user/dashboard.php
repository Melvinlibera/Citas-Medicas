<?php
session_start();

// Verificar sesión
if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Mi Panel - Hospital & Human</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
.dashboard {
    min-height: 100vh;
    padding: 100px 20px;
    text-align: center;
}

.box {
    background: white;
    max-width: 500px;
    margin: auto;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.box h1 {
    margin-bottom: 20px;
    color: #0a1f44;
}

.links a {
    display: block;
    margin: 12px 0;
    padding: 12px;
    background: #0a1f44;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;
}

.links a:hover {
    background: #1e90ff;
}
</style>

</head>

<body>

<div class="dashboard">

    <div class="box">

        <h1>Bienvenido <?php echo $_SESSION['usuario']; ?></h1>

        <div class="links">
            <a href="agendar.php">📅 Agendar Cita</a>
            <a href="mis_citas.php">📋 Ver Mis Citas</a>
            <a href="../index.php">🏠 Inicio</a>
            <a href="../auth/logout.php">🚪 Cerrar Sesión</a>
        </div>

    </div>

</div>

</body>
</html>