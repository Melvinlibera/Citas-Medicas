<?php
/**
 * PÁGINA DE AGENDAMIENTO DE CITA
 * 
 * Funcionalidad:
 * - Formulario para agendar citas médicas
 * - Selección de especialidad, doctor, fecha y hora
 * - Validación de disponibilidad
 * - Confirmación de agendamiento
 * - Integración con base de datos
 * 
 * Seguridad:
 * - Verificación de sesión
 * - Validación de entrada
 * - Prepared statements
 * - Prevención de duplicados
 */

session_start();
require_once("../config/db.php");

// Verifica login
if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$success = "";
$error = "";
$doctor_preseleccionado = isset($_GET['doctor']) ? intval($_GET['doctor']) : null;
$especialidad_preseleccionada = isset($_GET['especialidad']) ? intval($_GET['especialidad']) : null;

// Guardar cita si se envía el formulario
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $fecha = trim($_POST['fecha'] ?? '');
    $id_especialidad = intval($_POST['especialidad'] ?? 0);
    $id_doctor = intval($_POST['doctor'] ?? 0);
    $hora = trim($_POST['hora'] ?? '');

    // Validaciones
    if(!$fecha || !$id_especialidad || !$id_doctor || !$hora){
        $error = "Todos los campos son obligatorios";
    } elseif(strtotime($fecha) < strtotime(date('Y-m-d'))){
        $error = "No se puede agendar una fecha pasada";
    } else {
        // Revisar si ya hay cita del mismo doctor en la misma fecha y hora
        $stmt = $pdo->prepare("SELECT id FROM citas WHERE id_doctor=? AND fecha=? AND hora=? AND estado != 'cancelada'");
        $stmt->execute([$id_doctor, $fecha, $hora]);
        if($stmt->fetch()){
            $error = "Ese horario ya está ocupado. Por favor, selecciona otro.";
        } else {
            // Insertar cita
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO citas (id_usuario, id_especialidad, id_doctor, fecha, hora, estado, fecha_creacion)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([
                    $_SESSION['id'],
                    $id_especialidad,
                    $id_doctor,
                    $fecha,
                    $hora,
                    'pendiente'
                ]);
                $success = "✓ Cita agendada correctamente. Te contactaremos pronto para confirmar.";
            } catch(PDOException $e) {
                $error = "Error al agendar la cita. Por favor, intenta nuevamente.";
            }
        }
    }
}

// Obtener especialidades
try {
    $stmt = $pdo->query("SELECT id, nombre FROM especialidades ORDER BY nombre ASC");
    $especialidades = $stmt->fetchAll();
} catch(PDOException $e) {
    $especialidades = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Hospital & Human</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        /* =========================
           CONTENEDOR DE AGENDAMIENTO
        ========================= */
        .agendar-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 20px;
            margin-top: 60px;
        }

        /* =========================
           CAJA DE AGENDAMIENTO
        ========================= */
        .agendar-box {
            background: white;
            padding: 40px;
            border-radius: var(--radius);
            width: 100%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.5s ease-out;
        }

        .agendar-box h2 {
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 28px;
            font-weight: 700;
            text-align: center;
        }

        .agendar-box .subtitle {
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 25px;
        }

        /* =========================
           CAMPOS DEL FORMULARIO
        ========================= */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: var(--radius-sm);
            border: 1px solid #ddd;
            font-size: 14px;
            transition: var(--transition);
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
        }

        /* =========================
           MENSAJES
        ========================= */
        .error {
            color: #721c24;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px 15px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        .success {
            color: #155724;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 12px 15px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        /* =========================
           BOTÓN DE ENVÍO
        ========================= */
        .agendar-box button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            margin-top: 10px;
            transition: var(--transition);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .agendar-box button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .agendar-box button:active {
            transform: translateY(0);
        }

        /* =========================
           ENLACE DE VOLVER
        ========================= */
        .volver-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: var(--text-light);
        }

        .volver-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .volver-link a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        /* =========================
           ANIMACIONES
        ========================= */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 480px) {
            .agendar-box {
                padding: 30px 20px;
            }

            .agendar-box h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="agendar-container">
    <div class="agendar-box">
        <h2>📅 Agendar Cita</h2>
        <p class="subtitle">Selecciona los detalles de tu cita médica</p>

        <?php if($error): ?>
            <div class="error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validarAgendamiento()">
            
            <!-- Especialidad -->
            <div class="form-group">
                <label for="especialidad">Especialidad *</label>
                <select name="especialidad" id="especialidad" required>
                    <option value="">Selecciona una especialidad</option>
                    <?php foreach($especialidades as $esp): ?>
                        <option value="<?php echo $esp['id']; ?>" <?php echo ($especialidad_preseleccionada == $esp['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($esp['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Doctor -->
            <div class="form-group">
                <label for="doctor">Médico Especialista *</label>
                <select name="doctor" id="doctor" required>
                    <option value="">Selecciona un médico</option>
                </select>
            </div>

            <!-- Fecha -->
            <div class="form-group">
                <label for="fecha">Fecha de la Cita *</label>
                <input type="date" name="fecha" id="fecha" required>
            </div>

            <!-- Hora -->
            <div class="form-group">
                <label for="hora">Hora de la Cita *</label>
                <input type="time" name="hora" id="hora" required>
            </div>

            <!-- Botón de envío -->
            <button type="submit">Agendar Cita</button>

        </form>

        <!-- Volver -->
        <div class="volver-link">
            <a href="../index.php">← Volver al inicio</a>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="../assets/js/validaciones.js" defer></script>

<script>
/**
 * Cargar médicos según la especialidad seleccionada
 */
document.getElementById('especialidad').addEventListener('change', function(){
    var espId = this.value;
    var doctorSelect = document.getElementById('doctor');
    
    if(!espId) {
        doctorSelect.innerHTML = "<option value=''>Selecciona un médico</option>";
        return;
    }

    doctorSelect.innerHTML = "<option value=''>Cargando médicos...</option>";

    axios.post('../user/get_doctores.php', { id_especialidad: espId })
    .then(function(res){
        var docs = res.data;
        doctorSelect.innerHTML = "<option value=''>Selecciona un médico</option>";
        
        if(docs.length === 0) {
            doctorSelect.innerHTML = "<option value=''>No hay médicos disponibles</option>";
            return;
        }

        docs.forEach(d => {
            var opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.nombre;
            <?php if($doctor_preseleccionado): ?>
                if(d.id == <?php echo $doctor_preseleccionado; ?>) {
                    opt.selected = true;
                }
            <?php endif; ?>
            doctorSelect.appendChild(opt);
        });
    })
    .catch(err=>{
        console.error(err);
        doctorSelect.innerHTML = "<option value=''>Error al cargar médicos</option>";
    });
});

// Cargar médicos si hay especialidad preseleccionada
<?php if($especialidad_preseleccionada): ?>
document.getElementById('especialidad').value = <?php echo $especialidad_preseleccionada; ?>;
document.getElementById('especialidad').dispatchEvent(new Event('change'));
<?php endif; ?>

// Establecer fecha mínima a hoy
document.getElementById('fecha').min = new Date().toISOString().split('T')[0];
</script>

</body>
</html>
