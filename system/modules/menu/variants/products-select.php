<?php
include('../../../model/conexion.php');

$options_prod = query_all_products('all_registers');

if ($options_prod) {
    echo '<option value="0">Seleccione un producto</option>';
    foreach ($options_prod as $option) {
        if ($option['id'] == 1) {
            continue;
        }
        echo '<option value="' . $option['id'] . '">' . $option['producto'] . ' ' . '($' . number_format($option['precio'], 2) . ')</option>';
    }
} else {
    echo '<option value="0">Sin productos disponibles</option>';
}
?>