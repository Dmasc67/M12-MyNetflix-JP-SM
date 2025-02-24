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
        if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
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
                    alert("Usuario eliminado correctamente.");
                    loadPendingUsers(); // Recargar la lista de usuarios pendientes
                } else {
                    alert("Error al eliminar el usuario: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error al eliminar el usuario:", error);
                alert("Error al eliminar el usuario.");
            });
        }
    } else if (action === 'validar') {
        if (confirm("¿Estás seguro de que deseas validar este usuario?")) {
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
                    alert("Usuario validado correctamente.");
                    loadPendingUsers(); // Recargar la lista de usuarios pendientes
                } else {
                    alert("Error al validar el usuario: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error al validar el usuario:", error);
                alert("Error al validar el usuario.");
            });
        }
    }
}

function manageUserStatus(userId, action) {
    let confirmMessage = "";
    if (action === "delete") confirmMessage = "¿Estás seguro de eliminar este usuario?";
    if (action === "deactivate") confirmMessage = "¿Deseas desactivar este usuario?";

    if (!confirmMessage || confirm(confirmMessage)) {
        fetch(`ajax/manage_user_status.php?id=${userId}&action=${action}`, { method: "GET" })
            .then(response => response.text())
            .then(data => {
                alert(data);
                loadUsers(); // Recargar la tabla automáticamente
            })
            .catch(error => console.error("Error al gestionar usuario:", error));
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