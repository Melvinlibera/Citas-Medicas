<?php
/* =========================
   PANEL DE USUARIO (PACIENTE)
   - Ver citas
   - Acceso protegido
========================= */

session_start();

/* =========================
   VALIDAR SESIÓN
========================= */
if (!isset($_SESSION['usuario'])) {
    header("Location: /citas_medicas/login.php");
    exit();
}

/* =========================
   CONEXIÓN BD (PDO)
========================= */
require_once("../config/db.php");

/* =========================
   OBTENER CITAS DEL USUARIO
========================= */
$stmt = $pdo->prepare("
    SELECT c.*, d.nombre AS doctor, e.nombre AS especialidad
    FROM citas c
    JOIN doctores d ON c.id_doctor = d.id
    JOIN especialidades e ON c.id_especialidad = e.id
    WHERE c.id_usuario = ?
    ORDER BY c.fecha DESC
");

$stmt->execute([$_SESSION['id']]);
$citas = $stmt->fetchAll();
?>

<?php include("../includes/header.php"); ?>

<div class="section">

    <h2>Mis Citas</h2>

    <?php if (count($citas) > 0): ?>

        <div class="cards">

            <?php foreach ($citas as $cita): ?>

                <div class="container">

                    <h3><?php echo $cita['especialidad']; ?></h3>

                    <p><strong>Doctor:</strong> <?php echo $cita['doctor']; ?></p>
                    <p><strong>Fecha:</strong> <?php echo $cita['fecha']; ?></p>
                    <p><strong>Hora:</strong> <?php echo $cita['hora']; ?></p>

                    <!-- BOTÓN ELIMINAR -->
                    <a href="../ajax/eliminar_cita.php?id=<?php echo $cita['id']; ?>" 
                       onclick="return confirm('¿Cancelar cita?')">
                       Cancelar
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <p>No tienes citas registradas.</p>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>