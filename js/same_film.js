document.addEventListener('DOMContentLoaded', function() {
    const tituloInput = document.getElementById('titulo');
    if (tituloInput) {
        tituloInput.addEventListener('blur', function() {
            const titulo = this.value;
            console.log('Verificando título:', titulo); // Para depuración
            if (titulo) {
                fetch('check_title.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ titulo: titulo })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Título Duplicado',
                            text: 'El título de la película ya existe. Por favor, elige otro.',
                        });
                        this.value = ''; // Limpiar el campo de título
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
});