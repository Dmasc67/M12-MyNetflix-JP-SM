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

            document.getElementById('movieModal').style.display = 'block';

            const closeButton = document.querySelector('.close');
            if (closeButton) {
                closeButton.onclick = () => {
                    closeMovieModal();
                };
            } else {
                console.error('El botón de cierre no se encontró en el DOM.');
            }
        })
        .catch(error => console.error('Error al obtener la información de la película:', error));
}