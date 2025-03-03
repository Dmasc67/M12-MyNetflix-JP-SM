function loadPendingUsers() {
    fetch("ajax/get_pending_users.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("pendingUsers").innerHTML = data;
        })
        .catch(error => console.error("Error al obtener usuarios pendientes:", error));
}

// Cargar usuarios inmediatamente al abrir la página
loadPendingUsers();

// Actualizar cada 5 segundos sin recargar
setInterval(loadPendingUsers, 5000);

function manageUser(userId, action) {
    if (action === 'delete') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas eliminar este usuario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("ajax/delete_user.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire('Eliminado!', 'Usuario eliminado correctamente.', 'success');
                        loadPendingUsers(); // Recargar la lista de usuarios pendientes
                    } else {
                        Swal.fire('Error!', 'Error al eliminar el usuario: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error("Error al eliminar el usuario:", error);
                    Swal.fire('Error!', 'Error al eliminar el usuario.', 'error');
                });
            }
        });
    } else if (action === 'validar') {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¿Deseas validar este usuario?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, validar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("ajax/validate_user.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire('Validado!', 'Usuario validado correctamente.', 'success');
                        loadPendingUsers(); // Recargar la lista de usuarios pendientes
                    } else {
                        Swal.fire('Error!', 'Error al validar el usuario: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error("Error al validar el usuario:", error);
                    Swal.fire('Error!', 'Error al validar el usuario.', 'error');
                });
            }
        });
    }
}

function manageUserStatus(userId, action) {
    let confirmMessage = "";
    if (action === "activate") confirmMessage = "¿Deseas activar este usuario?";
    if (action === "deactivate") confirmMessage = "¿Deseas desactivar este usuario?";
    if (action === "delete") confirmMessage = "¿Estás seguro de eliminar este usuario?";

    if (confirmMessage) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: confirmMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`ajax/manage_user_status.php?id=${userId}&action=${action}`, { method: "GET" })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire('Hecho!', data, 'success');
                        loadUsers(); // Recargar la tabla automáticamente
                    })
                    .catch(error => {
                        console.error("Error al gestionar usuario:", error);
                        Swal.fire('Error!', 'Error al gestionar usuario.', 'error');
                    });
            }
        });
    }
}

function loadUsers() {
    fetch("ajax/get_users.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("usersTable").innerHTML = data;
        })
        .catch(error => console.error("Error al cargar usuarios:", error));
}

// Cargar usuarios al inicio y cada 10 segundos
loadUsers();
setInterval(loadUsers, 5000);