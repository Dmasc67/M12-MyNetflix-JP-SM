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
                console.log("Botón eliminar clickeado, ID de película:", movieId);

                fetch("ajax/delete_movie.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${movieId}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Respuesta del servidor:", data);
                    if (data.status === "success") {
                        loadMovies(); // Reload movies after deletion
                    } else {
                        console.error("Error al eliminar la película:", data.message);
                    }
                })
                .catch(error => {
                    console.error("Error al eliminar la película:", error);
                    console.log("Error completo:", error);
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