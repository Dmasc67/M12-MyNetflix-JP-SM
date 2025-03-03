document.addEventListener('DOMContentLoaded', function() {
    // Validación de registro
    const registerForm = document.querySelector('#registerForm');
    const registerModal = document.getElementById('registerModal');

    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío inmediato del formulario

            const formData = new FormData(registerForm);

            fetch('process_register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    // Oculta el formulario
                    registerModal.style.display = 'none';

                    // Muestra un mensaje de éxito
                    showRegistrationAlert();

                    // Limpia el formulario
                    registerForm.reset();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Validación de login
    const loginForm = document.querySelector('#loginModal form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío inmediato del formulario

            const formData = new FormData(loginForm);

            fetch('process_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Muestra el SweetAlert de inicio de sesión exitoso
                    showLoginAlert(data.username);

                    // Opcional: Redirigir o cerrar el modal después de un tiempo
                    setTimeout(() => {
                        window.location.reload(); // Recarga la página o redirige a otra página
                    }, 3000);
                } else {
                    // Muestra el SweetAlert de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});

function showPendingValidationAlert() {
    Swal.fire({
        title: 'Validación Pendiente',
        text: 'Tu cuenta está pendiente de validación por un administrador. Por favor, espera a que sea validada.',
        icon: 'info',
        confirmButtonText: 'Entendido',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}

function showEmailNotRegisteredAlert() {
    Swal.fire({
        title: 'Correo No Registrado',
        text: 'El correo ingresado no está registrado en nuestra web.',
        icon: 'error',
        confirmButtonText: 'Entendido'
    });
}

function showInactiveUserAlert() {
    Swal.fire({
        title: 'Usuario Inactivo',
        text: 'Tu cuenta ha sido desactivada por un administrador. Por favor, contacta al soporte si tienes dudas.',
        icon: 'warning',
        confirmButtonText: 'Entendido',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
} 