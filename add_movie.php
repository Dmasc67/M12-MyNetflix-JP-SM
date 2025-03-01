<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirige si no es administrador
    exit();
}

require 'db/conexion.php';

// Obtener categorías, directores y actores para los select
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
$directores = $pdo->query("SELECT * FROM directores")->fetchAll(PDO::FETCH_ASSOC);
$actores = $pdo->query("SELECT * FROM actores")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $año = $_POST['año'];
    $duracion = $_POST['duracion'];
    $categoria_id = $_POST['categoria'];
    $director_id = $_POST['director'];
    $actor_id = $_POST['actor'];

    // Manejo de la carátula (subida de archivos)
    if (isset($_FILES['caratula']) && $_FILES['caratula']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "./img/peliculas/"; // Carpeta donde se guardarán las imágenes
        $target_file = $target_dir . basename($_FILES["caratula"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validar el tipo de archivo
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Solo se permiten archivos JPG, JPEG, PNG y GIF.");
        }

        // Validar el tamaño del archivo (por ejemplo, 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB
        if ($_FILES["caratula"]["size"] > $max_size) {
            die("Error: El archivo es demasiado grande. El tamaño máximo permitido es 5MB.");
        }

        // Renombrar el archivo para evitar conflictos
        $new_file_name = uniqid() . "." . $imageFileType; // Genera un nombre único
        $target_file = $target_dir . $new_file_name;

        // Mover el archivo subido a la carpeta de destino
        if (move_uploaded_file($_FILES["caratula"]["tmp_name"], $target_file)) {
            $caratula = $target_file; // Guarda la ruta del archivo en la base de datos
        } else {
            die("Error: Hubo un problema al subir el archivo.");
        }
    } else {
        die("Error: No se ha subido ningún archivo o hubo un error en la subida.");
    }

    // Insertar la película en la tabla `peliculas`
    $query = "INSERT INTO peliculas (titulo, descripcion, año, duracion, caratula) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$titulo, $descripcion, $año, $duracion, $caratula]);

    // Obtener el ID de la película recién insertada
    $pelicula_id = $pdo->lastInsertId();

    // Insertar relaciones en las tablas `pelicula_categoria`, `pelicula_director` y `pelicula_actor`
    $stmt_categoria = $pdo->prepare("INSERT INTO pelicula_categoria (pelicula_id, categoria_id) VALUES (?, ?)");
    $stmt_categoria->execute([$pelicula_id, $categoria_id]);

    $stmt_director = $pdo->prepare("INSERT INTO pelicula_director (pelicula_id, director_id) VALUES (?, ?)");
    $stmt_director->execute([$pelicula_id, $director_id]);

    $stmt_actor = $pdo->prepare("INSERT INTO pelicula_actor (pelicula_id, actor_id) VALUES (?, ?)");
    $stmt_actor->execute([$pelicula_id, $actor_id]);

    header("Location: admin.php"); // Redirige de vuelta al panel de administración
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Película</title>
    <div class="header">
    <link rel="stylesheet" href="css/stylesadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>MyNetflix - Añadir Película</h1>
    <div class="auth-icon">
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="admin.php" title="Volver a Administración">
                <i class="fas fa-arrow-left" style="font-size: 30px; color: #fff;"></i> <!-- Ícono de volver atrás -->
            </a>
        <?php endif; ?>
    </div>
    </div>

    <h2>Añadir Nueva Película</h2>
    <form action="add_movie.php" method="post" enctype="multipart/form-data" class="container mt-4">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="año">Año:</label>
            <input type="number" name="año" id="año" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="" disabled selected>Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>">
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="director">Director:</label>
            <select name="director" id="director" class="form-control" required>
                <option value="" disabled selected>Seleccione un director</option>
                <?php foreach ($directores as $director): ?>
                    <option value="<?php echo $director['id']; ?>">
                        <?php echo htmlspecialchars($director['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="actor">Actor:</label>
            <select name="actor" id="actor" class="form-control" required>
                <option value="" disabled selected>Seleccione un actor</option>
                <?php foreach ($actores as $actor): ?>
                    <option value="<?php echo $actor['id']; ?>">
                        <?php echo htmlspecialchars($actor['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="duracion">Duración (minutos):</label>
            <input type="number" name="duracion" id="duracion" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="caratula">Carátula:</label>
            <input type="file" name="caratula" id="caratula" class="form-control" accept=".jpg,.jpeg,.png,.gif" required>
        </div>

        <button type="submit" class="btn btn-primary">Añadir Película</button>
    </form>
    <br>
</body>
</html>