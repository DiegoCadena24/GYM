<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "gymdb";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Variables
$mensaje = '';

// Procesar los datos cuando se envíen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $membresia_id = $_POST['membresia_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $metodo_pago = $_POST['metodo_pago'];

    // Insertar el miembro
    $sql = "INSERT INTO Miembro (nombre, apellido, telefono, email, membresia_id, fecha_registro) 
            VALUES ('$nombre', '$apellido', '$telefono', '$email', '$membresia_id', '$fecha_inicio')";

    if ($conn->query($sql)) {
        $miembro_id = $conn->insert_id; // Obtener el ID del miembro recién insertado

        // Obtener el costo de la membresía
        $sql_membresia = "SELECT costo FROM Membresia WHERE id = $membresia_id";
        $result_membresia = $conn->query($sql_membresia);
        $row_membresia = $result_membresia->fetch_assoc();
        $monto = $row_membresia['costo'];

        // Insertar el pago
        $sql_pago = "INSERT INTO Pago (miembro_id, monto, fecha_pago, metodo_pago) 
                     VALUES ($miembro_id, $monto, '$fecha_inicio', '$metodo_pago')";
        if ($conn->query($sql_pago)) {
            $mensaje = "Miembro registrado y pago procesado correctamente.";
        } else {
            $mensaje = "Error al registrar el pago: " . $conn->error;
        }
    } else {
        $mensaje = "Error al registrar el miembro: " . $conn->error;
    }
}

