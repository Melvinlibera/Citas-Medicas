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
    'cedula' => '',
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
    $cedula = trim($_POST['cedula'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $seguro = $_POST['seguro'] ?? '';
    $nombre_seguro = $_POST['nombre_seguro'] ?? null;

    // Guardar datos para repoblar el formulario en caso de error
    $form_data = compact('nombre', 'cedula', 'telefono', 'correo', 'seguro', 'nombre_seguro');

    // =========================
    // VALIDACIONES
    // =========================

    // Validar campos obligatorios
    if(empty($nombre) || empty($cedula) || empty($telefono) || empty($correo) || empty($password) || empty($seguro)){
        $error = "Todos los campos son obligatorios";
    }
    // Validar nombre (mínimo 3 caracteres)
    elseif(strlen($nombre) < 3){
        $error = "El nombre debe tener al menos 3 caracteres";
    }
    // Validar cédula (formato dominicano)
    elseif(!preg_match('/^\d{10}$/', preg_replace('/[.\-\s()]/i', '', $cedula))){
        $error = "Cédula inválida (debe tener 10 dígitos)";
    }
    // Validar teléfono
    elseif(!preg_match('/^\d{7,15}$/', preg_replace('/[.\-\s()]/i', '', $telefono))){
        $error = "Teléfono inválido";
    }
    // Validar email
    elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $error = "Correo electrónico inválido";
    }
    // Validar contraseña (mínimo 6 caracteres)
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
                        (nombre, cedula, telefono, correo, password, seguro, rol, fecha_registro)
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                    ");

                    $stmt->execute([
                        $nombre,
                        $cedula,
                        $telefono,
                        $correo,
                        $pass_hash,
                        $seguro === "si" ? $nombre_seguro : 'privado',
                        'user' // Rol por defecto
                    ]);

                    $success = "✓ Registro exitoso. Ahora puedes iniciar sesión.";
                    // Limpiar formulario
                    $form_data = [
                        'nombre' => '',
                        'cedula' => '',
                        'telefono' => '',
                        'correo' => '',
                        'seguro' => '',
                        'nombre_seguro' => ''
                    ];

                } catch(PDOException $e) {
                    $error = "Error al registrar el usuario. Por favor, intenta nuevamente.";
                    if($debug) {
                        $error .= " (" . $e->getMessage() . ")";
                    }
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

    <style>
        /* =========================
           CONTENEDOR DE REGISTRO
        ========================= */
        .register-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0a1f44, #1e90ff);
            padding: 20px;
        }

        /* =========================
           CAJA DE REGISTRO
        ========================= */
        .register-box {
            background: white;
            padding: 40px;
            border-radius: 14px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: slideUp 0.5s ease-out;
        }

        .register-box h2 {
            margin-bottom: 10px;
            color: #0a1f44;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
        }

        .register-box .subtitle {
            text-align: center;
            color: #555;
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
            color: #0a1f44;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
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
            color: #721c24;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        .success {
            color: #155724;
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
            background: #0a1f44;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .register-box button:hover {
            background: #1e90ff;
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
            color: #555;
        }

        .login-link a {
            color: #1e90ff;
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

<div class="register-container">

    <form class="register-box" method="POST" onsubmit="return validarRegistro()">

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

        <!-- Nombre completo -->
        <div class="form-group">
            <label for="nombre">Nombre Completo *</label>
            <input 
                type="text" 
                id="nombre"
                name="nombre" 
                placeholder="Ej: Juan Pérez" 
                value="<?php echo htmlspecialchars($form_data['nombre']); ?>"
                required
            >
        </div>

        <!-- Cédula -->
        <div class="form-group">
            <label for="cedula">Cédula *</label>
            <input 
                type="text" 
                id="cedula"
                name="cedula" 
                placeholder="Ej: 001-1234567-8" 
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
                placeholder="Ej: +1 (809) 123-4567" 
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
            <input 
                type="password" 
                id="password"
                name="password" 
                placeholder="Mínimo 6 caracteres" 
                required
            >
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
});
</script>

</body>
</html>
