<?php
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin'){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Admin - Hospital & Human</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<style>
/* Reset simple */
*{margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', sans-serif;}
body{background:#f4f7fc;}

/* Sidebar */
.sidebar{
    position: fixed;
    left:0;
    top:0;
    width:220px;
    height:100%;
    background:#0a1f44;
    color:#fff;
    display:flex;
    flex-direction:column;
    padding-top:20px;
}
.sidebar h2{
    text-align:center;
    margin-bottom:30px;
    font-size:22px;
    color:#1e90ff;
}
.sidebar a{
    padding:15px 20px;
    text-decoration:none;
    color:#fff;
    display:flex;
    align-items:center;
    gap:10px;
    transition:0.3s;
}
.sidebar a:hover{
    background:#1e90ff;
}

/* Main content */
.main{
    margin-left:220px;
    padding:40px 30px;
}

/* Cards */
.card{
    background:#fff;
    padding:25px;
    border-radius:16px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    margin-bottom:20px;
    transition:0.3s;
}
.card:hover{
    box-shadow:0 10px 30px rgba(0,0,0,0.12);
}

/* Buttons */
button{
    padding:10px 18px;
    border:none;
    border-radius:8px;
    background:#0a1f44;
    color:white;
    cursor:pointer;
    transition:0.3s;
}
button:hover{
    background:#1e90ff;
}

/* Table modern */
table{
    width:100%;
    border-collapse:collapse;
}
table th, table td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
table th{
    background:#f0f4f8;
    color:#0a1f44;
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>Admin</h2>
    <a href="dashboard.php"><i class='bx bx-home'></i> Inicio</a>
    <a href="doctores.php"><i class='bx bx-user'></i> Doctores</a>
    <a href="especialidades.php"><i class='bx bx-briefcase'></i> Especialidades</a>
    <a href="citas.php"><i class='bx bx-calendar'></i> Citas</a>
    <a href="usuarios.php"><i class='bx bx-group'></i> Usuarios</a>
    <a href="../auth/logout.php"><i class='bx bx-log-out'></i> Cerrar sesión</a>
</div>

<!-- Main content -->
<div class="main">
    <div class="card">
        <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?></h1>
        <p>Usa el menú lateral para gestionar doctores, especialidades, citas y usuarios.</p>
    </div>

    <div class="card">
        <h3>Resumen rápido</h3>
        <p>Doctores: <?php
            $count = $pdo->query("SELECT COUNT(*) FROM doctores")->fetchColumn();
            echo $count;
        ?></p>
        <p>Especialidades: <?php
            $count = $pdo->query("SELECT COUNT(*) FROM especialidades")->fetchColumn();
            echo $count;
        ?></p>
        <p>Usuarios: <?php
            $count = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
            echo $count;
        ?></p>
        <p>Citas totales: <?php
            $count = $pdo->query("SELECT COUNT(*) FROM citas")->fetchColumn();
            echo $count;
        ?></p>
    </div>
</div>

</body>
</html>