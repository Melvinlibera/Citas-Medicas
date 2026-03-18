<?php
include("../config/db.php");

// Obtener todas las especialidades
$stmt = $pdo->prepare("SELECT * FROM especialidades");
$stmt->execute();

$especialidades = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Especialidades - HOSPITAL & HUMAN</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>
.container {
    padding: 80px 20px;
    text-align: center;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    text-decoration: none;
    color: black;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    color: #0a1f44;
    margin-bottom: 10px;
}

.precio {
    font-weight: bold;
    color: #1e90ff;
}

.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 12px;
    background: #0a1f44;
    color: white;
    border-radius: 6px;
    text-decoration: none;
}
</style>

</head>

<body>

<div class="container">

    <h1>Especialidades Médicas</h1>

    <div class="cards">

        <?php foreach($especialidades as $esp): ?>
            
            <div class="card">
                <h3><?php echo $esp['nombre']; ?></h3>

                <p><?php echo $esp['descripcion']; ?></p>

                <p class="precio">RD$<?php echo $esp['precio']; ?></p>

                <a class="btn" href="ver.php?id=<?php echo $esp['id']; ?>">
                    Ver más
                </a>
            </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>