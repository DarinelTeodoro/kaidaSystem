<?php
include('../../../model/conexion.php');
$data_combo = query_combo($_POST['id_combo']);
?>

<div class="align-between">
    <span class="details-name-combo text-primary"><?= $data_combo['combo'] ?></span>
    <button class="btn-add" onclick="open_add_section(<?= $data_combo['id'] ?>)">Agregar Sección</button>
</div>
<div class="line"></div>

<?php
$data_secciones = query_all_sections($_POST['id_combo']);

if ($data_secciones) {
    foreach ($data_secciones as $section) {
        ?>

        <div class="align-between mt-3">
            <div class="lh-1" align="justify">
                <div><span class="combo-title-section"><?= $section['section'] ?></span></div>
                <div><span class="text-muted combo-description-section"><?= $section['instruction'] ?></span></div>
            </div>
            <div>
                <button class="btn-delete" onclick="confirm_delete_section(<?= $section['id'] ?>)">
                    <i class="fi fi-tr-trash"></i>
                </button>
            </div>
        </div>

        <?php

        $productos = json_decode($section['products'], true) ?? [];
        if (!empty($productos)) {
            echo '<ol class="lh-1 mt-2 mb-3 list-products-section" align="justify">';
            foreach ($productos as $producto) {
                $prod = query_product($producto);
                echo '
                    <li>' . $prod['producto'] . '</li>
                ';
            }
            echo '</ol>';
        }
    }
} else {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Secciones Registradas</span></div>';
}
?>