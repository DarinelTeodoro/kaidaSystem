function open_add_extra() {
    document.getElementById("modal-add-extra").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';
}

function close_add_extra() {
    document.getElementById("modal-add-extra").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

function load_extras_list() {
    $("#content-list").load('extras/extras-list.php', function (response, status, xhr) {
        if (status == "error") {
            $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
        }
    })
}

//agregar extra
$('#form-add-extra').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-extra').disabled = true;

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
                document.getElementById('form-add-extra').reset();
                document.getElementById('submit-add-extra').disabled = false;
                close_add_extra();
                load_extras_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-add-extra').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-extra').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//abrir modal editar extra
function open_edit_extra(id) {
    document.getElementById("modal-edit-extra").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'extras/extra-info.php',
        type: 'POST',
        data: { id_extra: id },
        dataType: 'html',
        success: function (response) {
            $("#container-fields-edit-extra").html(response);
        },
        error: function (xhr, status, error) {
            $("#container-fields-edit-extra").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    });
}

//cerrar modal editar extra
function close_edit_extra() {
    document.getElementById("modal-edit-extra").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

//editar extra
$('#form-edit-extra').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-edit-extra').disabled = true;

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
                document.getElementById('submit-edit-extra').disabled = false;
                close_edit_extra();
                load_extras_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-edit-extra').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-edit-extra').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});

//Eliminar extra
function confirm_delete_extra(id) {
    let id_extra = id;

    if (confirm('¿Deseas eliminar el extra?')) {
        $.ajax({
            url: 'menu-delete.php',
            type: 'POST',
            data: { menu_request: 'delete-extra', id_extra: id_extra },
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    show_alert(response.bg, response.alerta, response.message, true);
                    load_extras_list();
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