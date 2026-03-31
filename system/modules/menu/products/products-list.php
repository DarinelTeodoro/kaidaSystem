<?php
include('../../../model/conexion.php');
$list_productos = query_all_products('all_registers');
$list_categorias = query_all_categories('all_registers');

if (isset($_POST['id'])) {
    $idcat = $_POST['id'];
} else {
    $idcat = 0;
}

// Agrupacion por categorias
$group_productos = [];
if ($list_productos) {
    foreach ($list_productos as $lp) {
        $id_categoria = $lp['id_categoria'];
        $group_productos[$id_categoria][] = $lp;
    }
}

if ($list_categorias) {
    echo '<div class="accordion" id="accordionCategoria">';
    foreach ($list_categorias as $categoria) {
        $id_categoria = $categoria['id'];
        $productos = $group_productos[$id_categoria] ?? [];
        $count_productos = count($productos);
        echo '<div class="accordion-item mb-2">';
        if ($productos) {
            ?>
            <h2 class="accordion-header">
                <button class="accordion-button <?= $categoria['id'] != $idcat ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsAccordion-<?= $categoria['id'] ?>" aria-expanded="true" aria-controls="panelsAccordion-<?= $categoria['id'] ?>">
                    <span>
                        <?= $categoria['categoria'] ?>
                    </span>
                    <span class="n-products ms-2">
                        <?= $count_productos ?>
                    </span>
                </button>
            </h2>

            <div id="panelsAccordion-<?= $categoria['id'] ?>" class="accordion-collapse collapse <?= $categoria['id'] == $idcat ? 'show' : '' ?>">
                <div class="accordion-body row">
                    <?php
                    foreach ($productos as $producto) {
                        ?>

                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 p-1">
                            <div class="card-system">
                                <div
                                    style="background: rgb(0, 0, 0, 0.1) url('../menu/products/files/') center center / cover no-repeat;">
                                </div>
                                <div class="container-rows">
                                    <div class="lh-1">
                                        <div class="title-card align-between">
                                            <div>
                                                <?= $producto['producto'] ?>
                                            </div>
                                            <div class="text-success">$
                                                <?= number_format($producto['precio'], 2) ?>
                                            </div>
                                        </div>
                                        <div class="description-card">
                                            <span class="text-muted">
                                                <?= $producto['descripcion'] == null ? 'Sin Descripción' : $producto['descripcion'] ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="options-element-list">
                                        <button type="button" class="btn-edit" onclick="open_edit_product(<?= $producto['id'] ?>)">
                                            <i class="fi fi-tr-pencil"></i>
                                        </button>
                                        <button type="button" class="btn-delete"
                                            onclick="confirm_delete_product(<?= $producto['id'] ?>)">
                                            <i class="fi fi-tr-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
    echo '</div>';
}

if (empty($group_productos)) {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Productos Registrados</span></div>';
}
?>



<script>
    //Abrir editar producto
    document.querySelectorAll('.edit-product').forEach(button => {
        button.addEventListener('click', function () {
            let product = this.dataset.product;
            disabled_botones();
            document.getElementById('modal-edit-product')?.classList.add('object-visible');
            document.getElementById('html-body').style.overflow = 'hidden';

            $("#form-edit-product").html('<div align="center">Cargando...</div>');

            $.ajax({
                url: 'modules/menu/menu-productos-info.php',
                type: 'POST',
                data: { id_product: product },
                dataType: 'html',
                success: function (response) {
                    setTimeout(() => {
                        $("#form-edit-product").html(response);
                    }, 200);
                },
                error: function (xhr, status, error) {
                    show_alert('danger', 'Error al cargar los datos', error, true);
                }
            });
        });
    });

    document.addEventListener('click', e => {
        if (e.target.id === 'close-modal-edit-product') {
            enabled_botones();
            document.getElementById('modal-edit-product')?.classList.remove('object-visible');
            document.getElementById('html-body').style.overflow = 'auto';
        }
    });


    //Eliminar producto
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function () {
            let product = this.dataset.product;
            disabled_botones();

            if (confirm('¿Deseas eliminar el producto?')) {
                $.ajax({
                    url: 'modules/menu/menu-delete.php',
                    type: 'POST',
                    data: { menu_request: 'delete-product', id_product: product },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 200) {
                            enabled_botones();
                            show_alert(response.bg, response.alerta, response.message, true);
                            recargar_lista_productos();
                        } else {
                            enabled_botones();
                            show_alert(response.bg, response.alerta, response.message, true);
                        }
                    }
                });
            } else {
                enabled_botones();
            }
        });
    });
</script>