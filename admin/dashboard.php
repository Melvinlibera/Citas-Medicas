<?php
session_start();

// Verificar sesión y rol admin
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Panel Admin - Hospital & Human</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
/* ESTILO ADMIN */
.admin-container {
    min-height: 100vh;
    padding: 100px 20px 40px;
    text-align: center;
}

.admin-box {
    background: white;
    max-width: 600px;
    margin: auto;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.admin-box h1 {
    color: #0a1f44;
    margin-bottom: 20px;
}

.admin-box p {
    color: #555;
}

.admin-links a {
    display: block;
    margin: 15px 0;
    padding: 12px;

    background: #0a1f44;
    color: white;
    text-decoration: none;
    border-radius: 8px;

    transition: 0.3s;
}

.admin-links a:hover {
    background: #1e90ff;
}
</style>

</head>

<body>

<div class="admin-container">

    <div class="admin-box">

        <h1>Panel Administrador</h1>

        <p>Bienvenido, <b><?php echo $_SESSION['usuario']; ?></b></p>

        <div class="admin-links">
            <a href="citas.php">Gestionar Citas</a>
            <a href="../auth/logout.php">Cerrar sesión</a>
        </div>

    </div>

</div>

</body>
</html>