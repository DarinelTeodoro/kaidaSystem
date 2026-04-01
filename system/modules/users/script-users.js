//primera vista
$(document).ready(function () {
    $("#container-users-list").load('users-list.php', function (response, status, xhr) {
        if (status == "error") {
            $('#container-content-section').html(
                `<div class="container-not-found total-center">
                    <div><i class="fi fi-tr-not-found"></i></div>
                    <div><span class="text-not-found">Error al cargar el contenido</span></div>
                </div>`
            );
        }
    });
});

function load_users_list() {
    $("#container-users-list").load('users-list.php', function (response, status, xhr) {
        if (status == "error") {
            $('#container-content-section').html(
                `<div class="container-not-found total-center">
                    <div><i class="fi fi-tr-not-found"></i></div>
                    <div><span class="text-not-found">Error al cargar el contenido</span></div>
                </div>`
            );
        }
    });
}

function open_add_user() {
    document.getElementById("modal-add-user").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';
}

function close_add_user() {
    document.getElementById("modal-add-user").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

//visalizar foto de perfil
document.addEventListener('change', e => {
    if (e.target.id === 'user-photo') {
        showImgNewUser(e);
    }
});

function showImgNewUser(e) {
    const cont_img_newuser = document.getElementById('label-photo-new-user');
    const input = e.target;
    const file = input.files?.[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (ev) {
            cont_img_newuser.style.background =
                `rgb(0, 0, 0, 0.2) url(${ev.target.result}) center center / cover no-repeat`;
        };
        reader.readAsDataURL(file);
    } else {
        cont_img_newuser.style.background =
            `rgb(0, 0, 0, 0.2) url('files/default.webp') center center / cover no-repeat`;
    }
}

// ejecutar agregar usuario
$('#form-add-user').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-user').disabled = true;

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
        url: 'controller-user.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === 201) {
                document.getElementById('submit-add-user').disabled = false;
                document.getElementById('label-photo-new-user').style.background = "rgb(0, 0, 0, 0.2) url('files/default.webp') center center / cover no-repeat";
                document.getElementById('form-add-user').reset();
                close_add_user();
                load_users_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-add-user').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-user').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//abrir modal editar categoria
function open_edit_user(id) {
    document.getElementById("modal-edit-user").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'user-info.php',
        type: 'POST',
        data: { id_user: id },
        dataType: 'html',
        success: function (response) {
            $("#container-fields-edit-user").html(response);
        },
        error: function (xhr, status, error) {
            $("#container-fields-edit-user").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    });
}

function close_edit_user() {
    document.getElementById("modal-edit-user").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

// ejecutar editar usuario
$('#form-edit-user').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-edit-user').disabled = true;

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
        url: 'controller-user.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.status === 201) {
                document.getElementById('submit-edit-user').disabled = false;
                document.getElementById('label-photo-edit-user').style.background = "rgb(0, 0, 0, 0.2) url('files/default.webp') center center / cover no-repeat";
                close_edit_user();
                load_users_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-edit-user').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-edit-user').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});

//Eliminar usuario
function confirm_delete_user(id) {
    let id_user = id;

    if (confirm('¿Deseas eliminar el usuario?')) {
        $.ajax({
            url: 'controller-user.php',
            type: 'POST',
            data: { user_request: 'delete-user', id_user: id_user },
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    show_alert(response.bg, response.alerta, response.message, true);
                    load_users_list();
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