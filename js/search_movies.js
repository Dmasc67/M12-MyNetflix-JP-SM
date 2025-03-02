document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector("input[name='search']");
    const resultsContainer = document.getElementById("moviesTable");

    searchInput.addEventListener("keyup", function () {
        let searchTerm = searchInput.value.trim();

        fetch(`ajax/search_movies.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
                attachDeleteHandlers(); // Re-attach delete handlers after updating the DOM
            })
            .catch(error => console.error("Error al buscar películas:", error));
    });

    function attachDeleteHandlers() {
        const deleteButtons = document.querySelectorAll(".btn-eliminar");
        deleteButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault();
                const movieId = this.dataset.id;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Deseas eliminar esta película?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("ajax/delete_movie.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `id=${movieId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                Swal.fire('Eliminado!', 'La película ha sido eliminada.', 'success');
                                loadMovies(); // Reload movies after deletion
                            } else {
                                Swal.fire('Error!', 'Error al eliminar la película: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error("Error al eliminar la película:", error);
                            Swal.fire('Error!', 'Error al eliminar la película.', 'error');
                        });
                    }
                });
            });
        });
    }

    function loadMovies() {
        fetch("ajax/search_movies.php")
            .then(response => response.text())
            .then(data => {
                resultsContainer.innerHTML = data;
                attachDeleteHandlers(); // Attach delete handlers after loading movies
            })
            .catch(error => console.error("Error al cargar películas:", error));
    }

    loadMovies(); // Cargar todas las películas al inicio
});