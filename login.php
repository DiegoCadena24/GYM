<?php
include 'init.php'; // Iniciar sesión
include 'conexion.php'; // Incluye la conexión a la base de datos

// Verificar si ya hay una sesión activa
if(isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    
    // Validar que los campos no estén vacíos
    if(empty($usuario) || empty($password)) {
        $error = "Por favor, complete todos los campos";
    } else {
        // Consulta para verificar las credenciales
        $query = "SELECT id, usuario, password FROM usuarios WHERE usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if($resultado->num_rows == 1) {
            $row = $resultado->fetch_assoc();
            
            // Verificar la contraseña (usando password_verify si las contraseñas están hasheadas)
            if(password_verify($password, $row['password'])) {
                // Iniciar sesión
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nombre'] = $row['usuario'];
                
                // Redirigir al index
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta";
            }
        } else {
            $error = "Usuario no encontrado";
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
    <title>Iniciar Sesión - GYMPROYECT</title>
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
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary), #553d9a);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .login-logo i {
            font-size: 2.5em;
            margin-right: 10px;
        }
        
        .login-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8em;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .login-header p {
            margin-top: 5px;
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
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
            color: var(--secondary);
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        .login-button {
            background-color: var(--primary);
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
            box-shadow: 0 4px 6px rgba(36, 37, 130, 0.2);
        }
        
        .login-button:hover {
            background-color: #1e1e6e;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(36, 37, 130, 0.3);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .back-to-site {
            display: flex;
            justify-content: center;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-dumbbell"></i>
                <h1>GYMPROYECT</h1>
            </div>
            <p>Accede a tu cuenta para administrar</p>
        </div>
        
        <form class="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if(!empty($error)): ?>
                <div class="error-message">
                    <p><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <div class="input-with-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-with-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
            </div>
            
            <button type="submit" class="login-button">Iniciar Sesión</button>
            
            <div class="register-link">
                <p>¿No tienes una cuenta? <a href="crear_usuario.php">Regístrate aquí</a></p>
            </div>
        </form>
    </div>
</body>
</html>