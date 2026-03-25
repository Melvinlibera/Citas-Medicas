<?php
/**
 * PÁGINA DE LOGIN
 * 
 * Funcionalidad:
 * - Autenticación de usuarios
 * - Validación de credenciales
 * - Verificación de contraseña con password_verify()
 * - Manejo de sesiones
 * - Redirección según rol (admin/user)
 * 
 * Seguridad:
 * - Prepared statements para prevenir inyección SQL
 * - password_verify() para validación segura de contraseñas
 * - Mensajes de error genéricos
 * - Sesiones validadas
 */

session_start();
include("../config/db.php");

// =========================
// INICIALIZAR VARIABLES
// =========================
$error = "";
$correo_guardado = "";

// =========================
// PROCESAR FORMULARIO
// =========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener y limpiar datos
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Guardar correo para repoblar el formulario
    $correo_guardado = htmlspecialchars($correo);

    // =========================
    // VALIDACIONES BÁSICAS
    // =========================

    if(empty($correo) || empty($password)){
        $error = "Por favor, completa todos los campos";
    }
    elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $error = "Correo electrónico inválido";
    }
    else {

        // =========================
        // BUSCAR USUARIO EN BD
        // =========================

        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
            $stmt->execute([$correo]);
            $user = $stmt->fetch();

            // =========================
            // VERIFICAR CONTRASEÑA
            // =========================

            if($user && password_verify($password, $user['password'])){

                // Contraseña correcta - crear sesión
                $_SESSION['usuario'] = $user['correo'];
                $_SESSION['nombre'] = trim($user['nombre'] . ' ' . $user['apellido']);
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['cedula'] = $user['cedula'];
                $_SESSION['telefono'] = $user['telefono'];
                $_SESSION['seguro'] = $user['seguro'];

                // Redirección según rol
                if($user['rol'] == 'admin'){
                    header("Location: ../admin/dashboard.php");
                } elseif($user['rol'] == 'doctor'){
                    header("Location: ../doctor/dashboard.php");
                } else {
                    header("Location: ../user/dashboard.php");
                }
                exit();

            } else {
                // Contraseña incorrecta o usuario no existe
                $error = "Correo o contraseña incorrectos";
            }

        } catch(PDOException $e) {
            $error = "Error en la base de datos. Por favor, intenta más tarde.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Hospital & Human</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        /* =========================
           CONTENEDOR DE LOGIN
        ========================= */
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0a1f44, #1e90ff);
            padding: 20px;
        }

        /* =========================
           CAJA DE LOGIN
        ========================= */
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 14px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: slideUp 0.5s ease-out;
        }

        .login-box h2 {
            margin-bottom: 10px;
            color: #0a1f44;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
        }

        .login-box .subtitle {
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

        .form-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e90ff;
            box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.1);
        }

        /* =========================
           MENSAJE DE ERROR
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

        /* =========================
           BOTÓN DE ENVÍO
        ========================= */
        .login-box button {
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

        .login-box button:hover {
            background: #1e90ff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 144, 255, 0.3);
        }

        .login-box button:active {
            transform: translateY(0);
        }

        /* =========================
           ENLACES
        ========================= */
        .links {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .links a {
            color: #1e90ff;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .links a:hover {
            color: #0a1f44;
            text-decoration: underline;
        }

        .links p {
            margin: 10px 0;
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
            .login-box {
                padding: 30px 20px;
            }

            .login-box h2 {
                font-size: 24px;
            }
        }
    </style>

</head>

<body>
<script>
    // Aplicar tema INMEDIATAMENTE antes de renderizar el contenido
    (function() {
        const storedTheme = localStorage.getItem('hnh-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        document.body.classList.add(theme);
    })();
</script>

<?php include("../includes/floating_theme_toggle.php"); ?>

<div class="login-container">

    <form class="login-box" method="POST" onsubmit="return validarLogin()">
        <input type="hidden" name="csrf_token" value="<?= esc(obtenerTokenCSRF()) ?>">

        <h2>Iniciar Sesión</h2>
        <p class="subtitle">Bienvenido a Hospital & Human</p>

        <!-- Mensaje de error -->
        <?php if($error): ?>
            <div class="error">
                <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Correo electrónico -->
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input 
                type="email" 
                id="correo"
                name="correo" 
                placeholder="tu@correo.com" 
                value="<?php echo $correo_guardado; ?>"
                required
            >
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="password-field">
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    placeholder="Tu contraseña" 
                    required
                >
                <button type="button" class="eye-btn" onclick="togglePassword('password')" title="Mostrar/Ocultar contraseña"><i class='bx bx-hide'></i></button>
            </div>
        </div>

        <!-- Botón de envío -->
        <button type="submit">Entrar</button>

        <!-- Enlaces -->
        <div class="links">
            <p>¿No tienes cuenta?<br>
                <a href="register.php">Regístrate aquí</a>
            </p>
            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <a href="../index.php">← Volver al inicio</a>
            </p>
        </div>

    </form>

</div>

<!-- Scripts -->
<script src="../assets/js/validaciones.js" defer></script>
<script src="../assets/js/main.js" defer></script>
<script>
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

</body>
</html>
