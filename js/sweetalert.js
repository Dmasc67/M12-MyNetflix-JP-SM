// Función para mostrar un SweetAlert al registrarse
function showRegistrationAlert() {
    Swal.fire({
        icon: 'success',
        title: 'Registro exitoso',
        text: 'Te has registrado, espera a ser validado.',
        confirmButtonText: 'Aceptar'
    });
}

// Función para mostrar un SweetAlert al iniciar sesión
function showLoginAlert(username) {
    Swal.fire({
        icon: 'success',
        title: 'Inicio de sesión exitoso',
        text: `Bienvenido/a, ${username}!`,
        confirmButtonText: 'Aceptar'
    });
}

// Función para confirmar la activación de un usuario
function confirmActivateUser(userId, callback) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Quieres activar este usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            callback(userId);
        }
    });
}

// Función para confirmar la desactivación de un usuario
function confirmDeactivateUser(userId, callback) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Quieres desactivar este usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            callback(userId);
        }
    });
}

// Función para confirmar la validación de un usuario
function confirmValidateUser(userId, callback) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Quieres validar este usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, validar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            callback(userId);
        }
    });
}
