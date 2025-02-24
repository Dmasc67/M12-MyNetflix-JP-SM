<?php
session_start();
require '../db/conexion.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    exit("Acceso denegado");
}

// Obtener usuarios pendientes
$pendingUsersQuery = "SELECT * FROM usuarios WHERE estado = 'pendiente'";
$pendingUsersResult = $pdo->query($pendingUsersQuery);

// Si no hay usuarios pendientes
if ($pendingUsersResult->rowCount() === 0) {
    echo "<p>No hay solicitudes pendientes.</p>";
    exit();
}

// Construcción de la tabla
echo '<table class="tabla-estilo">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>';

while ($pendingUser = $pendingUsersResult->fetch(PDO::FETCH_ASSOC)) {
    echo '<tr>
            <td>' . htmlspecialchars($pendingUser['id']) . '</td>
            <td>' . htmlspecialchars($pendingUser['nombre']) . '</td>
            <td>' . htmlspecialchars($pendingUser['email']) . '</td>
            <td>
                    <button onclick="manageUser(' . htmlspecialchars($pendingUser['id']) . ', \'validar\')" class="admin-panel button btn-validar">Validar</button>
                    <button onclick="manageUser(' . htmlspecialchars($pendingUser['id']) . ', \'delete\')" class="admin-panel button btn-eliminar">Eliminar</button>
            </td>
          </tr>';
}
echo '</table>';
?>
