<?php
include 'init.php'; // Iniciar sesión
include 'conexion.php'; // Conexión a la base de datos
include 'verificar_sesion.php'; // Verificar si el usuario está logueado

// Verificar si hay usuario logueado
verificarSesion();

// Obtener información del usuario
$usuario_id = $_SESSION['usuario_id'];
$query = "SELECT nombre FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$nombre_usuario = $usuario['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a GYMPROYECT</title>
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
        
        .user-menu {
            display: flex;
            align-items: center;
        }
        
        .user-menu .user-info {
            color: white;
            margin-right: 15px;
            font-family: 'Montserrat', sans-serif;
        }
        
        .user-menu .logout-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .user-menu .logout-btn:hover {
            background-color: #e43c64;
            transform: translateY(-2px);
        }
        
        .main-container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/api/placeholder/1200/600');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 40px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 40px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .hero-section h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3em;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
        }
        
        .hero-section p {
            font-size: 1.2em;
            max-width: 700px;
            margin: 0 auto 30px;
            line-height: 1.8;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 3em;
            color: var(--secondary);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5em;
            margin-bottom: 15px;
            color: var(--dark);
        }
        
        .button-container {
            text-align: center;
            margin: 40px 0;
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
        
        .btn-green {
            background-color: var(--accent);
            box-shadow: 0 4px 15px rgba(46, 184, 109, 0.4);
        }
        
        .btn-green:hover {
            box-shadow: 0 10px 20px rgba(46, 184, 109, 0.5);
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
            
            .hero-section {
                padding: 60px 20px;
            }
            
            .hero-section h2 {
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
                <a href="index.php" class="active"><i class="fas fa-home"></i> Inicio</a>
                <a href="registro.php"><i class="fas fa-user-plus"></i> Registrar Miembro</a>
                <a href="membresia.php"><i class="fas fa-id-card"></i> Membresia</a>
                <a href="ventas.php"><i class="fas fa-shopping-cart"></i> Ventas</a>
            </div>
            <div class="user-menu">
                <div class="user-info">
                    <i class="fas fa-user"></i> <?php echo $nombre_usuario; ?>
                </div>
                <a href="cerrar_sesion.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    
    <!-- Contenedor Principal -->
    <div class="main-container">
        <!-- Sección Hero -->
        <div class="hero-section">
            <h2>Bienvenido, <?php echo $nombre_usuario; ?></h2>
            <p>En GYMPROYECT, te ayudamos a alcanzar tus metas fitness con rutinas personalizadas, dietas equilibradas y seguimiento de tu progreso. ¡Comienza tu transformación hoy mismo!</p>
            <a href="rutinas.php" class="btn"><i class="fas fa-running"></i> Ver Rutinas</a>
        </div>
        
        <!-- Características -->
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-running"></i>
                </div>
                <h3>Rutinas Personalizadas</h3>
                <p>Accede a rutinas de entrenamiento diseñadas específicamente para tus objetivos y nivel de condición física.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Dietas Equilibradas</h3>
                <p>Descubre planes de alimentación saludables que complementan tu entrenamiento y te ayudan a alcanzar tus metas.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Seguimiento de Progreso</h3>
                <p>Registra y visualiza tu progreso con gráficos y estadísticas que te motivarán a seguir adelante.</p>
            </div>
        </div>
        
        <!-- Botones de Acción -->
        <div class="button-container">
            <a href="rutinas.php" class="btn btn-primary"><i class="fas fa-running"></i> Ver Rutinas</a>
            <a href="dietas.php" class="btn"><i class="fas fa-utensils"></i> Ver Dietas</a>
            <a href="progreso.php" class="btn btn-green"><i class="fas fa-chart-line"></i> Ver Progreso</a>
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
                <a href="rutinas.php">Rutinas</a>
                <a href="dietas.php">Dietas</a>
                <a href="progreso.php">Progreso</a>
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