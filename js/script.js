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
    let isLikedByUser = false;
    let intervalId;

    fetch(`get_movie_info.php?id=${movieId}`)
        .then(response => response.json())
        .then(data => {
            // Actualizar la información de la película en el modal
            document.getElementById('modal-title').innerText = data.titulo;
            document.getElementById('modal-image').src = `./img/peliculas/${data.caratula}`;
            document.getElementById('modal-description').innerText = data.descripcion;
            document.getElementById('modal-year').innerText = `Año: ${data.año}`;
            document.getElementById('modal-duration').innerText = `Duración: ${data.duracion} minutos`;

            const directores = data.directores.join(', ');
            document.getElementById('modal-directors').innerText = `Directores: ${directores}`;

            const actores = data.actores.join(', ');
            document.getElementById('modal-actors').innerText = `Actores: ${actores}`;

            const categorias = data.categorias.join(', ');
            document.getElementById('modal-categories').innerText = `Categorías: ${categorias}`;

            const modalLikeButton = document.getElementById('modal-like-button');
            const modalLikeCount = document.getElementById('modal-like-count');

            modalLikeButton.setAttribute('data-id', movieId);
            modalLikeCount.innerText = data.likes;

            isLikedByUser = data.user_like > 0;
            updateLikeButtonState();

            document.getElementById('movieModal').style.display = 'block';

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

            function updateLikeButtonState() {
                if (isLikedByUser) {
                    modalLikeButton.classList.add('liked');
                    modalLikeButton.classList.remove('unliked');
                } else {
                    modalLikeButton.classList.remove('liked');
                    modalLikeButton.classList.add('unliked');
                }
            }

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

            const savedLikeState = localStorage.getItem(`movie_${movieId}_liked`);
            if (savedLikeState !== null) {
                isLikedByUser = savedLikeState === 'true';
            }
            updateLikeButtonState();

            // Iniciar actualizaciones periódicas con un intervalo más largo
            intervalId = setInterval(updateLikes, 1000); // 10 segundos

            const closeButton = document.querySelector('.close');
            if (closeButton) {
                closeButton.onclick = () => {
                    closeMovieModal();
                    clearInterval(intervalId);
                };
            } else {
                console.error('El botón de cierre no se encontró en el DOM.');
            }
        })
        .catch(error => console.error('Error al obtener la información de la película:', error));
}