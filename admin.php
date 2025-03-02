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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sweetalert.js"></script>
    <script src="js/admin.js"></script>
    <script src="js/search_movies.js"></script>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <a href="index.php"><h1>MyNetflix - Administración</h1></a>
        <div class="auth-icon">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php" title="Volver a la página anterior">
                    <i class="fas fa-arrow-left" style="font-size: 30px; color: #fff;"></i> <!-- Ícono de volver -->
                </a>
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
            <!-- Ejemplo de botón para activar/desactivar usuario -->
            <button onclick="activateUser(1)">Activar Usuario</button>
            <button onclick="deactivateUser(1)">Desactivar Usuario</button>
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

    <script>
        function activateUser(userId) {
            confirmActivateUser(userId, function(id) {
                // Lógica para activar el usuario
                fetch('activate_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Activado!', 'El usuario ha sido activado.', 'success');
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        function deactivateUser(userId) {
            confirmDeactivateUser(userId, function(id) {
                // Lógica para desactivar el usuario
                fetch('deactivate_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Desactivado!', 'El usuario ha sido desactivado.', 'success');
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        function validateUser(userId) {
            confirmValidateUser(userId, function(id) {
                // Lógica para validar el usuario
                fetch('validate_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Validado!', 'El usuario ha sido validado.', 'success');
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    </script>
</body>
</html>