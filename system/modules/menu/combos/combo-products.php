<?php
include('../../../model/conexion.php');
$array_categorias = query_all_categories('all_registers');
$array_productos = query_all_products('all_registers');

// Agrupacion por categorias
$grupoProductos = [];
if ($array_productos) {
    foreach ($array_productos as $p) {
        $categoriaId = $p['id_categoria'];
        $grupoProductos[$categoriaId][] = $p;
    }
}

if ($array_categorias) {
    foreach ($array_categorias as $categoria) {
        $categoriaId = $categoria['id'];
        $productos = $grupoProductos[$categoriaId] ?? [];

        if (!$productos)
            continue;
        ?>
        <div class="categorias-productos-combos">
            <span><?= $categoria['categoria'] ?></span>
        </div>
        <div class="btn-group row" role="group">
            <?php
            foreach ($productos as $producto) {
                ?>
                <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3">
                    <input type="checkbox" class="btn-check" name="producto-combo[]" id="<?= $producto['id'] ?>"
                        value="<?= $producto['id'] ?>">
                    <label class="btn btn-outline-primary label-check p-0" for="<?= $producto['id'] ?>">
                        <div>
                            <?= $producto['producto'] ?>
                        </div>
                    </label>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
} else {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Productos Registrados</span></div>';
}
?>