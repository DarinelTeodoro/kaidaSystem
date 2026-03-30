//primera vista
$(document).ready(function () {
    $("#container-content-section").load('categories/menu-categories.php', function (response, status, xhr) {
        if (status == "success") {
            $("#content-list").load('categories/categories-list.php', function (response, status, xhr) {
                if (status == "error") {
                    $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
                }
            });
        } else {
            $('#container-content-section').html(
                `<div class="container-not-found total-center">
                    <div><i class="fi fi-tr-not-found"></i></div>
                    <div><span class="text-not-found">Error al cargar el contenido</span></div>
                </div>`
            );
        }
    });
});

//Cambiar vista seccion
document.querySelectorAll('.btn-section-menu').forEach(button => {
    button.addEventListener('click', function () {
        document.querySelectorAll('.btn-section-menu').forEach(btn => btn.disabled = false);
        this.disabled = true;
        let path = this.dataset.path;
        let list = this.dataset.list;

        // Mostrar loading
        $('#container-content-section').html(
            `<div class="total-center">
                <div class="loading">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>`
        );

        $('#container-content-section').load(path, function (response, status, xhr) {
            if (status == "success") {
                if (list) {
                    $("#content-list").load(list, function (response, status, xhr) {
                        if (status == "error") {
                            $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
                        }
                    });
                }
            } else {
                $('#container-content-section').html(
                    `<div class="container-not-found total-center">
                        <div><i class="fi fi-tr-not-found"></i></div>
                        <div><span class="text-not-found">Error al cargar el contenido</span></div>
                    </div>`
                );
            }
        });
    });
});