//-----------------------------------------
//           SECCION PRODUCTOS
//-----------------------------------------
function open_add_product() {
    document.getElementById("modal-add-product").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'products/categories-select.php',
        type: 'POST',
        data: {},
        dataType: 'html',
        success: function (response) {
            $("#product-category").html(response);
        },
        error: function (xhr, status, error) {
            $("#product-category").html('<option value="1">Sin categoria</option>');
        }
    });
}

function close_add_product() {
    document.getElementById("modal-add-product").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}

function load_products_list(id) {
    $.ajax({
        url: 'products/products-list.php',
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

//visalizar foto de perfil
document.addEventListener('change', e => {
    if (e.target.id === 'product-photo') {
        showImgNewProduct(e);
    }
});

function showImgNewProduct(e) {
    const cont_img_newuser = document.getElementById('label-photo-new-product');
    const input = e.target;
    const file = input.files?.[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (ev) {
            cont_img_newuser.style.background =
                `rgb(0, 0, 0, 0) url(${ev.target.result}) center center / cover no-repeat`;
        };
        reader.readAsDataURL(file);
    } else {
        cont_img_newuser.style.background =
            `rgb(0, 0, 0, 0) url('products/files/default.webp') center center / cover no-repeat`;
    }
}

//agregar producto
$('#form-add-product').submit(function (event) {
    event.preventDefault();

    document.getElementById('submit-add-product').disabled = true;

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
                document.getElementById('form-add-product').reset();
                document.getElementById('submit-add-product').disabled = false;
                close_add_product();
                load_products_list(response.idcat);
                show_alert(response.bg, response.alerta, response.message, true);
            } else {
                document.getElementById('submit-add-product').disabled = false;
                show_alert(response.bg, response.alerta, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-add-product').disabled = false;
            show_alert('danger', 'Error', error, true);
        }
    });
});