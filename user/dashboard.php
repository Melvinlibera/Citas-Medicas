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
/* ========================= */
/* DASHBOARD USUARIO CON LOGO Y TARJETAS */
/* ========================= */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
}

.dashboard {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    background: linear-gradient(135deg, #0a1f44, #1e90ff);
    padding: 40px 20px;
}

/* Logo */
.dashboard .logo {
    max-width: 200px;
    margin-bottom: 40px;
    transition: transform 0.3s;
}
.dashboard .logo:hover {
    transform: scale(1.05);
}

/* Contenedor de tarjetas */
.card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

/* Tarjetas estilo cascada */
.card {
    background: #ffffff;
    padding: 30px 25px;
    border-radius: 20px;
    width: 220px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-weight: 600;
    color: #0a1f44;
    text-decoration: none;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

/* Icono grande dentro de la tarjeta */
.card span {
    font-size: 36px;
    margin-bottom: 12px;
}

/* Responsive */
@media(max-width: 600px){
    .card-container {
        flex-direction: column;
        gap: 15px;
    }
    .card {
        width: 90%;
        padding: 20px 15px;
    }
}
</style>

</head>

<body>

<div class="dashboard">

    <!-- Logo de la empresa -->
    <img src="../assets/img/logo.png" alt="Hospital & Human" class="logo">

    <h1 style="color:white; margin-bottom:30px;">Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>

    <!-- Contenedor de tarjetas -->
    <div class="card-container">
        <a href="agendar.php" class="card">
            <span>📅</span>
            Agendar Cita
        </a>
        <a href="mis_citas.php" class="card">
            <span>📋</span>
            Ver Mis Citas
        </a>
        <a href="../index.php" class="card">
            <span>🏠</span>
            Inicio
        </a>
        <a href="../auth/logout.php" class="card">
            <span>🚪</span>
            Cerrar Sesión
        </a>
    </div>

</div>

</body>
</html>