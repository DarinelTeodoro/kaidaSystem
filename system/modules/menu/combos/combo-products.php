<?php
include('../../../model/conexion.php');
$array_categorias = query_all_categories('all_registers');
$array_productos = query_all_products('all_registers');

$combo = $_POST['id_combo'];
echo '<input type="hidden" name="section-combo-id" id="section-combo-id" value="'.$combo.'">';

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
        <div class="btn-group row cont-btn-check" role="group">
            <?php
            foreach ($productos as $producto) {
                ?>
                <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 cc">
                    <div class="p-1">
                        <input type="checkbox" class="btn-check" name="producto-combo[]" id="<?= $producto['id'] ?>"
                            value="<?= $producto['id'] ?>">
                        <label class="btn btn-outline-dark label-check p-0" for="<?= $producto['id'] ?>">
                            <div>
                                <!--rgb(0, 0, 0, 0.1) url('../menu/products/files/') center center / cover no-repeat-->
                                <div class="total-center" style="background: #8d0017;
                                        border-radius: 10px;
                                        margin: 10px;
                                        width: 60px;
                                        height: 60px;
                                        color: #ffffff;
                                        background-blend-mode: darken;
                                        box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">
                                        <i class="fi fi-tr-hamburger-soda" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="total-center">
                                <?= $producto['producto'] ?>
                            </div>
                        </label>
                    </div>
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