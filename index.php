<?php
include './db/conexion.php';

// Obtener las 5 películas más populares
$top5Query = "SELECT p.titulo, COUNT(l.usuario_id) AS likes, p.caratula 
               FROM peliculas p 
               LEFT JOIN likes l ON p.id = l.pelicula_id 
               GROUP BY p.id 
               ORDER BY likes DESC 
               LIMIT 5";
$top5Result = $pdo->query($top5Query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyNetflix</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
<!-- TOP 5 Películas -->
<div class="header">
    <h1>MyNetflix</h1>
    <div class="auth-icon">
        <i class="fas fa-user" title="Iniciar Sesión" id="loginIcon" onclick="toggleModal()"></i>
    </div>
</div>
    
<h2>Top 5</h2>
<div class="top5">
    <div class="row">
        <?php while ($row = $top5Result->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col">
                <img src="./img/top5/<?php echo $row['caratula']; ?>" alt="<?php echo $row['titulo']; ?>">
                <h3><?php echo $row['titulo']; ?></h3>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Grid de Películas Disponibles -->
<div class="grid-peliculas">
    <h2>Películas</h2>
    <div class="row">
        <?php
        $peliculasQuery = "SELECT * FROM peliculas ORDER BY (SELECT COUNT(*) FROM likes WHERE pelicula_id = peliculas.id) DESC";
        $peliculasResult = $pdo->query($peliculasQuery);
        while ($pelicula = $peliculasResult->fetch(PDO::FETCH_ASSOC)):
        ?>
            <div class="col">
                <img src="./img/peliculas/<?php echo $pelicula['caratula']; ?>" alt="<?php echo $pelicula['titulo']; ?>">
                <h3><?php echo $pelicula['titulo']; ?></h3>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal de Login -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="toggleModal()">&times;</span>
        <h2>Iniciar Sesión</h2>
        <form action="process_login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>
</div>
<script src="js/script.js"></script>
</body>
</html>
