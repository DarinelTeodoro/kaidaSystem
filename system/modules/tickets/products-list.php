<?php
include('../../model/conexion.php');
$list_productos = query_all_products('all_registers');
$list_categorias = query_all_categories('all_registers');
$list_combos = query_all_combos('all_registers');

// Agrupacion por categorias
$group_productos = [];
if ($list_productos) {
    foreach ($list_productos as $lp) {
        $id_categoria = $lp['id_categoria'];
        $group_productos[$id_categoria][] = $lp;
    }
}

if ($list_combos) {
    ?>
    <div class="separator-category">
        <span>
            Combos
        </span>
    </div>
    <?php
    echo '<div class="carrousel-products">';
    foreach ($list_combos as $combo) {
        ?>
        <div class="card-product" data-search="<?= $combo['combo'] ?> Combos" onclick="open_arm_combo(<?= $combo['id'] ?>)">
            <div class="imagen-product"
                style="background: rgb(0, 0, 0, 0.2) url('../menu/products/files/combo.webp ?>') center center / cover no-repeat;">
            </div>
            <div class="info-product total-center">
                <div class="lh-1 mb-1">
                    <?= $combo['combo'] ?>
                </div>
                <div class="text-success">$
                    <?= number_format($combo['precio'], 2) ?>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';
}

if ($list_categorias) {
    foreach ($list_categorias as $categoria) {
        $id_categoria = $categoria['id'];
        $productos = $group_productos[$id_categoria] ?? [];
        $count_productos = count($productos);
        if ($productos) {
            ?>
            <div class="separator-category">
                <span>
                    <?= $categoria['categoria'] ?>
                </span>
            </div>

            <div class="carrousel-products">
                <?php
                foreach ($productos as $producto) {
                    $array_variants = query_variants($producto['id']);

                    if ($array_variants) {
                        $onclick = 'open_select_variant('.$producto['id'].')';
                    } else {
                        //$onclick = 'product_to_car()';
                        $onclick = 'product_to_car('.$producto['id'].',\''. addslashes($producto['producto']) .'\','. $producto['precio'] .')';
                    }
                    ?>
                    <div class="card-product" data-search="<?= $producto['producto'] . ' ' . $categoria['categoria'] ?>" onclick="<?= $onclick ?>" <?= $producto['id'] ?>>
                        <div class="imagen-product"
                            style="background: rgb(0, 0, 0, 0.2) url('../menu/products/files/<?= $producto['imagen'] ?>') center center / cover no-repeat;">
                        </div>
                        <div class="info-product total-center">
                            <div class="lh-1 mb-1">
                                <?= $producto['producto'] ?>
                            </div>
                            <div class="text-success">$
                                <?= number_format($producto['precio'], 2) ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
    }
}

if (empty($group_productos)) {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Productos Registrados</span></div>';
}
?>