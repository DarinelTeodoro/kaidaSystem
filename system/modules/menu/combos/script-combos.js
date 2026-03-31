//-----------------------------------------
//           SECCION COMBOS
//-----------------------------------------
function open_add_combo() {
    document.getElementById("modal-add-combo").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';
}

function close_add_combo() {
    document.getElementById("modal-add-combo").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

function load_combos_list() {
    $("#content-list").load('combos/combos-list.php', function (response, status, xhr) {
        if (status == "error") {
            $("#content-list").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar la lista</span></div>');
        }
    })
}

//agregar combo
$('#form-add-combo').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-combo').disabled = true;

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
                document.getElementById('form-add-combo').reset();
                document.getElementById('submit-add-combo').disabled = false;
                close_add_combo();
                load_combos_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-add-combo').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-combo').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


//abrir modal editar combo
function open_edit_combo(id) {
    document.getElementById("modal-edit-combo").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'combos/combo-info.php',
        type: 'POST',
        data: { id_combo: id },
        dataType: 'html',
        success: function (response) {
            $("#container-fields-edit-combo").html(response);
        },
        error: function (xhr, status, error) {
            $("#container-fields-edit-combo").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    });
}

function close_edit_combo() {
    document.getElementById("modal-edit-combo").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


//editar combo
$('#form-edit-combo').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-edit-combo').disabled = true;

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
                document.getElementById('submit-edit-combo').disabled = false;
                close_edit_combo();
                load_combos_list();
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-edit-combo').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-edit-combo').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});


function load_sections_combo(combo) {
    $.ajax({
        url: 'combos/combo-sections.php',
        type: 'POST',
        data: { id_combo: combo },
        dataType: 'html',
        success: function (response) {
            $("#container-details-combo").html(response);
        },
        error: function (xhr, status, error) {
            $("#container-details-combo").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    })
}


function open_setting_combo(id) {
    document.getElementById("modal-setting-combo").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    load_sections_combo(id);
}

function close_setting_combo() {
    document.getElementById("modal-setting-combo").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


function open_add_section(id) {
    document.getElementById("modal-add-sections").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'combos/combo-products.php',
        type: 'POST',
        data: {id_combo: id},
        dataType: 'html',
        success: function (response) {
            $("#products-for-combo").html(response);
        },
        error: function (xhr, status, error) {
            $("#products-for-combo").html('<div class="p-2">Error de carga: ' + error + '</div>');
        }
    });
}

function close_add_section() {
    document.getElementById("modal-add-sections").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

//Eliminar combo
function confirm_delete_section(id) {
    let id_section = id;

    if (confirm('¿Deseas eliminar la seccion?')) {
        $.ajax({
            url: 'menu-delete.php',
            type: 'POST',
            data: { menu_request: 'delete-combo-section', id_section: id_section },
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    show_alert(response.bg, response.alerta, response.message, true);
                    load_sections_combo(response.combo);
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

(function () {
    const input = document.getElementById('combos-search-product');
    const cont = document.getElementById('products-for-combo');

    if (!input || !cont) return;

    function normalize(str) {
        return (str || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function filterProducts() {
        const q = normalize(input.value);
        const sections = cont.querySelectorAll('.categorias-productos-combos');

        sections.forEach(section => {
            const group = section.nextElementSibling;

            if (!group || !group.classList.contains('cont-btn-check')) return;

            const items = group.querySelectorAll('.cc');
            let anyVisibleInCategory = false;

            items.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                const label = item.querySelector('label');
                const name = normalize(label ? label.textContent : '');
                const isChecked = checkbox ? checkbox.checked : false;
                const match = (q === '') ? true : (name.includes(q) || isChecked);

                item.style.display = match ? '' : 'none';

                if (match) anyVisibleInCategory = true;
            });

            section.style.display = anyVisibleInCategory ? '' : 'none';
            group.style.display = anyVisibleInCategory ? '' : 'none';
        });
    }

    input.addEventListener('input', filterProducts);

    cont.addEventListener('change', (e) => {
        if (e.target && e.target.matches('input[type="checkbox"]')) {
            filterProducts();
        }
    });

    filterProducts();
})();

//AGREGAR SECCION DEL COMBO
$('#form-section-combo').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-section').disabled = true;

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
                document.getElementById('submit-add-section').disabled = false;
                document.getElementById('form-section-combo').reset();
                show_alert(response.bg, response.alerta, response.message, true);
                load_sections_combo(response.combo);
                close_add_section();
            } else {
                document.getElementById('submit-add-section').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-section').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});

//Eliminar combo
function confirm_delete_combo(id) {
    let id_combo = id;

    if (confirm('¿Deseas eliminar el combo?')) {
        $.ajax({
            url: 'menu-delete.php',
            type: 'POST',
            data: { menu_request: 'delete-combo', id_combo: id_combo },
            dataType: 'json',
            success: function (response) {
                if (response.status === 200) {
                    show_alert(response.bg, response.alerta, response.message, true);
                    load_combos_list();
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