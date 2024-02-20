document.addEventListener('DOMContentLoaded', function() {
    // Comprobar si hay parámetros de filtro en la URL
    if (window.location.search.indexOf('orderby') > -1 || window.location.search.indexOf('paged') > -1) {
        // Retrasar la ejecución del código de desplazamiento
        setTimeout(function() {
            // Obtener el bloque de productos
            var bloqueProductos = document.querySelector('#productos-loop');

            // Comprobar si el bloque de productos existe
            if (bloqueProductos) {
                // Calcular la posición de desplazamiento con padding
                var offsetTop = bloqueProductos.getBoundingClientRect().top + window.pageYOffset - 40; // Resta la cantidad de padding que quieras, en este caso 100px

                // Desplazarse al bloque de productos con padding
                window.scrollTo({ top: offsetTop, behavior: 'smooth' });
            }
        }, 20); // Retrasar la ejecución en 500 milisegundos
    }
});