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
// Obtener el ID del miembro a editar
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Obtener los datos del miembro
$sql = "SELECT * FROM Miembro WHERE id = $id";
$result = $conn->query($sql);
$miembro = $result->fetch_assoc();
// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $sql = "UPDATE Miembro SET nombre = '$nombre', apellido = '$apellido', telefono = '$telefono', email = '$email' WHERE id = $id";
    if ($conn->query($sql)) {
        echo "<script>alert('Miembro actualizado correctamente.');</script>";
        echo "<script>window.location.href = 'membresia.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar el miembro: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Miembro - GYMPROYECT</title>
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
            max-width: 700px;
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
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(36, 37, 130, 0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 14px 30px;
            margin: 10px;
            background-color: var(--secondary);
            color: white;
            font-size: 1em;
            text-decoration: none;
            border-radius: 50px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(246, 76, 114, 0.4);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .btn i {
            margin-right: 10px;
        }
        
        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: all 0.6s;
        }
        
        .btn:hover:before {
            left: 100%;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(246, 76, 114, 0.5);
        }
        
        .btn-primary {
            background-color: var(--primary);
            box-shadow: 0 4px 15px rgba(36, 37, 130, 0.4);
        }
        
        .btn-primary:hover {
            box-shadow: 0 10px 20px rgba(36, 37, 130, 0.5);
        }
        
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
        }
        
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 40px 0 20px;
            position: relative;
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
        
        /* Responsive */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2em;
            }
            
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
            
            .page-title h2 {
                font-size: 2em;
            }
            
            .form-body {
                padding: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                margin: 5px 0;
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
                <a href="registro.php"><i class="fas fa-user-plus"></i> Registrar Miembro</a>
                
                <a href="membresia.php"><i class="fas fa-users"></i> Lista de Miembros</a>
                <a href="ventas.php"><i class="fas fa-shopping-cart"></i> Ventas</a>
            </div>
        </div>
    </nav>
    
    <!-- Contenedor Principal -->
    <div class="main-container">
        <div class="page-title">
            <h2>Editar Miembro</h2>
            <p>Actualice la información del miembro</p>
        </div>
        
        <div class="form-container">
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h3>Información del Miembro</h3>
                <p>Modifique los datos necesarios y guarde los cambios</p>
            </div>
            
            <div class="form-body">
                <form action="editar_miembro.php?id=<?php echo $id; ?>" method="POST">
                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-user"></i> Nombre:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo $miembro['nombre']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido"><i class="fas fa-user"></i> Apellido:</label>
                        <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo $miembro['apellido']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono"><i class="fas fa-phone"></i> Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo $miembro['telefono']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico:</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $miembro['email']; ?>" required>
                    </div>
                    
                    <div class="btn-group">
                        <a href="membresia.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
                        <button type="submit" class="btn"><i class="fas fa-save"></i> Guardar Cambios</button>
                    </div>
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
                <a href="membresias.php">Membresías</a>
                <a href="membresia.php">Lista de Miembros</a>
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