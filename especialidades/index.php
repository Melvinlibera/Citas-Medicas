<?php
/**
 * LISTADO DE ESPECIALIDADES MÉDICAS - HOSPITAL & HUMAN
 *
 * Funcionalidad:
 * - Página pública que muestra todas las especialidades médicas disponibles
 * - Lista completa de servicios médicos ofrecidos por la institución
 * - Información detallada: nombre, descripción y precio de cada especialidad
 * - Enlaces a páginas de detalle para más información
 * - Accesible sin necesidad de autenticación
 *
 * Información mostrada por especialidad:
 * - Nombre de la especialidad médica
 * - Descripción detallada de los servicios
 * - Precio de consulta en pesos dominicanos (RD$)
 * - Botón "Ver más" para acceder a doctores disponibles
 *
 * Características de la página:
 * - Diseño responsive con tarjetas visuales
 * - Información clara y accesible para pacientes
 * - Navegación intuitiva hacia agendamiento de citas
 * - Optimizada para motores de búsqueda (SEO)
 *
 * Base de datos consultada:
 * - Tabla especialidades: id, nombre, descripcion, precio
 * - Consulta simple sin JOIN (información independiente)
 * - Resultados ordenados por ID (orden de inserción)
 *
 * Funcionalidades relacionadas:
 * - Enlace a especialidades/ver.php para más detalles
 * - Integración con sistema de agendamiento
 * - Información base para selección de especialidad
 *
 * Público objetivo:
 * - Pacientes potenciales que buscan servicios médicos
 * - Usuarios que necesitan elegir especialidad para cita
 * - Visitantes interesados en servicios de la institución
 *
 * No requiere autenticación (página pública)
 */

include("../config/db.php");

// Obtener todas las especialidades médicas disponibles
// Consulta simple a tabla especialidades para información pública
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