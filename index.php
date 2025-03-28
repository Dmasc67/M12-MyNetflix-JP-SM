<?php
include './db/conexion.php';
session_start();

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

// Mostrar mensaje de error si existe
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
        . $_SESSION['error_message'] .
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
        . '<span aria-hidden="true">&times;</span>'
        . '</button></div>';
    unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
}

// Mostrar el modal de registro si hay errores
$showRegisterModal = isset($errors) && !empty($errors);

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

// Obtener categorías para el filtro
$categoriasQuery = "SELECT * FROM categorias ORDER BY nombre";
$categoriasResult = $pdo->query($categoriasQuery);

// Verificar el estado del usuario y mostrar alertas
if (isset($_SESSION['user_id'])) {
    $userStatus = $_SESSION['user_status'] ?? 'pendiente';
    echo "<script>";
    if ($userStatus === 'pendiente') {
        echo "showPendingValidationAlert();";
    } elseif ($userStatus === 'inactivo') {
        echo "showInactiveUserAlert();";
    }
    echo "</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyNetflix</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/validacion_form.js"></script>
    <script src="js/sweetalert.js"></script>
    <style>
        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: #333;
            padding: 20px;
            border-radius: 5px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .filters-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filters-container .form-control {
            max-width: 200px;
        }
        #clear-filters {
            padding: 8px 15px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        #clear-filters:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    
<!-- Encabezado -->
<div class="header">
    <h1>MyNetflix</h1>
    <div class="auth-icon">
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
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
                <img src="./<?php echo $row['caratula']; ?>" alt="<?php echo $row['titulo']; ?>">
                <h3><?php echo $row['titulo']; ?></h3>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <h2>Películas Disponibles</h2>

    <h3>Filtros</h3>
    <div class="filters-container">
        <select id="filter-like" class="form-control">
            <option value="all">Todas</option>
            <option value="liked">Películas que me gustan</option>
            <option value="unliked">Películas que no me gustan</option>
        </select>

        <input type="text" id="search-title" class="form-control" placeholder="Buscar por título">
        <input type="text" id="search-director" class="form-control" placeholder="Buscar por director">
        <select id="search-category" class="form-control">
            <option value="">Todas las categorías</option>
            <?php while ($categoria = $categoriasResult->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?php echo htmlspecialchars($categoria['nombre']); ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button id="clear-filters" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Limpiar filtros
        </button>
    </div>

    <div id="movies-container" class="grid">
    <!-- Aquí se llenarán las películas con AJAX -->
    <?php foreach ($moviesResult as $movie): ?>
        <div class="grid-col">
            <img src="./<?php echo $movie['caratula']; ?>" alt="<?php echo $movie['titulo']; ?>" data-id="<?php echo $movie['id']; ?>" onclick="openMovieModal(<?php echo $movie['id']; ?>)">
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
                    <img src="./<?php echo $movie['caratula']; ?>" alt="<?php echo $movie['titulo']; ?>" data-id="<?php echo $movie['id']; ?>"> 
                    <h3><?php echo $movie['titulo']; ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
<?php endif; ?>

<!-- Modal de Login -->
<div id="loginModal" class="modal login-modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="toggleModal()">&times;</span>
        <h2>Iniciar Sesión</h2>
        <form id="loginForm" action="process_login.php" method="post">
            <input type="email" name="email" placeholder="Email" >
            <div class="error-message"></div>

            <input type="password" name="password" placeholder="Contraseña" >
            <div class="error-message"></div>

            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes una cuenta? <a href="#" onclick="openRegisterModal()">Regístrate aquí</a></p>
    </div>
</div>

<!-- Modal de Registro -->
<div id="registerModal" class="modal register-modal" style="display: <?php echo $showRegisterModal ? 'block' : 'none'; ?>;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerModal')">&times;</span>
        <h2>Registro</h2>
        <form id="registerForm" action="process_register.php" method="post">
            <input type="text" name="nombre" placeholder="Nombre"  value="<?php echo htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES); ?>" style="margin-left: 0;">
            <div class="error-message"></div>

            <input type="email" name="email" placeholder="Email"  value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>">
            <div class="error-message"></div>

            <input type="password" name="password" placeholder="Contraseña" >
            <div class="error-message"></div>

            <button type="submit">Registrar</button>
        </form>
        <p>¿Ya tienes una cuenta? <a href="#" onclick="openLoginModal()">Inicia sesión aquí</a></p>
    </div>
</div>

<!-- Modal de Información de Película -->
<div id="movieModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeMovieModal()">&times;</span>
        <div class="modal-body">
            <!-- La imagen ahora está primero (izquierda) -->
            <div class="modal-image-container">
                <img id="modal-image" src="" alt="">
            </div>
            <!-- La información ahora está después (derecha) -->
            <div class="modal-info">
                <h2 id="modal-title"></h2>
                <p id="modal-description"></p>
                <p id="modal-year"></p>
                <p id="modal-duration"></p>
                <p id="modal-directors"></p>
                <p id="modal-actors"></p>
                <p id="modal-categories"></p>
            </div>
        </div>
    </div>
</div>

<script src="js/script.js"></script>
<script src="js/like.js"></script>
<script src="js/ajax.js"></script>
<script>
    // Llamar a esta función después de un registro exitoso
    function onRegisterSuccess() {
        showRegistrationAlert();
    }

    // Llamar a esta función después de un inicio de sesión exitoso
    function onLoginSuccess(username) {
        showLoginAlert(username);
    }
</script>
</body>
</html>
