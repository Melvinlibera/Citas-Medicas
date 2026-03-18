<?php
session_start();
require_once("../config/db.php");

// Validar ID
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("Especialidad no válida");
}

$id = $_GET['id'];

// Obtener especialidad
$stmt = $pdo->prepare("SELECT * FROM especialidades WHERE id = ?");
$stmt->execute([$id]);
$esp = $stmt->fetch();

if(!$esp){
    die("Especialidad no encontrada");
}

// Obtener doctores de la especialidad
$stmt = $pdo->prepare("SELECT * FROM doctores WHERE id_especialidad = ?");
$stmt->execute([$id]);
$doctores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $esp['nombre']; ?></title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
/* CONTENEDOR */
.detalle-container {
    min-height: 100vh;
    padding: 40px 20px;

    display: flex;
    justify-content: center;
    align-items: flex-start; /* evita recorte */
}

/* CAJA */
.detalle-box {
    background: white;
    padding: 40px;
    border-radius: 16px;
    max-width: 600px;
    width: 100%;

    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

/* TITULO */
.detalle-box h1 {
    color: #0a1f44;
    margin-bottom: 15px;
    text-align: center;
}

/* TEXTO */
.detalle-box p {
    color: #555;
    line-height: 1.6;
    text-align: center;
}

/* PRECIOS */
.precio {
    margin-top: 20px;
    font-size: 18px;
    font-weight: 600;
    text-align: center;
}

.seguro {
    color: #1e90ff;
    font-size: 16px;
    text-align: center;
}

/* DOCTORES */
.doctores {
    margin-top: 30px;
}

.doctor {
    background: #f5f7fa;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 10px;
    text-align: center;
}

/* BOTÓN */
.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 16px;
    background: #0a1f44;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: 0.3s;
}

.btn:hover {
    background: #1e90ff;
}
</style>

</head>

<body>

<div class="detalle-container">

    <div class="detalle-box">

        <!-- ESPECIALIDAD -->
        <h1><?php echo $esp['nombre']; ?></h1>

        <p><?php echo $esp['descripcion']; ?></p>

        <div class="precio">
            Precio: RD$<?php echo number_format($esp['precio']); ?>
        </div>

        <div class="seguro">
            Con seguro: RD$<?php echo number_format($esp['precio'] * 0.25); ?>
        </div>

        <!-- DOCTORES -->
        <div class="doctores">
            <h3 style="text-align:center; margin-top:25px;">Doctores disponibles</h3>

            <?php if($doctores): ?>

                <?php foreach($doctores as $doc): ?>

                    <div class="doctor">

                        <strong><?php echo $doc['nombre']; ?></strong><br>

                        <?php if(isset($_SESSION['usuario'])): ?>

                            <a href="../user/agendar.php?doctor=<?php echo $doc['id']; ?>" class="btn">
                                Agendar cita
                            </a>

                        <?php else: ?>

                            <a href="../auth/login.php" class="btn">
                                Iniciar sesión
                            </a>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p style="text-align:center;">No hay doctores disponibles.</p>

            <?php endif; ?>
        </div>

    </div>

</div>

</body>
</html>