<?php
include('../../model/conexion.php');
$list_users = query_all_users();

$count = count($list_users);

if ($count > 1) {
    echo '<div class="row">';
    foreach ($list_users as $user) {
        ?>

        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 col-card">
            <div class="card-system">
                <div class="photo-perfil-list" style="background: rgb(0, 0, 0, 0) url('files/<?= $user['photo'] ?>') center center / cover no-repeat;"></div>
                <div>
                    <div class="lh-1">
                        <div class="title-card">
                            <?= $user['name'] ?>
                        </div>
                        <div class="description-card text-muted" align="justify">
                            <span>@
                                <?= $user['user'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="options-element-list">
                        <button type="button" class="btn-edit" onclick="open_edit_user(<?= $user['id'] ?>)"><i
                                class="fi fi-tr-pencil"></i></button>
                        <button type="button" class="btn-delete" onclick="confirm_delete_user(<?= $user['id'] ?>)"><i
                                class="fi fi-tr-trash"></i></button>
                    </div>
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