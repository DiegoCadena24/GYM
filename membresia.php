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

// Procesar la eliminación de un miembro
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']); // Sanitizar el ID
    $sql = "DELETE FROM Miembro WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Miembro eliminado correctamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al eliminar el miembro: " . $conn->error;
        $tipo_mensaje = "error";
    }
}

// Obtener la lista de miembros
$sql = "SELECT m.id, m.nombre, m.apellido, m.telefono, m.email, 
               m.fecha_registro, mem.tipo AS tipo_membresia, mem.costo, mem.duracion_dias,
               DATEDIFF(DATE_ADD(m.fecha_registro, INTERVAL mem.duracion_dias DAY), CURDATE()) AS dias_restantes
        FROM Miembro m
        JOIN Membresia mem ON m.membresia_id = mem.id";

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si la consulta se ejecutó correctamente
if (!$result) {
    $mensaje = "Error en la consulta: " . $conn->error;
    $tipo_mensaje = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Miembros - GYMPROYECT</title>
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
        
        .tabla-container {
            width: 100%;
            margin: 0 auto 60px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .tabla-header {
            background: linear-gradient(to right, var(--primary), #553d9a);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }
        
        .tabla-header h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8em;
            margin: 0;
            font-weight: 600;
        }
        
        .tabla-header p {
            margin-top: 5px;
            opacity: 0.9;
        }
        
        .tabla-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .tabla-body {
            padding: 30px;
            overflow-x: auto;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background-color: var(--primary);
            color: white;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 1px;
        }
        
        tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .dias-restantes {
            font-weight: bold;
        }
        
        .dias-restantes.vencida {
            color: var(--secondary);
        }
        
        .btn-editar, .btn-eliminar {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 5px;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 0.85em;
            text-transform: uppercase;
            text-decoration: none;
            margin-right: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-editar {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-editar:hover {
            background-color: #1d1d6b;
            transform: translateY(-2px);
        }
        
        .btn-eliminar {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-eliminar:hover {
            background-color: #e13c60;
            transform: translateY(-2px);
        }
        
        .no-members {
            text-align: center;
            padding: 30px;
            font-size: 1.2em;
            color: #666;
            font-style: italic;
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
            
            .page-title h2 {
                font-size: 2em;
            }
            
            .tabla-body {
                padding: 15px;
            }
            
            th, td {
                padding: 10px;
                font-size: 0.9em;
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
                
                <a href="membresia.php" class="active"><i class="fas fa-users"></i> Lista de Miembros</a>
                <a href="ventas.php"><i class="fas fa-shopping-cart"></i> Ventas</a>
            </div>
        </div>
    </nav>
    
    <!-- Contenedor Principal -->
    <div class="main-container">
        <div class="page-title">
            <h2>Lista de Miembros</h2>
            <p>Gestione los miembros registrados en nuestro gimnasio</p>
        </div>
        
        <div class="tabla-container">
            <div class="tabla-header">
                <div class="tabla-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Información de Miembros</h3>
                <p>Aquí puede visualizar, editar o eliminar miembros</p>
            </div>
            
            <div class="tabla-body">
                <?php if (isset($mensaje)): ?>
                    <div class="mensaje <?php echo $tipo_mensaje; ?>">
                        <i class="<?php echo $tipo_mensaje === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle'; ?>"></i>
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Membresía</th>
                            <th>Costo</th>
                            <th>Días Restantes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $dias_restantes = $row['dias_restantes'];
                                $clase_dias = ($dias_restantes < 0) ? "dias-restantes vencida" : "dias-restantes";
                                $dias_restantes_texto = ($dias_restantes < 0) ? "Vencida" : $dias_restantes . " días";
                            
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['nombre']}</td>
                                        <td>{$row['apellido']}</td>
                                        <td>{$row['telefono']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['tipo_membresia']}</td>
                                        <td>$ {$row['costo']}</td>
                                        <td class='{$clase_dias}'>{$dias_restantes_texto}</td>
                                        <td>
                                            <a href='editar_miembro.php?id={$row['id']}' class='btn-editar'><i class='fas fa-edit'></i> Editar</a>
                                            <a href='membresia.php?eliminar={$row['id']}' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de eliminar este miembro?\");'><i class='fas fa-trash'></i> Eliminar</a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='no-members'><i class='fas fa-info-circle'></i> No hay miembros registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
                <a href="membresia.php">Membresías</a>
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