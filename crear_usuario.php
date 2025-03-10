<?php
include 'init.php'; // Iniciar sesión
include 'conexion.php'; // Incluye la conexión a la base de datos

// Verificar si ya hay una sesión activa
if(isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmar_password'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    
    // Validar que los campos no estén vacíos
    if(empty($usuario) || empty($password) || empty($confirmar_password) || empty($nombre) || empty($email)) {
        $error = "Por favor, complete todos los campos";
    } 
    // Verificar que las contraseñas coincidan
    else if($password != $confirmar_password) {
        $error = "Las contraseñas no coinciden";
    } 
    else if(strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico no es válido";
    }
    else {
        // Verificar si el usuario ya existe
        $query = "SELECT id FROM usuarios WHERE usuario = ? OR email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ss", $usuario, $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if($resultado->num_rows > 0) {
            $error = "El usuario o email ya existe";
        } else {
            // Encriptar la contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insertar el nuevo usuario
            $query = "INSERT INTO usuarios (usuario, password, nombre, email, fecha_registro) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ssss", $usuario, $password_hash, $nombre, $email);
            
            if($stmt->execute()) {
                $success = "Usuario creado correctamente. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al crear el usuario: " . $stmt->error;
            }
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - GYMPROYECT</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap">
    
    <style>
        :root {
            --primary: #242582; /* Azul deportivo */
            --secondary: #f64c72; /* Rosa energético */
            --accent: #2eb86d; /* Verde vibrante */
            --light: #f9f9f9;
            --dark: #1a1a2e;
            --gray: #e5e5e5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--primary), #553d9a);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .register-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--primary), #553d9a);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .register-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .register-logo i {
            font-size: 2.5em;
            margin-right: 10px;
        }
        
        .register-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8em;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .register-header p {
            margin-top: 5px;
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .register-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        .form-group .input-with-icon {
            position: relative;
        }
        
        .form-group .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(36, 37, 130, 0.2);
            outline: none;
        }
        
        .error-message {
            background-color: rgba(246, 76, 114, 0.1);
            border-left: 4px solid var(--secondary);
            color: var(--secondary);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background-color: rgba(46, 184, 109, 0.1);
            border-left: 4px solid var(--accent);
            color: var(--accent);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .register-button {
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1em;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(246, 76, 114, 0.2);
        }
        
        .register-button:hover {
            background-color: #e43c64;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(246, 76, 114, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .back-to-site {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        
        .back-to-site a {
            display: flex;
            align-items: center;
            color: var(--light);
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-to-site a i {
            margin-right: 5px;
        }
        
        .back-to-site a:hover {
            color: white;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .register-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="register-logo">
                <i class="fas fa-dumbbell"></i>
                <h1>GYMPROYECT</h1>
            </div>
            <p>Crea tu cuenta para acceder al sistema</p>
        </div>
        
        <form class="register-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if(!empty($error)): ?>
                <div class="error-message">
                    <p><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="success-message">
                    <p><i class="fas fa-check-circle"></i> <?php echo $success; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="usuario">Nombre de Usuario</label>
                <div class="input-with-icon">
                    <i class="fas fa-id-badge"></i>
                    <input type="text" id="usuario" name="usuario" placeholder="Crea un nombre de usuario" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Crea una contraseña" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirmar_password">Confirmar Contraseña</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmar_password" name="confirmar_password" placeholder="Confirma tu contraseña" required>
                </div>
            </div>
            
            <button type="submit" class="register-button">Crear Cuenta</button>
            
            <div class="login-link">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>
    
    <div class="back-to-site">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Volver al sitio</a>
    </div>
</body>
</html>