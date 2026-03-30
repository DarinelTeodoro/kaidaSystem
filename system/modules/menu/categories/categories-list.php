<?php
include('../../../model/conexion.php');
$list_categorias = query_all_categories('all_registers');

$count = count($list_categorias);

if ($count > 1) {
    echo '<div class="row">';
    foreach ($list_categorias as $categoria) {
        if ($categoria['id'] == 1) {
            continue;
        }
        ?>

        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 col-card">
            <div class="card-system">
                <div class="lh-1">
                    <div class="title-card"><?= $categoria['categoria'] ?></div>
                    <div class="description-card text-muted" align="justify">
                        <span><?= $categoria['descripcion'] == null ? 'Sin Descripción' : $categoria['descripcion'] ?></span>
                    </div>
                </div>
                <div class="options-element-list">
                    <button type="button" class="btn-edit" onclick="open_edit_category(<?= $categoria['id'] ?>)"><i class="fi fi-tr-pencil"></i></button>
                    <button type="button" class="btn-delete" onclick="confirm_delete_category(<?= $categoria['id'] ?>)"><i class="fi fi-tr-trash"></i></button>
                </div>
            </div>
        </div>

        <?php
    }
    echo '</div>';
} else {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Categorias Registradas</span></div>';
}
?>