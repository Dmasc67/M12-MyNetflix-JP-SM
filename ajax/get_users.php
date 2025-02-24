<?php
require '../db/conexion.php';

$usersQuery = "SELECT * FROM usuarios WHERE estado = 'activo' OR estado = 'inactivo'";
$usersResult = $pdo->query($usersQuery);
?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Estado</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    <?php while ($user = $usersResult->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['nombre']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['estado']; ?></td>
            <td><?php echo $user['rol']; ?></td>
            <td>
                <button onclick="manageUserStatus(<?php echo htmlspecialchars($user['id']); ?>, 'activate')" class="admin-panel button btn-activar" >Activar</button>
                <button onclick="manageUserStatus(<?php echo htmlspecialchars($user['id']); ?>, 'deactivate')" class="admin-panel button btn-desactivar">Desactivar</button>
                <button onclick="manageUserStatus(<?php echo htmlspecialchars($user['id']); ?>, 'delete')" class="admin-panel button btn-eliminar">Eliminar</button>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
