// Función para abrir/cerrar el modal de login
function toggleModal() {
    var modal = document.getElementById("loginModal");
    modal.style.display = (modal.style.display === "flex") ? "none" : "flex";
}

// Función para cerrar el modal de película
function closeMovieModal() {
    document.getElementById('movieModal').style.display = 'none';
}

// Función para abrir el modal de película con datos dinámicos
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

            // Mostrar el modal de película
            document.getElementById('movieModal').style.display = 'flex';
        })
        .catch(error => console.error('Error al obtener la información de la película:', error));
}

// Cerrar modales al hacer clic fuera del contenido
window.onclick = function(event) {
    var loginModal = document.getElementById("loginModal");
    var movieModal = document.getElementById("movieModal");

    if (event.target == loginModal) {
        loginModal.style.display = "none";
    }
    if (event.target == movieModal) {
        movieModal.style.display = "none";
    }
}

// Asignar eventos de cierre a los botones de cerrar
document.addEventListener('DOMContentLoaded', function() {
    // Cerrar modal de login
    const loginCloseButton = document.querySelector('#loginModal .close');
    if (loginCloseButton) {
        loginCloseButton.onclick = () => {
            document.getElementById('loginModal').style.display = 'none';
        };
    }

    // Cerrar modal de película
    const movieCloseButton = document.querySelector('#movieModal .close');
    if (movieCloseButton) {
        movieCloseButton.onclick = () => {
            closeMovieModal();
        };
    }
});

// Función para abrir un modal específico
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Función para cerrar un modal específico
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Función para abrir el modal de registro y cerrar el de login
function openRegisterModal() {
    closeModal('loginModal'); // Cierra el modal de inicio de sesión
    openModal('registerModal'); // Abre el modal de registro
}

// Función para abrir el modal de login y cerrar el de registro
function openLoginModal() {
    closeModal('registerModal'); // Cierra el modal de registro
    openModal('loginModal'); // Abre el modal de inicio de sesión
}