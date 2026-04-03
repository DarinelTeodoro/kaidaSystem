//primera vista
$(document).ready(function () {
    $("#products-for-ticket").load('products-list.php', function (response, status, xhr) {
        if (status == "error") {
            $('#products-for-ticket').html(
                `<div class="container-not-found total-center">
                    <div><i class="fi fi-tr-not-found"></i></div>
                    <div><span class="text-not-found">Error al cargar el contenido</span></div>
                </div>`
            );
        }
    });
});

function load_products_tickets() {
    $("#products-for-ticket").load('products-list.php', function (response, status, xhr) {
        if (status == "error") {
            $('#products-for-ticket').html(
                `<div class="container-not-found total-center">
                    <div><i class="fi fi-tr-not-found"></i></div>
                    <div><span class="text-not-found">Error al cargar el contenido</span></div>
                </div>`
            );
        }
    });
}

//Buscador de productos en comanda
(function () {
    const input = document.getElementById('search-product');

    function normalizar(txt) {
        return (txt || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function aplicarFiltro() {
        const q = normalizar(input.value);
        const cont = document.getElementById('products-for-ticket');
        if (!cont) return;

        const secciones = cont.querySelectorAll('.separator-category');
        const carouseles = cont.querySelectorAll('.carrousel-products');

        if (!q) {
            cont.querySelectorAll('.card-product').forEach(c => (c.style.display = ''));
            secciones.forEach(s => (s.style.display = ''));
            carouseles.forEach(c => (c.style.display = ''));
            return;
        }

        cont.querySelectorAll('.card-product').forEach(card => {
            const haystack = normalizar(card.getAttribute('data-search'));
            card.style.display = haystack.includes(q) ? '' : 'none';
        });

        carouseles.forEach(car => {
            const visibles = Array.from(car.querySelectorAll('.card-product'))
                .some(card => card.style.display !== 'none');

            car.style.display = visibles ? '' : 'none';

            const header = car.previousElementSibling;
            if (header && header.classList.contains('separator-category')) {
                header.style.display = visibles ? '' : 'none';
            }
        });
    }

    input.addEventListener('input', aplicarFiltro);
    window.refiltrarProductosComanda = aplicarFiltro;
})();


document.addEventListener('change', (e) => {
    if (e.target?.id === 'type-delivery') {

        const valueoption = e.target.value;

        if (valueoption == 1) {
            document.getElementById('container-cost-delivery').style.display = 'none';
        } else if (valueoption == 2) {
            document.getElementById('container-cost-delivery').style.display = 'block';
        }
    }
});