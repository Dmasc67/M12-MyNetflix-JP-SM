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

function openMovieModal(movieId) {
    fetch(`get_movie_info.php?id=${movieId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-title').innerText = data.titulo;
            document.getElementById('modal-image').src = `./img/peliculas/${data.caratula}`;
            document.getElementById('modal-description').innerText = data.descripcion;
            document.getElementById('modal-year').innerText = `Año: ${data.año}`;
            document.getElementById('modal-duration').innerText = `Duración: ${data.duracion} minutos`;
            document.getElementById('modal-likes').innerText = `Likes: ${data.likes}`;

            // Mostrar directores
            const directores = data.directores.join(', ');
            document.getElementById('modal-directors').innerText = `Directores: ${directores}`;

            // Mostrar actores
            const actores = data.actores.join(', ');
            document.getElementById('modal-actors').innerText = `Actores: ${actores}`;

            // Mostrar categorías
            const categorias = data.categorias.join(', ');
            document.getElementById('modal-categories').innerText = `Categorías: ${categorias}`;

            document.getElementById('movieModal').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
        function closeMovieModal() {
            document.getElementById('movieModal').style.display = 'none';
        }
        
        // Cerrar el modal si se hace clic fuera del contenido
        window.onclick = function(event) {
            const modal = document.getElementById('movieModal');
            if (event.target === modal) {
                closeMovieModal();
            }
        }
    }