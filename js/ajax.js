document.addEventListener("DOMContentLoaded", function () {
    // Filtro por like / no like
    document.querySelector("#filter-like").addEventListener("change", function () {
        cargarPeliculas();
    });

    // Buscador avanzado con múltiples términos
    document.querySelector("#search-title").addEventListener("keyup", function () {
        cargarPeliculas();
    });

    document.querySelector("#search-director").addEventListener("keyup", function () {
        cargarPeliculas();
    });

    document.querySelector("#search-category").addEventListener("keyup", function () {
        cargarPeliculas();
    });
});

function cargarPeliculas() {
    let filtroLike = document.querySelector("#filter-like").value;
    let busquedaTitulo = document.querySelector("#search-title").value;
    let busquedaDirector = document.querySelector("#search-director").value;
    let busquedaCategoria = document.querySelector("#search-category").value;

    let formData = new FormData();
    formData.append("filter", filtroLike);
    formData.append("search_title", busquedaTitulo);
    formData.append("search_director", busquedaDirector);
    formData.append("search_category", busquedaCategoria);

    fetch("ajax/filtrar_peliculas.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Error en la respuesta del servidor");
        }
        return response.json();
    })
    .then(data => {
        let contenedor = document.querySelector("#movies-container");
        contenedor.innerHTML = "";
        data.forEach(pelicula => {
            let peliculaHTML = `
                <div class="grid-col">
                    <img src="./${pelicula.caratula}" alt="${pelicula.titulo}">
                    <h3>${pelicula.titulo}</h3>
                    <p>
                        <button class="btn btn-like ${pelicula.user_like ? 'liked' : ''}" data-id="${pelicula.id}">
                            👍<span class="like-count" id="like-count-${pelicula.id}">${pelicula.likes}</span>
                        </button>
                    </p>
                </div>`;
            contenedor.innerHTML += peliculaHTML;
        });
                // Reasignar eventos después de actualizar el contenido
                assignLikeEvents(); // Reasignar eventos de "like"
                assignModalEvents(); // Reasignar eventos del modal
    })
    .catch(error => {
        console.error("Error en AJAX:", error);
        alert("Hubo un error al cargar las películas. Por favor, inténtalo de nuevo.");
    });
}