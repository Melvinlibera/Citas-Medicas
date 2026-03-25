<?php
/**
 * PÁGINA DE REGISTRO DE USUARIO
 * 
 * Funcionalidad:
 * - Registro de nuevos pacientes
 * - Validación de datos (servidor y cliente)
 * - Cifrado de contraseñas con password_hash()
 * - Selección de seguro médico
 * - Prevención de duplicados (correo, cédula)
 * 
 * Seguridad:
 * - Validación de entrada (trim, filter_var)
 * - Prepared statements para prevenir inyección SQL
 * - Contraseñas cifradas con PASSWORD_DEFAULT
 * - Mensajes de error genéricos para no revelar información
 */

session_start();
include("../config/db.php");

// =========================
// INICIALIZAR VARIABLES
// =========================
$error = "";
$success = "";
$form_data = [
    'nombre' => '',
    'apellido' => '',
    'cedula' => '',
    'genero' => '',
    'telefono' => '',
    'correo' => '',
    'seguro' => '',
    'nombre_seguro' => ''
];

// =========================
// PROCESAR FORMULARIO
// =========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener y limpiar datos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $genero = $_POST['genero'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $seguro = $_POST['seguro'] ?? '';
    $nombre_seguro = $_POST['nombre_seguro'] ?? null;

    // Guardar datos para repoblar el formulario en caso de error
    $form_data = compact('nombre', 'apellido', 'cedula', 'genero', 'telefono', 'correo', 'seguro', 'nombre_seguro');

    // =========================
    // VALIDACIONES
    // =========================

    // Validar campos obligatorios
    if(empty($nombre) || empty($apellido) || empty($cedula) || empty($genero) || empty($telefono) || empty($correo) || empty($password) || empty($confirm_password) || empty($seguro)){
        $error = "Todos los campos son obligatorios";
    }
    // Validar nombre (mínimo 3 caracteres)
    elseif(strlen($nombre) < 3){
        $error = "El nombre debe tener al menos 3 caracteres";
    }
    // Validar cédula (formato dominicano 11 dígitos)
    elseif(!preg_match('/^\d{11}$/', preg_replace('/[.\-\s()]/i', '', $cedula))){
        $error = "Cédula inválida (debe tener 11 dígitos)";
    }
    // Validar teléfono (10 dígitos RD)
    elseif(!preg_match('/^\d{10}$/', preg_replace('/[.\-\s()]/i', '', $telefono))){
        $error = "Teléfono inválido (debe tener 10 dígitos)";
    }
    // Validar género
    elseif(!in_array($genero, ['masculino','femenino'])){
        $error = "Debes seleccionar género masculino o femenino";
    }
    // Validar email
    elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $error = "Correo electrónico inválido";
    }
    // Validar contraseñas
    elseif($password !== $confirm_password){
        $error = "Las contraseñas no coinciden";
    }
    elseif(strlen($password) < 6){
        $error = "La contraseña debe tener al menos 6 caracteres";
    }
    // Validar seguro
    elseif($seguro === "si" && empty($nombre_seguro)){
        $error = "Debes seleccionar tu seguro médico";
    }
    else {

        // =========================
        // VERIFICAR DUPLICADOS
        // =========================

        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);

        if($stmt->fetch()){
            $error = "El correo ya está registrado en el sistema";
        } else {

            // Verificar si la cédula ya existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE cedula = ?");
            $stmt->execute([$cedula]);

            if($stmt->fetch()){
                $error = "La cédula ya está registrada en el sistema";
            } else {

                // =========================
                // CIFRAR CONTRASEÑA
                // =========================
                $pass_hash = password_hash($password, PASSWORD_DEFAULT);

                // =========================
                // INSERTAR USUARIO
                // =========================
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO usuarios 
                        (nombre, apellido, cedula, telefono, correo, password, seguro, genero, rol, fecha_registro)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                    ");

                    $cedulaLimpia = preg_replace('/[^0-9]/', '', $cedula);
                    $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);
                    $telefonoFormateado = substr($telefonoLimpio,0,3) . '-' . substr($telefonoLimpio,3,3) . '-' . substr($telefonoLimpio,6,4);
                    $cedulaFormateada = substr($cedulaLimpia,0,3) . '-' . substr($cedulaLimpia,3,7) . '-' . substr($cedulaLimpia,10,1);

                    $stmt->execute([
                        $nombre,
                        $apellido,
                        $cedulaFormateada,
                        $telefonoFormateado,
                        $correo,
                        $pass_hash,
                        $seguro === "si" ? $nombre_seguro : 'privado',
                        $genero,
                        'user' // Rol por defecto
                    ]);

                    $success = "✓ Registro exitoso. Ahora puedes iniciar sesión.";
                    
                    // Limpiar formulario
                    $form_data = [
                        'nombre' => '',
                        'apellido' => '',
                        'cedula' => '',
                        'genero' => '',
                        'telefono' => '',
                        'correo' => '',
                        'seguro' => '',
                        'nombre_seguro' => ''
                    ];

                } catch(PDOException $e) {
                    $error = "Error al registrar el usuario. Por favor, intenta nuevamente.";
                    error_log("Error registro: " . $e->getMessage());
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Hospital & Human</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        /* =========================
           CONTENEDOR DE REGISTRO
        ========================= */
        .register-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--background);
            padding: 20px;
        }

        /* =========================
           CAJA DE REGISTRO
        ========================= */
        .register-box {
            background: var(--white);
            padding: 40px;
            border-radius: 14px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: slideUp 0.5s ease-out;
        }

        .register-box h2 {
            margin-bottom: 10px;
            color: var(--text);
            font-size: 28px;
            font-weight: 700;
            text-align: center;
        }

        .register-box .subtitle {
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
            margin-bottom: 25px;
        }

        /* =========================
           CAMPOS DEL FORMULARIO
        ========================= */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--label-text);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--input-border);
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: var(--input-bg);
            color: var(--input-text);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1e90ff;
            box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
        }

        /* =========================
           MENSAJES DE ERROR Y ÉXITO
        ========================= */
        .error {
            color: var(--error);
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        .success {
            color: var(--success);
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        /* =========================
           BOTÓN DE ENVÍO
        ========================= */
        .register-box button {
            width: 100%;
            padding: 12px;
            background: var(--button-bg);
            color: var(--text);
            border: 1px solid var(--button-border);
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .register-box button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 144, 255, 0.3);
        }

        .register-box button:active {
            transform: translateY(0);
        }

        /* =========================
           ENLACE A LOGIN
        ========================= */
        .login-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .login-link a:hover {
            color: #0a1f44;
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
            .register-box {
                padding: 30px 20px;
            }

            .register-box h2 {
                font-size: 24px;
            }
        }
    </style>

</head>

<body>

<?php include("../includes/floating_theme_toggle.php"); ?>

<div class="register-container">

    <form class="register-box" method="POST" onsubmit="return validarRegistro()">
        <input type="hidden" name="csrf_token" value="<?= esc(obtenerTokenCSRF()) ?>">

        <h2>Crear Cuenta</h2>
        <p class="subtitle">Únete a Hospital & Human</p>

        <!-- Mensaje de error -->
        <?php if($error): ?>
            <div class="error">
                <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Mensaje de éxito -->
        <?php if($success): ?>
            <div class="success">
                <strong>✓ Éxito:</strong> <?php echo htmlspecialchars($success); ?>
                <p style="margin-top: 10px; font-size: 13px;">
                    <a href="login.php" style="color: #155724; text-decoration: underline;">Inicia sesión aquí</a>
                </p>
            </div>
        <?php endif; ?>

        <!-- Nombre y apellido -->
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input 
                type="text" 
                id="nombre"
                name="nombre" 
                placeholder="Ej: Juan" 
                value="<?php echo htmlspecialchars($form_data['nombre']); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="apellido">Apellido *</label>
            <input 
                type="text" 
                id="apellido"
                name="apellido" 
                placeholder="Ej: Pérez" 
                value="<?php echo htmlspecialchars($form_data['apellido']); ?>"
                required
            >
        </div>

        <!-- Género -->
        <div class="form-group">
            <label for="genero">Género *</label>
            <select name="genero" id="genero" required>
                <option value="">-- Selecciona género --</option>
                <option value="masculino" <?php echo $form_data['genero'] === 'masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="femenino" <?php echo $form_data['genero'] === 'femenino' ? 'selected' : ''; ?>>Femenino</option>
            </select>
        </div>

        <!-- Cédula -->
        <div class="form-group">
            <label for="cedula">Cédula (11 dígitos) *</label>
            <input 
                type="text" 
                id="cedula"
                name="cedula" 
                inputmode="numeric"
                pattern="\d{3}-\d{7}-\d{1}"
                placeholder="Ej: 402-3610138-8" 
                value="<?php echo htmlspecialchars($form_data['cedula']); ?>"
                required
            >
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <label for="telefono">Teléfono/Celular *</label>
            <input 
                type="tel" 
                id="telefono"
                name="telefono" 
                placeholder="Ej: 849-350-9603" 
                value="<?php echo htmlspecialchars($form_data['telefono']); ?>"
                required
            >
        </div>

        <!-- Correo electrónico -->
        <div class="form-group">
            <label for="correo">Correo Electrónico *</label>
            <input 
                type="email" 
                id="correo"
                name="correo" 
                placeholder="tu@correo.com" 
                value="<?php echo htmlspecialchars($form_data['correo']); ?>"
                required
            >
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password">Contraseña *</label>
            <div class="password-field">
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    placeholder="Mínimo 6 caracteres" 
                    required
                >
                <button type="button" class="eye-btn" onclick="togglePassword('password')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
            </div>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmar Contraseña *</label>
            <div class="password-field">
                <input 
                    type="password" 
                    id="confirm_password"
                    name="confirm_password" 
                    placeholder="Repite contraseña" 
                    required
                >
                <button type="button" class="eye-btn" onclick="togglePassword('confirm_password')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
            </div>
        </div>

        <!-- Seguro médico -->
        <div class="form-group">
            <label for="seguro">¿Tienes Seguro Médico? *</label>
            <select 
                name="seguro" 
                id="seguro"
                required
                onchange="toggleSeguroSelect()"
            >
                <option value="">-- Selecciona una opción --</option>
                <option value="si" <?php echo $form_data['seguro'] === 'si' ? 'selected' : ''; ?>>Sí, tengo seguro</option>
                <option value="no" <?php echo $form_data['seguro'] === 'no' ? 'selected' : ''; ?>>No, soy privado</option>
            </select>
        </div>

        <!-- Seleccionar seguro (oculto por defecto) -->
        <div class="form-group" id="nombre-seguro-group" style="display: <?php echo $form_data['seguro'] === 'si' ? 'block' : 'none'; ?>;">
            <label for="nombre_seguro">Selecciona tu Seguro *</label>
            <select name="nombre_seguro" id="nombre_seguro">
                <option value="">-- Selecciona tu seguro --</option>
                <option value="ARS Palic" <?php echo $form_data['nombre_seguro'] === 'ARS Palic' ? 'selected' : ''; ?>>ARS Palic</option>
                <option value="ARS Humano" <?php echo $form_data['nombre_seguro'] === 'ARS Humano' ? 'selected' : ''; ?>>ARS Humano</option>
                <option value="ARS Universal" <?php echo $form_data['nombre_seguro'] === 'ARS Universal' ? 'selected' : ''; ?>>ARS Universal</option>
                <option value="ARS CMD" <?php echo $form_data['nombre_seguro'] === 'ARS CMD' ? 'selected' : ''; ?>>ARS CMD</option>
                <option value="ARS Mapfre" <?php echo $form_data['nombre_seguro'] === 'ARS Mapfre' ? 'selected' : ''; ?>>ARS Mapfre</option>
                <option value="ARS Senasa" <?php echo $form_data['nombre_seguro'] === 'ARS Senasa' ? 'selected' : ''; ?>>ARS Senasa</option>
                <option value="ARS Monumental" <?php echo $form_data['nombre_seguro'] === 'ARS Monumental' ? 'selected' : ''; ?>>ARS Monumental</option>
            </select>
        </div>

        <!-- Botón de envío -->
        <button type="submit">Registrarse</button>

        <!-- Enlace a login -->
        <p class="login-link">
            ¿Ya tienes cuenta?<br>
            <a href="login.php">Inicia sesión aquí</a>
        </p>

    </form>

</div>

<!-- Scripts -->
<script src="../assets/js/validaciones.js" defer></script>

<script>
/**
 * Mostrar/ocultar select de seguro
 * Se ejecuta cuando cambia la selección de "¿Tienes seguro?"
 */
function toggleSeguroSelect() {
    const seguroSelect = document.getElementById("seguro");
    const nombreSeguroGroup = document.getElementById("nombre-seguro-group");
    const nombreSeguroInput = document.getElementById("nombre_seguro");

    if(seguroSelect.value === "si"){
        nombreSeguroGroup.style.display = "block";
        nombreSeguroInput.required = true;
    } else {
        nombreSeguroGroup.style.display = "none";
        nombreSeguroInput.required = false;
        nombreSeguroInput.value = "";
    }
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    toggleSeguroSelect();

    const cedulaInput = document.getElementById('cedula');
    const telefonoInput = document.getElementById('telefono');

    if (cedulaInput) {
        cedulaInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '').slice(0,11);
            if (value.length > 3 && value.length <= 10) {
                value = value.replace(/^(\d{3})(\d{1,7})/, '$1-$2');
            } else if (value.length === 11) {
                value = value.replace(/^(\d{3})(\d{7})(\d{1})$/, '$1-$2-$3');
            }
            this.value = value;
        });
    }

    if (telefonoInput) {
        telefonoInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '').slice(0,10);
            if (value.length > 3 && value.length <= 6) {
                value = value.replace(/^(\d{3})(\d{1,3})/, '$1-$2');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{3})(\d{3})(\d{1,4})/, '$1-$2-$3');
            }
            this.value = value;
        });
    }
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;

    field.type = field.type === 'password' ? 'text' : 'password';

    const btn = field.parentElement.querySelector('.eye-btn');
    if (btn) {
        btn.innerHTML = field.type === 'password' ? "<i class='bx bx-hide'></i>" : "<i class='bx bx-show'></i>";
    }
}
</script>
<script src="../assets/js/main.js" defer></script>

</body>
</html>
