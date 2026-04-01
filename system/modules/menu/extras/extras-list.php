<?php
include('../../../model/conexion.php');
$list_extras = query_all_extras('all_registers');
?>

<div class="table-responsive">
    <table class="table">
        <thead class="table-dark">
            <tr>
                <th>Extra</th>
                <th align="center">Precio</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($list_extras) {
                foreach ($list_extras as $extra) {
                    ?>
                    <tr>
                        <td align="justify">
                            <?= $extra['extra'] ?>
                        </td>
                        <td class="text-success" align="center">$
                            <?= number_format($extra['precio'], 2) ?>
                        </td>
                        <td align="center">
                            <button type="button" class="btn-edit" onclick="open_edit_extra(<?= $extra['id']?>)"
                                style="margin: 4px;">
                                <i class="fi fi-tr-pencil"></i>
                            </button>
                            <button type="button" class="btn-delete" onclick="confirm_delete_extra(<?= $extra['id'] ?>)"
                                style="margin: 4px;">
                                <i class="fi fi-tr-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="3" align="center">Aun no hay extras</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>