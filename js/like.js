// Función para manejar el clic en el botón de like
function handleLikeClick() {
    const movieId = this.getAttribute('data-id');
    const likeCountElement = document.getElementById(`like-count-${movieId}`);

    fetch('like.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `movie_id=${movieId}`
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Verificar la respuesta del servidor
        if (data.status === 'success') {
            // Actualizar el contador de likes
            likeCountElement.textContent = data.likes;

            // Cambiar el estado visual del botón
            if (data.action === 'liked') {
                this.classList.add('liked'); // Añadir clase 'liked'
            } else {
                this.classList.remove('liked'); // Quitar clase 'liked'
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

// Función para asignar eventos de like a los botones
function assignLikeEvents() {
    const likeButtons = document.querySelectorAll('.btn-like');
    likeButtons.forEach(button => {
        button.removeEventListener('click', handleLikeClick); // Eliminar eventos anteriores
        button.addEventListener('click', handleLikeClick); // Asignar nuevo evento
    });
}

// Función para actualizar los likes automáticamente
function actualizarLikes() {
    fetch('get_likes.php')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            data.likes.forEach(like => {
                const likeCountElement = document.getElementById(`like-count-${like.pelicula_id}`);
                if (likeCountElement) {
                    likeCountElement.textContent = like.count;
                }

                // Actualizar el estado visual del botón (si el usuario ha dado like)
                const likeButton = document.querySelector(`.btn-like[data-id="${like.pelicula_id}"]`);
                if (likeButton) {
                    if (like.user_like > 0) {
                        likeButton.classList.add('liked');
                    } else {
                        likeButton.classList.remove('liked');
                    }
                }
            });
        } else {
            console.error('Error al obtener likes:', data.message);
        }
    })
    .catch(error => console.error('Error en actualización de likes:', error));
}

// Asignar eventos iniciales al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    assignLikeEvents(); // Asignar eventos de like
    actualizarLikes(); // Actualizar likes al cargar la página

    // Llamar a la función cada 3 segundos para actualizar los likes
    setInterval(actualizarLikes, 3000);
});

// Exportar funciones para reutilización
window.assignLikeEvents = assignLikeEvents;