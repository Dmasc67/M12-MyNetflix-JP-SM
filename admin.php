<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirige si no es administrador
    exit();
}

// Conexión a la base de datos
require 'db/conexion.php';

// Obtener películas
$moviesQuery = "SELECT p.*, COUNT(l.usuario_id) AS likes 
                FROM peliculas p 
                LEFT JOIN likes l ON p.id = l.pelicula_id 
                GROUP BY p.id 
                ORDER BY p.titulo";
$moviesResult = $pdo->query($moviesQuery);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="css/stylesadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <a href="index.php"><h1>MyNetflix - Administración</h1></a>
        <div class="auth-icon">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" title="Cerrar Sesión">
                    <i class="fas fa-sign-out-alt" style="font-size: 30px; color: #fff;"></i> <!-- Ícono de logout -->
                </a>
                <a href="add_movie.php" title="Añadir Película">
                    <i class="fas fa-plus-circle" style="font-size: 30px; color: #fff;"></i>
                </a>
            <?php else: ?>
                <i class="fas fa-user" title="Iniciar Sesión" id="loginIcon" onclick="toggleModal()"></i> <!-- Ícono de login -->
            <?php endif; ?>
        </div>
    </div>

    <!-- Panel de Administración -->
    <div class="admin-panel">
        <h1>Panel de Administración</h1>

        <!-- Solicitudes de Registro -->
        <h2>Solicitudes de Registro</h2>
        <div id="pendingUsers">
            <!-- Aquí se cargarán las solicitudes de registro -->
        </div>

        <!-- Usuarios -->
        <h2>Usuarios</h2>
        <div id="usersTable">
            <!-- Aquí se cargará la tabla de usuarios -->
        </div>

        <!-- Buscar Películas -->
        <h2>Buscar Películas</h2>
        <input type="text" name="search" placeholder="Buscar por título">

        <!-- Catálogo de Películas -->
        <h2>Catálogo de Películas</h2>
        <div id="moviesTable">
            <!-- Aquí se cargará la tabla de películas -->
        </div>
    </div>

    <script src="js/search_movies.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>