<?php
include './db/conexion.php';
session_start();

// Mostrar mensaje de error si existe
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
        . $_SESSION['error_message'] .
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
        . '<span aria-hidden="true">&times;</span>'
        . '</button></div>';
    unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
}

// Obtener las 5 películas más populares
$top5Query = "SELECT p.titulo, COUNT(l.usuario_id) AS likes, p.caratula 
               FROM peliculas p 
               LEFT JOIN likes l ON p.id = l.pelicula_id 
               GROUP BY p.id 
               ORDER BY likes DESC 
               LIMIT 5";
$top5Result = $pdo->query($top5Query);

// Obtener todas las películas ordenadas por popularidad (número de likes)
$allMoviesQuery = "SELECT p.titulo, COUNT(l.usuario_id) AS likes, p.caratula 
                    FROM peliculas p 
                    LEFT JOIN likes l ON p.id = l.pelicula_id 
                    GROUP BY p.id 
                    ORDER BY likes DESC";
$allMoviesResult = $pdo->query($allMoviesQuery);

// Obtener todas las películas
$moviesQuery = "SELECT p.*, COUNT(l.usuario_id) AS likes, 
                (SELECT COUNT(*) FROM likes WHERE pelicula_id = p.id AND usuario_id = :user_id) AS user_like 
                FROM peliculas p 
                LEFT JOIN likes l ON p.id = l.pelicula_id 
                GROUP BY p.id 
                ORDER BY p.titulo";

// Verificar si el usuario ha iniciado sesión
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$stmt = $pdo->prepare($moviesQuery);
$stmt->execute(['user_id' => $userId]);
$moviesResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filtrar películas por like del usuario
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    if ($filter === 'liked') {
        $moviesQuery = "SELECT p.*, COUNT(l.usuario_id) AS likes 
                        FROM peliculas p 
                        JOIN likes l ON p.id = l.pelicula_id 
                        WHERE l.usuario_id = :user_id 
                        GROUP BY p.id 
                        ORDER BY p.titulo";
    } else {
        $moviesQuery = "SELECT p.*, COUNT(l.usuario_id) AS likes 
                        FROM peliculas p 
                        LEFT JOIN likes l ON p.id = l.pelicula_id 
                        WHERE p.id NOT IN (SELECT pelicula_id FROM likes WHERE usuario_id = :user_id) 
                        GROUP BY p.id 
                        ORDER BY p.titulo";
    }
    $stmt = $pdo->prepare($moviesQuery);
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $moviesResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
    
<!-- Encabezado -->
<div class="header">
    <h1>MyNetflix</h1>
    <div class="auth-icon">
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="admin.php" title="Panel de Administración">
                    <i class="fas fa-tools" style="font-size: 30px; color: #fff;"></i> <!-- Ícono de herramientas -->
                </a>
            <?php endif; ?>    
    <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" title="Cerrar Sesión">
                <i class="fas fa-sign-out-alt" style="font-size: 30px; color: #fff;"></i> <!-- Ícono de logout -->
            </a>
        <?php else: ?>
            <i class="fas fa-user" title="Iniciar Sesión" id="loginIcon" onclick="toggleModal()"></i> <!-- Ícono de login -->
        <?php endif; ?>
    </div>
</div>

<!-- TOP 5 Películas -->
<h2>Top 5</h2>
<div class="top5">
    <div class="row">
        <?php while ($row = $top5Result->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="col">
                <img src="./img/peliculas/<?php echo $row['caratula']; ?>" alt="<?php echo $row['titulo']; ?>">
                <h3><?php echo $row['titulo']; ?></h3>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <h2>Películas Disponibles</h2>

    <h3>Filtros</h3>
    <select id="filter-like">
        <option value="all">Todas</option>
        <option value="liked">Películas que me gustan</option>
        <option value="unliked">Películas que no me gustan</option>
    </select>

    <input type="text" id="search-title" placeholder="Buscar por título">
    <input type="text" id="search-director" placeholder="Buscar por director">
    <input type="text" id="search-category" placeholder="Buscar por categoría">
    <input type="number" id="search-year" placeholder="Buscar por año" min="1900" max="2099">

    <div id="movies-container" class="grid">
        <!-- Aquí se llenarán las películas con AJAX -->
        <?php foreach ($moviesResult as $movie): ?>
    <div class="grid-col">
        <img src="./img/peliculas/<?php echo $movie['caratula']; ?>" alt="<?php echo $movie['titulo']; ?>" data-id="<?php echo $movie['id']; ?>" onclick="openMovieModal(<?php echo $movie['id']; ?>)">
        <h3><?php echo $movie['titulo']; ?></h3>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>
                <button class="btn btn-like <?php echo ($movie['user_like'] > 0) ? 'liked' : ''; ?>" 
                        data-id="<?php echo $movie['id']; ?>">
                    👍<span class="like-count" id="like-count-<?php echo $movie['id']; ?>"><?php echo $movie['likes']; ?></span>
                </button>
            </p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
        </div>
<?php else: ?>
    <h3>Películas Disponibles</h3>
    <div class="grid">
            <?php foreach ($moviesResult as $movie): ?>
                <div class="grid-col">
                    <img src="./img/peliculas/<?php echo $movie['caratula']; ?>" alt="<?php echo $movie['titulo']; ?>" data-id="<?php echo $movie['id']; ?>"> 
                    <h3><?php echo $movie['titulo']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
<?php endif; ?>

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

<!-- Modal de Información de Película -->
<div id="movieModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeMovieModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <img id="modal-image" src="" alt="">
        <p id="modal-description"></p>
        <p id="modal-year"></p>
        <p id="modal-duration"></p>
        <p id="modal-directors"></p>
        <p id="modal-actors"></p>
        <p id="modal-categories"></p>
        <p id="modal-likes"></p>
    </div>
</div>


<script src="js/script.js"></script>
<script src="js/like.js"></script>
<script src="js/ajax.js"></script>
</body>
</html>
