<?php
// products-list-ajax.php
include('../../model/conexion.php');

$list_productos = query_all_products('all_registers');
$list_categorias = query_all_categories('all_registers');

// Generar un hash único basado en los datos actuales
$data_hash = md5(json_encode($list_productos) . json_encode($list_categorias));

// Verificar si es una solicitud de verificación de cambios
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    if (isset($_POST['check_hash']) && $_POST['check_hash'] === 'true') {
        // Solo devolver el hash para verificar cambios
        echo json_encode(['hash' => $data_hash]);
        exit;
    }
}

// Si hay cambios o es primera carga, devolver el HTML completo
ob_start();
?>
<?php
// Aquí va el mismo código de products-list.php para generar el HTML
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
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsAccordion-<?= $categoria['id'] ?>">
                    <span><?= $categoria['categoria'] ?></span>
                    <span class="n-products ms-2"><?= $count_productos ?></span>
                </button>
            </h2>
            <div id="panelsAccordion-<?= $categoria['id'] ?>" class="accordion-collapse collapse">
                <div class="accordion-body row">
                    <?php foreach ($productos as $producto) { ?>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 p-1">
                            <div class="card-system card-product">
                                <div class="imagen-product"
                                    style="background: rgb(0, 0, 0, 0.1) url('../menu/products/files/<?= $producto['imagen'] ?>') center center / cover no-repeat;">
                                </div>
                                <div class="container-rows">
                                    <div class="lh-1">
                                        <div class="title-card align-between">
                                            <div><?= $producto['producto'] ?></div>
                                            <div class="text-success">$<?= number_format($producto['precio'], 2) ?></div>
                                        </div>
                                        <div class="description-card">
                                            <span
                                                class="text-muted"><?= $producto['descripcion'] == null ? 'Sin Descripción' : $producto['descripcion'] ?></span>
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
                    <?php } ?>
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
<?php
$html = ob_get_clean();
echo json_encode(['html' => $html, 'hash' => $data_hash]);
?>