//-----------------------------------------
//           SECCION CATEGORIAS
//-----------------------------------------
function open_add_category() {
    document.getElementById("modal-add-category").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';
}

function close_add_category() {
    document.getElementById("modal-add-category").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

function load_categories_list() {
    $("#content-list").load('categories/categories-list.php', function (response, status, xhr) {
        if (status == "error") {
            $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
        }
    })
}

//agregar categoria
$('#form-add-category').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-category').disabled = true;

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
                document.getElementById('form-add-category').reset();
                document.getElementById('submit-add-category').disabled = false;
                close_add_category();
                load_categories_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-add-category').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-category').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//abrir modal editar categoria
function open_edit_category(id) {
    document.getElementById("modal-edit-category").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'categories/category-info.php',
        type: 'POST',
        data: { id_category: id },
        dataType: 'html',
        success: function (response) {
            $("#container-fields-edit-category").html(response);
        },
        error: function (xhr, status, error) {
            $("#container-fields-edit-category").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    });
}

function close_edit_category() {
    document.getElementById("modal-edit-category").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


//editar categoria
$('#form-edit-category').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-edit-category').disabled = true;

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
                document.getElementById('submit-edit-category').disabled = false;
                close_edit_category();
                load_categories_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-edit-category').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-edit-category').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//Eliminar categoria
function confirm_delete_category(id) {
    let id_category = id;

    if (confirm('¿Deseas eliminar la categoria?')) {
        $.ajax({
            url: 'menu-delete.php',
            type: 'POST',
            data: { menu_request: 'delete-category', id_category: id_category },
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    show_alert(response.bg, response.alerta, response.message, true);
                    load_categories_list();
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