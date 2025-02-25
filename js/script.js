function toggleModal() {
    var modal = document.getElementById("loginModal");
    modal.style.display = (modal.style.display === "block") ? "none" : "block";
}


window.onclick = function(event) {
    var modal = document.getElementById("loginModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function closeMovieModal() {
    document.getElementById('movieModal').style.display = 'none';
    clearInterval(intervalId);
}

function openMovieModal(movieId) {
    let isLikedByUser = false; // Variable para guardar el estado del like del usuario

    // Obtener la información de la película desde el servidor
    fetch(`get_movie_info.php?id=${movieId}`)
        .then(response => response.json())
        .then(data => {
            // Actualizar la información de la película en el modal
            document.getElementById('modal-title').innerText = data.titulo;
            document.getElementById('modal-image').src = `./img/peliculas/${data.caratula}`;
            document.getElementById('modal-description').innerText = data.descripcion;
            document.getElementById('modal-year').innerText = `Año: ${data.año}`;
            document.getElementById('modal-duration').innerText = `Duración: ${data.duracion} minutos`;

            // Mostrar directores, actores y categorías
            const directores = data.directores.join(', ');
            document.getElementById('modal-directors').innerText = `Directores: ${directores}`;

            const actores = data.actores.join(', ');
            document.getElementById('modal-actors').innerText = `Actores: ${actores}`;

            const categorias = data.categorias.join(', ');
            document.getElementById('modal-categories').innerText = `Categorías: ${categorias}`;

            // Obtener referencias al botón de like y al contador de likes
            const modalLikeButton = document.getElementById('modal-like-button');
            const modalLikeCount = document.getElementById('modal-like-count');

            // Asignar el ID de la película al botón de like
            modalLikeButton.setAttribute('data-id', movieId);

            // Actualizar el contador de likes
            modalLikeCount.innerText = data.likes;

            // Verificar si el usuario ya dio like y actualizar el estado visual inicial
            isLikedByUser = data.user_like > 0;
            updateLikeButtonState();

            // Mostrar el modal
            document.getElementById('movieModal').style.display = 'block';

            // Manejar el evento de clic en el botón de like
            modalLikeButton.onclick = function () {
                const action = isLikedByUser ? 'unlike' : 'like';

                fetch(`like.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ movieId: movieId, action: action }),
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            modalLikeCount.innerText = result.likes;
                            isLikedByUser = result.action === 'liked';
                            updateLikeButtonState();
                            localStorage.setItem(`movie_${movieId}_liked`, isLikedByUser);
                        }
                    })
                    .catch(error => console.error('Error al dar like:', error));
            };

            // Función para actualizar el estado visual del botón
            function updateLikeButtonState() {
                if (isLikedByUser) {
                    modalLikeButton.classList.add('liked');
                    modalLikeButton.classList.remove('unliked');
                } else {
                    modalLikeButton.classList.remove('liked');
                    modalLikeButton.classList.add('unliked');
                }
            }

            // Función para actualizar los likes automáticamente
            const updateLikes = () => {
                fetch(`get_movie_info.php?id=${movieId}`)
                    .then(response => response.json())
                    .then(data => {
                        modalLikeCount.innerText = data.likes;
                        const prevLikeState = isLikedByUser;
                        isLikedByUser = data.user_like > 0;
                        
                        if (prevLikeState !== isLikedByUser) {
                            updateLikeButtonState();
                            localStorage.setItem(`movie_${movieId}_liked`, isLikedByUser);
                        }
                    })
                    .catch(error => console.error('Error al actualizar likes:', error));
            };

            // Verificar estado guardado en localStorage y aplicarlo
            const savedLikeState = localStorage.getItem(`movie_${movieId}_liked`);
            if (savedLikeState !== null) {
                isLikedByUser = savedLikeState === 'true';
            }
            updateLikeButtonState();

            // Iniciar actualizaciones periódicas
            updateLikes();
            const intervalId = setInterval(updateLikes, 3000);

            // Manejar cierre del modal
            const closeButton = document.querySelector('.close');
            if (closeButton) {
                closeButton.onclick = closeMovieModal;
            } else {
                console.error('El botón de cierre no se encontró en el DOM.');
            }
        })
        .catch(error => console.error('Error al obtener la información de la película:', error));
}