// Obtener los tipos de membresía
$sql = "SELECT * FROM Membresia";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Miembro - GYMPROYECT</title>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap">
    
    <!-- Estilos CSS -->
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
            background-color: var(--light);
            color: #333;
            line-height: 1.6;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), #553d9a);
            padding: 20px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }
        
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/api/placeholder/1200/400');
            background-size: cover;
            background-position: center;
            opacity: 0.15;
            z-index: 0;
        }
        
        .header-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 20px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .logo i {
            font-size: 2.5em;
            color: white;
            margin-right: 10px;
        }
        
        header h1 {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            font-size: 2.8em;
            color: white;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2em;
            margin-top: 5px;
            font-weight: 300;
        }
        
        nav {
            background-color: var(--dark);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .nav-links {
            display: flex;
        }
        
        nav a {
            display: flex;
            align-items: center;
            color: white;
            padding: 16px 20px;
            text-align: center;
            text-decoration: none;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        nav a i {
            margin-right: 8px;
            font-size: 1.1em;
        }
        
        nav a:hover {
            background-color: var(--secondary);
            color: white;
            transform: translateY(-2px);
        }
        
        .active {
            border-bottom: 3px solid var(--secondary);
        }
        
        .main-container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .page-title h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.5em;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .page-title p {
            color: #666;
            font-size: 1.1em;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .page-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--secondary);
            margin: 15px auto 0;
            border-radius: 2px;
        }
        
        .form-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto 60px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(to right, var(--primary), #553d9a);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }
        
        .form-header h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8em;
            margin: 0;
            font-weight: 600;
        }
        
        .form-header p {
            margin-top: 5px;
            opacity: 0.9;
        }
        
        .form-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .form-body {
            padding: 30px;
        }
        
        .mensaje {
            margin-bottom: 25px;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
            font-size: 1.1em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .mensaje i {
            margin-right: 10px;
            font-size: 1.3em;
        }
        
        .mensaje.success {
            background-color: rgba(46, 184, 109, 0.15);
            color: var(--accent);
            border-left: 4px solid var(--accent);
        }
        
        .mensaje.error {
            background-color: rgba(246, 76, 114, 0.15);
            color: var(--secondary);
            border-left: 4px solid var(--secondary);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-family: 'Montserrat', sans-serif;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 1.1em;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1em;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
            color: #333;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(36, 37, 130, 0.1);
            outline: none;
            background-color: #fff;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .btn-submit {
            display: block;
            width: 100%;
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 14px;
            font-size: 1.1em;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(246, 76, 114, 0.4);
        }
        
        .btn-submit:hover {
            background-color: #e13c60;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(246, 76, 114, 0.5);
        }
        
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-section h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.4em;
            margin-bottom: 20px;
            font-weight: 600;
            color: var(--secondary);
        }
        
        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-section a:hover {
            color: var(--secondary);
        }
        
        .social-links {
            margin-top: 15px;
        }
        
        .social-links a {
            display: inline-block;
            margin-right: 15px;
            color: white;
            font-size: 1.2em;
            transition: transform 0.3s ease;
        }
        
        .social-links a:hover {
            transform: translateY(-3px);
            color: var(--secondary);
        }
        
        .copyright {
            text-align: center;
            padding: 20px 0 10px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9em;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .required::after {
            content: '*';
            color: var(--secondary);
            margin-left: 3px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
            }
            
            .nav-links {
                flex-direction: column;
                width: 100%;
            }
            
            nav a {
                padding: 12px;
                width: 100%;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .page-title h2 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header>
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-dumbbell"></i>
                <h1>GYMPROYECT</h1>
            </div>
            <p class="tagline">Transforma tu cuerpo, transforma tu vida</p>
        </div>
    </header>
    
    <!-- Menú de Navegación -->
    <nav>
        <div class="nav-container">
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Inicio</a>
                <a href="registro.php" class="active"><i class="fas fa-user-plus"></i> Registrar Miembro</a>
                
            </div>
        </div>
    </nav>
    
    <!-- Contenedor Principal -->
    <div class="main-container">
        <div class="page-title">
            <h2>Registro de Nuevo Miembro</h2>
            <p>Complete el formulario a continuación para registrar un nuevo miembro en nuestro gimnasio</p>
        </div>
        
        <div class="form-container">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Información del Miembro</h3>
                <p>Los campos marcados con * son obligatorios</p>
            </div>
            
            <div class="form-body">
                <?php if ($mensaje): ?>
                    <div class="mensaje <?php echo strpos($mensaje, 'Error') !== false ? 'error' : 'success'; ?>">
                        <i class="<?php echo strpos($mensaje, 'Error') !== false ? 'fas fa-exclamation-circle' : 'fas fa-check-circle'; ?>"></i>
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>
                
                <form action="registro.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre" class="required">Nombre</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="nombre" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="apellido" class="required">Apellido</label>
                            <div class="input-group">
                                <i class="fas fa-user"></i>
                                <input type="text" id="apellido" name="apellido" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <div class="input-group">
                                <i class="fas fa-phone"></i>
                                <input type="text" id="telefono" name="telefono" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="required">Correo Electrónico</label>
                            <div class="input-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="membresia_id" class="required">Tipo de Membresía</label>
                            <div class="input-group">
                                <i class="fas fa-id-card"></i>
                                <select id="membresia_id" name="membresia_id" class="form-control" required>
                                    <option value="">Seleccionar Membresía</option>
                                    <?php
                                    // Mostrar las opciones de membresía desde la base de datos
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['tipo'] . " - $" . $row['costo'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay membresías disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="fecha_inicio" class="required">Fecha de Inicio</label>
                            <div class="input-group">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="metodo_pago" class="required">Método de Pago</label>
                        <div class="input-group">
                            <i class="fas fa-credit-card"></i>
                            <select id="metodo_pago" name="metodo_pago" class="form-control" required>
                                <option value="">Seleccionar método de pago</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-user-plus"></i> Registrar Miembro
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Pie de Página -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>GYMPROYECT</h3>
                <p>Somos tu mejor opción para alcanzar un estilo de vida saludable con instalaciones modernas y profesionales capacitados.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <a href="index.php">Inicio</a>
                <a href="registro.php">Registrar Miembro</a>
                
                <a href="ventas.php">Ventas</a>
            </div>
            
            <div class="footer-section">
                <h3>Contacto</h3>
                <p><i class="fas fa-map-marker-alt"></i> Av. Principal #123</p>
                <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                <p><i class="fas fa-envelope"></i> info@gymproyect.com</p>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; 2025 GYMPROYECT. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>