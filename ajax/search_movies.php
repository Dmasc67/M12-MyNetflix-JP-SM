<?php
require '../db/conexion.php';

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT p.*, COUNT(l.usuario_id) AS likes, GROUP_CONCAT(c.nombre) AS categorias, GROUP_CONCAT(d.nombre) AS directores 
          FROM peliculas p 
          LEFT JOIN likes l ON p.id = l.pelicula_id 
          LEFT JOIN pelicula_categoria pc ON p.id = pc.pelicula_id 
          LEFT JOIN categorias c ON pc.categoria_id = c.id 
          LEFT JOIN pelicula_director pd ON p.id = pd.pelicula_id 
          LEFT JOIN directores d ON pd.director_id = d.id 
          WHERE p.titulo LIKE :search 
          GROUP BY p.id 
          ORDER BY p.titulo";

$stmt = $pdo->prepare($query);
$stmt->execute(['search' => '%' . $searchTerm . '%']);
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Año</th>
        <th>Categoría</th>
        <th>Director</th>
        <th>Duración (minutos)</th>
        <th>Acciones</th>
    </tr>
    <?php if (count($movies) > 0): ?>
        <?php foreach ($movies as $movie): ?>
            <tr>
                <td><?php echo htmlspecialchars($movie['titulo']); ?></td>
                <td><?php echo htmlspecialchars($movie['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($movie['año']); ?></td>
                <td><?php echo htmlspecialchars($movie['categorias']); ?></td>
                <td><?php echo htmlspecialchars($movie['directores']); ?></td>
                <td><?php echo htmlspecialchars($movie['duracion']); ?></td>
                <td style="display: flex; gap: 10px; justify-content: center;">
                    <button onclick="window.location.href='edit_movie.php?id=<?php echo $movie['id']; ?>'" class="admin-panel button btn-modificar">Modificar</button>
                    <button class="admin-panel button btn-eliminar" data-id="<?php echo $movie['id']; ?>">Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">No se encontraron resultados.</td></tr>
    <?php endif; ?>
</table>
