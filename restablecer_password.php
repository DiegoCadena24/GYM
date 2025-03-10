<?php
session_start();

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "gymdb";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha proporcionado un token
if (!isset($_GET['token'])) {
    die("Token no válido.");
}

$token = $_GET['token'];

// Buscar el token en la base de datos
$stmt = $conn->prepare("SELECT id FROM Administrador WHERE token = ? AND token_expiracion > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    die("Token no válido o expirado.");
}

$stmt->bind_result($id);
$stmt->fetch();

// Procesar el formulario de restablecimiento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nueva_password = trim($_POST['password']);
    $confirmar_password = trim($_POST['confirmar_password']);

    // Validar que las contraseñas coincidan
    if ($nueva_password !== $confirmar_password) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Hashear la nueva contraseña
        $hashed_password = password_hash($nueva_password, PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE Administrador SET password = ?, token = NULL, token_expiracion = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $id);
        $stmt->execute();

        $exito = "Contraseña restablecida correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <!-- Estilos CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .login-container .error {
            color: #f44336;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .login-container .exito {
            color: #4CAF50;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Restablecer Contraseña</h2>

        <!-- Mostrar mensajes -->
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($exito)): ?>
            <p class="exito"><?php echo $exito; ?></p>
        <?php endif; ?>

        <!-- Formulario de restablecimiento -->
        <form method="post" action="">
            <input type="password" name="password" placeholder="Nueva Contraseña" required><br>
            <input type="password" name="confirmar_password" placeholder="Confirmar Contraseña" required><br>
            <input type="submit" value="Restablecer Contraseña">
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>