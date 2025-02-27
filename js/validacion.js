document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        let valid = true;
        const errors = {};

        // Validar título
        const titulo = document.getElementById('titulo').value;
        if (titulo.length > 100) {
            errors['titulo'] = "El título no debe exceder los 100 caracteres.";
            valid = false;
        }

        // Validar descripción
        const descripcion = document.getElementById('descripcion').value;
        if (descripcion.length > 240) {
            errors['descripcion'] = "La descripción no debe exceder los 240 caracteres.";
            valid = false;
        }

        // Validar año
        const año = document.getElementById('año').value;
        if (!/^\d{4}$/.test(año)) {
            errors['año'] = "El año debe ser un número de 4 dígitos.";
            valid = false;
        }

        // Validar duración
        const duracion = document.getElementById('duracion').value;
        if (!/^\d{1,3}$/.test(duracion)) {
            errors['duracion'] = "La duración debe ser un número de hasta 3 dígitos.";
            valid = false;
        }

        // Mostrar errores
        for (const field in errors) {
            const errorElement = document.getElementById(field + '-error');
            errorElement.textContent = errors[field];
            errorElement.style.color = 'red';
        }

        if (!valid) {
            event.preventDefault();
        }
    });
});
