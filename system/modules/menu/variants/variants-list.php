<?php
include('../../../model/conexion.php');
$list_productos = query_all_products('all_registers');
$list_variantes = query_all_variants('all_registers');

if (isset($_POST['id'])) {
    $idprod = $_POST['id'];
} else {
    $idprod = 0;
}

// Agrupacion por variantes
$group_variantes = [];
if ($list_variantes) {
    foreach ($list_variantes as $lv) {
        $id_producto = $lv['id_producto'];
        $group_variantes[$id_producto][] = $lv;
    }
}

if ($list_productos) {
    echo '<div class="accordion row" id="accordionProducto">';
    foreach ($list_productos as $producto) {
        $id_producto = $producto['id'];
        $variantes = $group_variantes[$id_producto] ?? [];
        $count_variantes = count($variantes);

        if ($variantes) {
            echo '<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 p-1"><div class="accordion-item">';
            ?>
            <h2 class="accordion-header">
                <button type="button" class="accordion-button <?= $producto['id'] != $idprod ? 'collapsed' : '' ?>"
                    data-bs-toggle="collapse" data-bs-target="#panelsAccordion-<?= $producto['id'] ?>" aria-expanded="true"
                    aria-controls="panelsAccordion-<?= $producto['id'] ?>">
                    <span>
                        <?= $producto['producto'] ?>
                    </span>
                    <span class="n-products ms-2">
                        <?= $count_variantes ?>
                    </span>
                </button>
            </h2>

            <div id="panelsAccordion-<?= $producto['id'] ?>"
                class="accordion-collapse collapse <?= $producto['id'] == $idprod ? 'show' : '' ?>">
                <div class="accordion-body">
                    <?php
                    foreach ($variantes as $variante) {
                        ?>
                        <div class="card-variant">
                            <div class="title-card align-between">
                                <div><?= $variante['variante'] ?></div>
                                <div class="d-flex align-items-center">
                                    <span class="text-muted"
                                        style="font-size: 0.8rem; margin-right: 4px;">+<?= number_format($variante['precio_agregado'], 2) ?>
                                        = </span>
                                    <span
                                        class="text-success">$<?= number_format(($producto['precio'] + $variante['precio_agregado']), 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn-edit me-1" onclick="open_edit_variant(<?= $variante['id'] ?>)">
                                        <i class="fi fi-tr-pencil"></i>
                                    </button>
                                    <button type="button" class="btn-delete" onclick="confirm_delete_variant(<?= $variante['id'] ?>)">
                                        <i class="fi fi-tr-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
            echo '</div></div>';
        }
    }
    echo '</div>';
}

if (empty($group_variantes)) {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Variantes Registrados</span></div>';
}
?>