<?php
session_start();
require 'db/conexion.php';

// Verificar si se ha enviado el ID de la película
if (isset($_GET['id'])) {
    $movieId = $_GET['id'];

    // Obtener todas las categorías de la base de datos
    $stmtCategorias = $pdo->prepare("SELECT * FROM categorias");
    $stmtCategorias->execute();
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todos los actores de la base de datos
    $stmtActores = $pdo->prepare("SELECT * FROM actores");
    $stmtActores->execute();
    $actores = $stmtActores->fetchAll(PDO::FETCH_ASSOC);

    // Obtener todos los directores de la base de datos
    $stmtDirectores = $pdo->prepare("SELECT * FROM directores");
    $stmtDirectores->execute();
    $directores = $stmtDirectores->fetchAll(PDO::FETCH_ASSOC);

    // Obtener la película de la base de datos junto con sus categorías y actores
    $stmt = $pdo->prepare("
        SELECT p.*, GROUP_CONCAT(c.id) AS categoria_ids, GROUP_CONCAT(c.nombre) AS categorias, 
               GROUP_CONCAT(a.id) AS actor_ids, GROUP_CONCAT(a.nombre) AS actores, 
               GROUP_CONCAT(d.id) AS director_ids, GROUP_CONCAT(d.nombre) AS directores 
        FROM peliculas p 
        LEFT JOIN pelicula_categoria pc ON p.id = pc.pelicula_id 
        LEFT JOIN categorias c ON pc.categoria_id = c.id 
        LEFT JOIN pelicula_actor pa ON p.id = pa.pelicula_id 
        LEFT JOIN actores a ON pa.actor_id = a.id 
        LEFT JOIN pelicula_director pd ON p.id = pd.pelicula_id 
        LEFT JOIN directores d ON pd.director_id = d.id 
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$movieId]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si la película existe
    if (!$movie) {
        die("Película no encontrada.");
    }
} else {
    die("ID de película no proporcionado.");
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar otros campos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $año = $_POST['año'];
    $duracion = $_POST['duracion'];
    $categoria = $_POST['categoria'];
    $director = $_POST['director'];
    $actor = $_POST['actor'];

    // Manejo de la carátula
    if (isset($_FILES['caratula']) && $_FILES['caratula']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "./img/peliculas/"; // Carpeta donde se guardarán las imágenes
        $fileTmpPath = $_FILES['caratula']['tmp_name'];
        $fileName = $_FILES['caratula']['name'];
        $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

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
        if (move_uploaded_file($fileTmpPath, $target_file)) {
            // Actualizar la película
            $updateQuery = "UPDATE peliculas SET titulo = ?, descripcion = ?, año = ?, duracion = ?, caratula = ? WHERE id = ?";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([$titulo, $descripcion, $año, $duracion, $target_file, $movieId]);

            // Actualizar las relaciones en las tablas intermedias
            // 1. Eliminar las relaciones antiguas
            $pdo->prepare("DELETE FROM pelicula_categoria WHERE pelicula_id = ?")->execute([$movieId]);
            $pdo->prepare("DELETE FROM pelicula_director WHERE pelicula_id = ?")->execute([$movieId]);
            $pdo->prepare("DELETE FROM pelicula_actor WHERE pelicula_id = ?")->execute([$movieId]);

            // 2. Insertar las nuevas relaciones
            $pdo->prepare("INSERT INTO pelicula_categoria (pelicula_id, categoria_id) VALUES (?, ?)")->execute([$movieId, $categoria]);
            $pdo->prepare("INSERT INTO pelicula_director (pelicula_id, director_id) VALUES (?, ?)")->execute([$movieId, $director]);
            $pdo->prepare("INSERT INTO pelicula_actor (pelicula_id, actor_id) VALUES (?, ?)")->execute([$movieId, $actor]);

            // Redirigir a admin.php después de la actualización
            header("Location: admin.php");
            exit(); // Asegurarse de que no se ejecute más código después de la redirección
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "Error en la carga del archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Película</title>
    <div class="header">
    <link rel="stylesheet" href="css/stylesadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<h1>MyNetflix - Modificar Película</h1>
    <div class="auth-icon">
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="admin.php" title="Volver a Administración">
                    <i class="fas fa-arrow-left" style="font-size: 30px; color: #fff;"></i> <!-- Ícono de volver atrás -->
                </a>
            <?php endif; ?>
    </div>
</div>

    <h2>Modificar Película - <?php echo $movie['titulo']; ?></h2>
    <form action="edit_movie.php?id=<?php echo $movieId; ?>" method="post" enctype="multipart/form-data" class="container mt-4">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?php echo $movie['titulo']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control" value="<?php echo $movie['descripcion']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="año">Año:</label>
            <input type="number" name="año" id="año" class="form-control" value="<?php echo $movie['año']; ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="" disabled>Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo in_array($categoria['id'], explode(',', $movie['categoria_ids'])) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="director">Director:</label>
            <select name="director" id="director" class="form-control" required>
                <option value="" disabled>Seleccione un director</option>
                <?php foreach ($directores as $director): ?>
                    <option value="<?php echo $director['id']; ?>" <?php echo in_array($director['id'], explode(',', $movie['director_ids'])) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($director['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="actor">Actor:</label>
            <select name="actor" id="actor" class="form-control" required>
                <option value="" disabled>Seleccione un actor</option>
                <?php foreach ($actores as $actor): ?>
                    <option value="<?php echo $actor['id']; ?>" <?php echo in_array($actor['id'], explode(',', $movie['actor_ids'])) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($actor['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="duracion">Duración (minutos):</label>
            <input type="number" name="duracion" id="duracion" class="form-control" value="<?php echo $movie['duracion']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="caratula">Carátula:</label>
            <input type="file" name="caratula" id="caratula" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Película</button>
    </form>
    </br>
</body>
</html> 