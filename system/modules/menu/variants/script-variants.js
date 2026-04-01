function open_add_variant() {
    document.getElementById("modal-add-variant").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'variants/products-select.php',
        type: 'POST',
        data: {},
        dataType: 'html',
        success: function (response) {
            $("#variant-product").html(response);
        },
        error: function (xhr, status, error) {
            $("#variant-product").html('<option value="0">Sin productos disponibles</option>');
        }
    });
}

function close_add_variant() {
    document.getElementById("modal-add-variant").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

function load_variants_list() {
    $("#content-list").load('variants/variants-list.php', function (response, status, xhr) {
        if (status == "error") {
            $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
        }
    })
}

function load_variants_list(id) {
    $.ajax({
        url: 'variants/variants-list.php',
        type: 'POST',
        data: {id: id},
        dataType: 'html',
        success: function (response) {
            $("#content-list").html(response);
        },
        error: function (xhr, status, error) {
            $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
        }
    });
}


//agregar variante
$('#form-add-variant').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-variant').disabled = true;

    let html = `
    <div class="loading">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    `;

    show_alert('default', 'Esperando Respuesta', html, false);
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: 'menu-add.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === 201) {
                document.getElementById('form-add-variant').reset();
                document.getElementById('submit-add-variant').disabled = false;
                close_add_variant();
                load_variants_list(response.idprod);
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-add-variant').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-variant').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//abrir modal editar variante
function open_edit_variant(id) {
    document.getElementById("modal-edit-variant").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'variants/variant-info.php',
        type: 'POST',
        data: { id_variant: id },
        dataType: 'html',
        success: function (response) {
            $("#container-fields-edit-variant").html(response);
        },
        error: function (xhr, status, error) {
            $("#container-fields-edit-variant").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    });
}

//cerrar modal editar variante
function close_edit_variant() {
    document.getElementById("modal-edit-variant").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


//editar variante
$('#form-edit-variant').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-edit-variant').disabled = true;

    let html = `
    <div class="loading">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    `;

    show_alert('default', 'Esperando Respuesta', html, false);
    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: 'menu-edit.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === 200) {
                document.getElementById('submit-edit-variant').disabled = false;
                close_edit_variant();
                load_variants_list(response.idprod);
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-edit-variant').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-edit-variant').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//Eliminar variante
function confirm_delete_variant(id) {
    let id_variant = id;

    if (confirm('¿Deseas eliminar la variante?')) {
        $.ajax({
            url: 'menu-delete.php',
            type: 'POST',
            data: { menu_request: 'delete-variant', id_variant: id_variant },
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    show_alert(response.bg, response.alerta, response.message, true);
                    load_variants_list(response.idprod);
                } else {
                    show_alert(response.bg, response.alerta, response.message, true);
                }
            },
            error: function (xhr, status, error) {
                show_alert('danger', 'Error', error, true);
            }
        });
    }
}