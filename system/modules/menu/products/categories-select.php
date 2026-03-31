<?php
include('../../../model/conexion.php');

$options_cat = query_all_categories('all_registers');

if ($options_cat) {
        echo '<option value="1">Sin categoria</option>';
    foreach ($options_cat as $option) {
        if ($option['id'] == 1) {
            continue;
        }
        echo '<option value="' . $option['id'] . '">' . $option['categoria'] . '</option>';
    }
}
?>