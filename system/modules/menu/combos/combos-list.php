<?php
include('../../../model/conexion.php');
$list_combos = query_all_combos('all_registers');

if ($list_combos) {
    echo '<div class="row">';
    foreach ($list_combos as $combo) {
        ?>
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 col-card">
            <div class="card-system">
                <div class="lh-1">
                    <div class="title-card align-between">
                        <?= $combo['combo'] ?>
                        <span class="text-success">$<?= number_format($combo['precio'], 2) ?></span>
                    </div>
                    <div class="description-card text-muted" align="justify">
                        <span><?= $combo['descripcion'] == null ? 'Sin Descripción' : $combo['descripcion'] ?></span>
                    </div>
                </div>
                <div class="options-element-list">
                    <button type="button" class="btn-setting" onclick="open_setting_combo(<?= $combo['id'] ?>)"><i class="fi fi-tr-module"></i></button>
                    <button type="button" class="btn-edit" onclick="open_edit_combo(<?= $combo['id'] ?>)"><i class="fi fi-tr-pencil"></i></button>
                    <button type="button" class="btn-delete" onclick="confirm_delete_combo(<?= $combo['id'] ?>)"><i class="fi fi-tr-trash"></i></button>
                </div>
            </div>
        </div>

        <?php
    }
    echo '</div>';
} else {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Combos Registradas</span></div>';
}
?>