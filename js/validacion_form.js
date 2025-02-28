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
                    alert(data.message); // Muestra el mensaje de error en un popup
                } else {
                    // Oculta el formulario
                    registerModal.style.display = 'none';

                    // Muestra un mensaje de éxito
                    alert('Registro exitoso');

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
            let valid = true;
            const errors = {};

            const email = loginForm.querySelector('input[name="email"]').value;

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors['email'] = "El email no es válido.";
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                alert(Object.values(errors).join(' '));
            }
        });
    }
}); 