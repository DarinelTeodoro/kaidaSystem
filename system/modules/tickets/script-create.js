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


// Actualizar total cuando cambie el precio de envío o el tipo de entrega
// Para el select (solo change funciona bien)
document.addEventListener('change', (e) => {
    if (e.target?.id === 'type-delivery') {
        const valueoption = e.target.value;

        if (valueoption == 1) {
            document.getElementById('container-cost-delivery').style.display = 'none';
        } else if (valueoption == 2) {
            document.getElementById('container-cost-delivery').style.display = 'block';
        }

        // Actualizar el total mostrado
        update_cart_display();
    }
});

// Para el input numérico (input para reflejar mientras escribe)
document.addEventListener('input', (e) => {
    if (e.target?.id === 'delivery-price') {
        // Actualizar el total mientras se escribe el precio de envío
        update_cart_display();
    }
});


function open_arm_combo(id) {
    document.getElementById("modal-arm-combo").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'products-combo.php',
        type: 'POST',
        data: { id_combo: id },
        dataType: 'html',
        success: function (response) {
            $("#arm-combo").html(response);
        },
        error: function (xhr, status, error) {
            $("#arm-combo").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar combo</span></div>');
        }
    });
}

function close_arm_combo() {
    document.getElementById("modal-arm-combo").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


function open_select_variant(id) {
    document.getElementById("modal-select-variant").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';

    $.ajax({
        url: 'variants-product.php',
        type: 'POST',
        data: { id_product: id },
        dataType: 'html',
        success: function (response) {
            $("#select-variant").html(response);
        },
        error: function (xhr, status, error) {
            $("#select-variant").html('<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar variantes</span></div>');
        }
    });
}

function close_select_variant() {
    document.getElementById("modal-select-variant").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}



// Estructura para almacenar los productos en el carrito
let carritoItems = [];

// Función para agregar producto simple al carrito
function product_to_car(productId, productName, productPrice, variantData = null) {
    const producto = {
        id: productId,
        nombre: productName,
        precio: parseFloat(productPrice),
        cantidad: 1,
        tipo: 'producto',
        extras: [],
        comentarios: '',
        variante: variantData
    };

    // Abrir modal para agregar extras y comentarios
    open_extras_modal(producto);
}

// Función para agregar combo al carrito con validación
function combo_to_car() {
    // Validar que se haya seleccionado al menos un producto por grupo (excepto predeterminados)
    const grupos = document.querySelectorAll('[data-grupo-id]');
    let isValid = true;
    let errores = [];

    grupos.forEach(grupo => {
        const tipo = grupo.getAttribute('data-tipo');

        // Saltar grupos predeterminados (tipo 'predeterminado')
        if (tipo === 'predeterminado') return;

        const grupoId = grupo.getAttribute('data-grupo-id');
        let seleccionados = [];

        if (tipo === 'multiple') {
            // Para checkboxes
            seleccionados = Array.from(grupo.querySelectorAll(`input[name="grupo_${grupoId}[]"]:checked`));
        } else {
            // Para radios
            seleccionados = Array.from(grupo.querySelectorAll(`input[name="grupo_${grupoId}"]:checked`));
        }

        if (seleccionados.length === 0) {
            const nombreGrupo = grupo.querySelector('.title-section')?.innerText || 'Grupo';
            errores.push(`Debes seleccionar al menos un producto en "${nombreGrupo}"`);
            isValid = false;
        }
    });

    if (!isValid) {
        show_alert('danger', 'Validación de Combo', errores.join('\n'), true);
        return;
    }

    // Obtener datos del combo
    const comboId = document.getElementById('combo_id_actual')?.value;
    const comboNombre = document.getElementById('combo_nombre_actual')?.value;
    const comboPrecio = document.getElementById('combo_precio_actual')?.value;
    const notasCombo = document.getElementById('notas_combo')?.value || '';

    // Recolectar productos seleccionados por grupo
    const productosSeleccionados = [];

    grupos.forEach(grupo => {
        const tipo = grupo.getAttribute('data-tipo');
        const grupoId = grupo.getAttribute('data-grupo-id');
        const nombreGrupo = grupo.querySelector('.title-section')?.innerText || 'Grupo';

        if (tipo === 'predeterminado') {
            // Recolectar productos predeterminados
            const predeterminados = grupo.querySelectorAll('input[readonly]');
            predeterminados.forEach(input => {
                productosSeleccionados.push({
                    id: input.getAttribute('data-idproducto'),
                    nombre: input.value,
                    grupo: nombreGrupo,
                    tipo: 'predeterminado'
                });
            });
        } else {
            // Recolectar seleccionados por el usuario
            let seleccionados = [];

            if (tipo === 'multiple') {
                seleccionados = grupo.querySelectorAll(`input[name="grupo_${grupoId}[]"]:checked`);
            } else {
                seleccionados = grupo.querySelectorAll(`input[name="grupo_${grupoId}"]:checked`);
            }

            seleccionados.forEach(selected => {
                const label = grupo.querySelector(`label[for="${selected.id}"]`);
                productosSeleccionados.push({
                    id: selected.value,
                    nombre: label?.innerText || 'Producto',
                    grupo: nombreGrupo,
                    tipo: tipo
                });
            });
        }
    });

    const combo = {
        id: comboId,
        nombre: comboNombre,
        precio: parseFloat(comboPrecio),
        cantidad: 1,
        tipo: 'combo',
        productos: productosSeleccionados,
        comentarios: notasCombo,
        extras: []
    };

    // Abrir modal para agregar extras y comentarios adicionales
    open_extras_modal(combo);
}

// Función para agregar variante al carrito
function variant_to_car(productId, productName, variantId, variantName, variantPrice) {
    const producto = {
        id: productId,
        nombre: productName,
        precio: parseFloat(variantPrice),
        cantidad: 1,
        tipo: 'variante',
        variante: {
            id: variantId,
            nombre: variantName
        },
        extras: [],
        comentarios: ''
    };

    // Abrir modal para agregar extras y comentarios
    open_extras_modal(producto);
}

// Modal para agregar extras y comentarios
function open_extras_modal(item) {
    document.getElementById("modal-select-extras").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';


    $('#modal-extra-title').html(`Agregar Extras - ${item.nombre}`);
    // modal dinámico
    const modalExtras = document.getElementById('select-extras');

    modalExtras.innerHTML = `
            <div>
                <div class="align-between sticky-top bg-light pt-2 pb-2" align="end">
                    <div class="extras-total-display">Total Extras: <span class="text-success" id="extras-total-display">$0.00</span></div>
                    <button type="button" class="btn-add-car" onclick="confirm_add_to_car(${JSON.stringify(item).replace(/"/g, '&quot;')})">
                        Agregar al Carrito
                    </button>
                </div>

                <div class="mb-3" align="justify">
                    <label for="comentarios-item" class="form-label">Comentarios adicionales:</label>
                    <textarea id="comentarios-item" class="field-modal" rows="3" 
                        placeholder="Ej: Sin cebolla, extra queso, etc.">${item.comentarios || ''}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Extras disponibles:</label>
                    <div id="extras-list" class="extras-container"></div>
                </div>
            </div>
    `;

    // Cargar lista de extras
    load_extras_list();
}

// Cargar lista de extras desde la base de datos (con botones + y -)
function load_extras_list() {
    $.ajax({
        url: 'extras-list.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            const container = document.getElementById('extras-list');
            if (response.length > 0) {
                container.innerHTML = response.map(extra => `
                    <div class="extra-item mb-2 p-2 border rounded">
                        <div>
                            <div align="justify">
                                <span>${extra.nombre}</span>
                                <span class="text-success">(+$${parseFloat(extra.precio).toFixed(2)})</span>
                            </div>
                            <div class="extra-controls" align="end">
                                <button type="button" class="btn-setting" onclick="decrement_extra(this, ${extra.precio})">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input type="number" 
                                    class="extra-cantidad field-modal text-center" 
                                    data-extra-id="${extra.id}"
                                    data-extra-nombre="${extra.nombre}"
                                    data-extra-precio="${extra.precio}"
                                    min="0" 
                                    max="10" 
                                    value="0"
                                    readonly
                                    style="width: 50px; text-align: center; display: inline-block; border: none; margin: 0; background: #ffffff;">
                                <button type="button" class="btn-setting" onclick="increment_extra(this, ${extra.precio})">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Extras Disponibles</span></div>';
            }
        },
        error: function () {
            document.getElementById('extras-list').innerHTML =
                '<div class="container-not-charge total-center"><i class="fi fi-tr-triangle-warning"></i><span class="text-not-charge">Error al cargar combo</span></div>';
        }
    });
}

// Incrementar cantidad de extra
function increment_extra(button, precio) {
    const extraDiv = button.closest('.extra-item');
    const input = extraDiv.querySelector('.extra-cantidad');
    let currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));

    if (currentValue < maxValue) {
        currentValue++;
        input.value = currentValue;
        update_extra_total_display();
    }
}

// Decrementar cantidad de extra
function decrement_extra(button, precio) {
    const extraDiv = button.closest('.extra-item');
    const input = extraDiv.querySelector('.extra-cantidad');
    let currentValue = parseInt(input.value);

    if (currentValue > 0) {
        currentValue--;
        input.value = currentValue;
        update_extra_total_display();
    }
}

// Actualizar visualización del total de extras en el modal
function update_extra_total_display() {
    const extraInputs = document.querySelectorAll('.extra-cantidad');
    let totalExtras = 0;

    extraInputs.forEach(input => {
        const cantidad = parseInt(input.value);
        if (cantidad > 0) {
            const precio = parseFloat(input.getAttribute('data-extra-precio'));
            totalExtras += precio * cantidad;
        }
    });

    // Buscar o crear el elemento de total
    let totalDisplay = document.getElementById('extras-total-display');

    if (totalDisplay) {
        if (totalExtras > 0) {
            totalDisplay.innerHTML = `$${totalExtras.toFixed(2)}`;
        } else {
            totalDisplay.innerHTML = `$0.00`;
        }
    }
}


// Modificar la función add_to_cart para guardar precio original
function add_to_cart(item) {
    // Guardar precio original si no existe
    if (!item.precio_original) {
        item.precio_original = item.precio;
    }
    carritoItems.push(item);
    var myOffcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasScrolling'));
    myOffcanvas.show();
    update_cart_display();
}

// Confirmar agregar al carrito con extras
function confirm_add_to_car(item) {
    // Reconstruir el objeto (porque se pasó como string escapado)
    const productItem = typeof item === 'string' ? JSON.parse(item) : item;

    // Obtener comentarios
    const comentarios = document.getElementById('comentarios-item')?.value || '';
    productItem.comentarios = comentarios;

    // Obtener extras seleccionados
    const extrasSeleccionados = [];
    const extraInputs = document.querySelectorAll('.extra-cantidad');

    extraInputs.forEach(input => {
        const cantidad = parseInt(input.value);
        if (cantidad > 0) {
            extrasSeleccionados.push({
                id: input.getAttribute('data-extra-id'),
                nombre: input.getAttribute('data-extra-nombre'),
                precio: parseFloat(input.getAttribute('data-extra-precio')),
                cantidad: cantidad
            });
        }
    });

    productItem.extras = extrasSeleccionados;

    // Guardar precio original
    productItem.precio_original = productItem.precio;

    // Calcular precio total con extras
    let precioExtras = 0;
    extrasSeleccionados.forEach(extra => {
        precioExtras += extra.precio * extra.cantidad;
    });
    productItem.precio = productItem.precio + precioExtras;

    // Si es combo, asegurar que los productos tengan la estructura correcta
    if (productItem.tipo === 'combo' && productItem.productos) {
        // Los productos ya vienen con la propiedad 'grupo' desde products-combo.php
        // No es necesario modificar nada adicional
    }

    add_to_cart(productItem);

    // Cerrar modal
    close_extras_modal();
    close_arm_combo();
    close_select_variant();
}

// Actualizar visualización del carrito
function update_cart_display() {
    const container = document.getElementById('productos-in-car');
    const totalSpan = document.getElementById('text-total-car');

    if (!container) return;

    if (carritoItems.length === 0) {
        container.innerHTML = '<div class="text-center p-3 text-muted">No hay productos en el carrito</div>';
        if (totalSpan) totalSpan.innerText = '$0.00';
        return;
    }

    let totalProductos = 0;

    container.innerHTML = carritoItems.map((item, index) => {
        let subtotal = item.precio * item.cantidad;
        totalProductos += subtotal;

        let extrasHtml = '';
        if (item.extras && item.extras.length > 0) {
            extrasHtml = `
                <div class="extras-list small text-muted mt-1">
                    <strong>Extras</strong>
                    <ul class="mb-0">
                        ${item.extras.map(extra => `<li><div class="align-between lh-1"><span>${extra.nombre} x${extra.cantidad}</span><span>+$${(extra.precio * extra.cantidad).toFixed(2)}</span></div>`).join('</li>')}
                    </ul>
                </div>
            `;
        }

        let comentariosHtml = '';
        if (item.comentarios) {
            comentariosHtml = `
                <div class="comentarios-list small text-muted mt-1">
                    <strong>Notas</strong>
                    <div class="bg-notas p-1 rounded">${item.comentarios}</div>
                </div>
            `;
        }

        let detallesHtml = '';
        let variante = '';
        if (item.tipo === 'combo') {
            const productosPorGrupo = {};
            item.productos.forEach(producto => {
                if (!productosPorGrupo[producto.grupo]) {
                    productosPorGrupo[producto.grupo] = [];
                }
                productosPorGrupo[producto.grupo].push(producto.nombre);
            });

            let seccionesHtml = '';
            for (const [grupo, productos] of Object.entries(productosPorGrupo)) {
                seccionesHtml += `
                    <div class="combo-section-details mb-1">
                        <strong class="text-primary">${grupo}:</strong>
                        <div class="ms-2">
                            <ul class="mb-0 lh-1">
                                ${productos.map(producto => `<li>${producto}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                `;
            }

            detallesHtml = `
                <div class="combo-details small text-muted mt-1">
                    <strong>Contenido del Combo:</strong>
                    ${seccionesHtml}
                </div>
            `;
        }

        if (item.tipo === 'variante' && item.variante) {
            variante = `<span class="text-muted">(${item.variante.nombre})</span>`;
        }

        return `
            <div class="cart-item mt-3 p-2 border rounded" data-index="${index}">
                <div>
                    <div class="title-item align-between"><div><span class="fw-bold">${item.nombre}</span> ${variante}</div><div><span class="text-success">$${item.precio_original.toFixed(2)}</span></div></div>
                    ${detallesHtml}
                    ${extrasHtml}
                </div>
                <div class="line mb-1"></div>
                <div class="align-between text-muted"><small class="text-danger">$${item.precio.toFixed(2)}</small><small>x</small><small class="text-primary">${item.cantidad}</small><small>=</small><small class="text-danger">$${subtotal.toFixed(2)}</small></div>
                ${comentariosHtml}
                <div class="align-between mt-2 mb-1">
                    <button class="btn-edit" onclick="edit_cart_item(${index})">
                        <i class="fi fi-tr-pencil"></i>
                    </button>
                    <button class="btn btn-add" onclick="update_quantity(${index}, -1)"><i class="bi bi-dash-lg"></i></button>
                    <button class="btn btn-add" onclick="update_quantity(${index}, 1)"><i class="bi bi-plus-lg"></i></button>
                    <button class="btn-delete" onclick="remove_from_cart(${index})">
                        <i class="fi fi-tr-x"></i>
                    </button>
                </div>
            </div>
        `;
    }).join('');

    // Calcular total con envío
    let totalGeneral = totalProductos;
    const typeDelivery = document.getElementById('type-delivery')?.value;
    let deliveryPrice = 0;

    if (typeDelivery == 2) {
        deliveryPrice = parseFloat(document.getElementById('delivery-price')?.value || 0);
        totalGeneral += deliveryPrice;
    }

    // Mostrar total con desglose si hay envío
    if (deliveryPrice > 0) {
        if (totalSpan) totalSpan.innerHTML = `<small class="text-muted">Comanda: $${totalProductos.toFixed(2)}</small><small class="text-muted"> + envío: $${deliveryPrice.toFixed(2)}</small> = <strong>$${totalGeneral.toFixed(2)}</strong>`;
    } else {
        if (totalSpan) totalSpan.innerText = `$${totalGeneral.toFixed(2)}`;
    }
}


// Actualizar cantidad de un item
function update_quantity(index, change) {
    if (carritoItems[index]) {
        const newCantidad = carritoItems[index].cantidad + change;
        if (newCantidad > 0) {
            carritoItems[index].cantidad = newCantidad;
            update_cart_display();
        } else if (newCantidad === 0) {
            remove_from_cart(index);
        }
    }
}

// Eliminar item del carrito
function remove_from_cart(index) {
    carritoItems.splice(index, 1);
    update_cart_display();
}

// Cerrar modal de extras
function close_extras_modal() {
    document.getElementById("modal-select-extras").classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


/***********************/
/*    EDITAR EXTRAS    */
/***********************/

// Editar item del carrito
function edit_cart_item(index) {
    const item = carritoItems[index];
    if (!item) return;

    // Guardar el índice para actualizar después
    window.editingCartIndex = index;

    // Abrir modal de edición
    open_edit_extras_modal(item, index);
}

// Modal para editar extras y comentarios
function open_edit_extras_modal(item, index) {
    document.getElementById("modal-edit-select-extras").classList.add('object-visible');
    document.getElementById('html-body').style.overflow = 'hidden';


    $('#modal-edit-extra-title').html(`Editar - ${item.nombre}`);
    // modal dinámico
    const modalExtras = document.getElementById('edit-select-extras');

    // Preparar extras seleccionados actualmente
    const selectedExtras = {};
    if (item.extras && item.extras.length > 0) {
        item.extras.forEach(extra => {
            selectedExtras[extra.id] = extra.cantidad;
        });
    }

    modalExtras.innerHTML = `
        <div>
            <div class="align-between sticky-top bg-light pt-2 pb-2" align="end">
                <div class="extras-total-display">Total Extras: <span class="text-success" id="extras-edit-total-display">$${(parseFloat(item.precio) - parseFloat(item.precio_original)).toFixed(2)}</span></div>
                <button type="button" class="btn-add-car" onclick="confirm_edit_cart_item(${index})">
                    Guardar
                </button>
            </div>

            <div class="mb-3">
                <div align="justify"><label for="edit-comentarios-item" class="form-label">Comentarios adicionales:</label></div>
                <textarea id="edit-comentarios-item" class="field-modal" rows="3" 
                        placeholder="Ej: Sin cebolla, extra queso, etc.">${item.comentarios || ''}</textarea>
            </div>
                
            <div class="mb-3">
                <label class="form-label">Extras disponibles:</label>
                <div id="edit-extras-list" class="extras-container"></div>
            </div>
        </div>
    `;

    // Cargar lista de extras con los seleccionados actualmente
    load_edit_extras_list(selectedExtras);
}

// Cargar lista de extras para edición (con botones + y -)
function load_edit_extras_list(selectedExtras) {
    $.ajax({
        url: 'extras-list.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            const container = document.getElementById('edit-extras-list');
            if (response.length > 0) {
                container.innerHTML = response.map(extra => {
                    const cantidadSeleccionada = selectedExtras[extra.id] || 0;
                    return `
                        <div class="extra-item mb-2 p-2 border rounded">
                            <div>
                                <div align="start">
                                    <span>${extra.nombre}</span>
                                    <span class="text-success">(+$${parseFloat(extra.precio).toFixed(2)})</span>
                                </div>
                                <div class="extra-controls" align="end">
                                    <button type="button" class="btn-setting" onclick="decrement_extra_edit(this, ${extra.precio})">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                    <input type="number" 
                                        class="extra-cantidad-edit field-modal text-center" 
                                        data-extra-id="${extra.id}"
                                        data-extra-nombre="${extra.nombre}"
                                        data-extra-precio="${extra.precio}"
                                        min="0" 
                                        max="10" 
                                        value="${cantidadSeleccionada}"
                                        readonly
                                        style="width: 50px; text-align: center; display: inline-block; margin: 0; border: none; background: #ffffff;">
                                    <button type="button" class="btn-setting" onclick="increment_extra_edit(this, ${extra.precio})">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            } else {
                container.innerHTML = '<div class="text-center p-3">No hay extras disponibles</div>';
            }
        },
        error: function () {
            document.getElementById('edit-extras-list').innerHTML =
                '<div class="text-center text-danger p-3">Error al cargar los extras</div>';
        }
    });
}

// Incrementar cantidad de extra en edición
function increment_extra_edit(button, precio) {
    const extraDiv = button.closest('.extra-item');
    const input = extraDiv.querySelector('.extra-cantidad-edit');
    let currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));

    if (currentValue < maxValue) {
        currentValue++;
        input.value = currentValue;
        update_extra_edit_total_display();
    }
}

// Decrementar cantidad de extra en edición
function decrement_extra_edit(button, precio) {
    const extraDiv = button.closest('.extra-item');
    const input = extraDiv.querySelector('.extra-cantidad-edit');
    let currentValue = parseInt(input.value);

    if (currentValue > 0) {
        currentValue--;
        input.value = currentValue;
        update_extra_edit_total_display();
    }
}

// Actualizar visualización del total de extras en el modal de edición
function update_extra_edit_total_display() {
    const extraInputs = document.querySelectorAll('.extra-cantidad-edit');
    let totalExtras = 0;

    extraInputs.forEach(input => {
        const cantidad = parseInt(input.value);
        if (cantidad > 0) {
            const precio = parseFloat(input.getAttribute('data-extra-precio'));
            totalExtras += precio * cantidad;
        }
    });

    let totalDisplay = document.getElementById('extras-edit-total-display');

    if (totalDisplay) {
        if (totalExtras > 0) {
            totalDisplay.innerHTML = `$${totalExtras.toFixed(2)}`;
        } else {
            totalDisplay.innerHTML = `$0.00`;

        }
    }
}

// Confirmar edición del item del carrito
function confirm_edit_cart_item(index) {
    const item = carritoItems[index];
    if (!item) return;

    // Obtener comentarios actualizados
    const comentarios = document.getElementById('edit-comentarios-item')?.value || '';
    item.comentarios = comentarios;

    // Guardar el precio original antes de extras
    const precioOriginal = item.precio_original || item.precio;

    // Obtener extras seleccionados actualizados
    const extrasSeleccionados = [];
    const extraInputs = document.querySelectorAll('.extra-cantidad-edit');
    let nuevoPrecioExtras = 0;

    extraInputs.forEach(input => {
        const cantidad = parseInt(input.value);
        if (cantidad > 0) {
            const extraPrecio = parseFloat(input.getAttribute('data-extra-precio'));
            extrasSeleccionados.push({
                id: input.getAttribute('data-extra-id'),
                nombre: input.getAttribute('data-extra-nombre'),
                precio: extraPrecio,
                cantidad: cantidad
            });
            nuevoPrecioExtras += extraPrecio * cantidad;
        }
    });

    item.extras = extrasSeleccionados;
    item.precio = precioOriginal + nuevoPrecioExtras;

    // Actualizar el carrito
    update_cart_display();

    // Cerrar modal
    close_edit_extras_modal();
}

// Cerrar modal de edición
function close_edit_extras_modal() {
    document.getElementById('modal-edit-select-extras').classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}


/***********************/
/*       GUARDAR       */
/***********************/

// Enviar comanda al servidor
$('#offcanvasScrolling').submit(function (event) {
    event.preventDefault();

    if (carritoItems.length === 0) {
        show_alert('warning', 'Carrito Vacío', 'No hay productos para enviar', true);
        return;
    }

    const typeDelivery = document.getElementById('type-delivery')?.value;
    const clientName = document.getElementById('delivary-client')?.value;
    const deliveryPrice = parseFloat(document.getElementById('delivery-price')?.value || 0);

    if (!clientName) {
        show_alert('warning', 'Cliente Requerido', 'Por favor ingrese el nombre del cliente', true);
        return;
    }

    const ticketData = {
        items: carritoItems,
        type_delivery: typeDelivery,
        client: clientName,
        delivery_cost: deliveryPrice,
        total: calculate_total()
    };

    // Enviar al servidor
    $.ajax({
        url: 'save-ticket.php',
        type: 'POST',
        data: JSON.stringify(ticketData),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function () {
            document.getElementById("view-preloader").classList.add('object-visible');
            document.getElementById('send-ticket').disable = true;
        },
        success: function (response) {
            document.getElementById("view-preloader").classList.remove('object-visible');
            document.getElementById('send-ticket').disable = false;
            if (response.success) {
                show_alert('success', 'Comanda Enviada', 'La comanda ha sido enviada', true);
                // Limpiar carrito después de enviar
                carritoItems = [];
                update_cart_display();
                document.getElementById('offcanvasScrolling').reset();
            } else {
                show_alert('danger', 'Error', response.message || 'Error al enviar la comanda', true);
            }
        },
        error: function () {
            document.getElementById("view-preloader").classList.remove('object-visible');
            document.getElementById('send-ticket').disable = false;
            show_alert('danger', 'Error', 'Error de conexión al servidor', true);
        }
    });
})

// Calcular total del carrito incluyendo costo de entrega
function calculate_total() {
    let total = 0;
    carritoItems.forEach(item => {
        total += item.precio * item.cantidad;
    });

    // Agregar costo de entrega si está seleccionado
    const typeDelivery = document.getElementById('type-delivery')?.value;
    if (typeDelivery == 2) { // Solo si es "Para llevar"
        const deliveryPrice = parseFloat(document.getElementById('delivery-price')?.value || 0);
        total += deliveryPrice;
    }

    return total;
}