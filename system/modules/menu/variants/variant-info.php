<?php
include('../../../model/conexion.php');
$data_variant = query_variant($_POST['id_variant']);
?>

<div align="justify"><label for="edit-variant-name">Nombre del Producto</label></div>
<div class="mb-2"><input type="text" name="edit-variant-name" id="edit-variant-name" class="field-modal"
        value="<?= $data_variant['variante'] ?>" required></div>

<div align="justify"><label for="edit-variant-price">Precio Agregado</label></div>
<div class="mb-3"><input type="number" name="edit-variant-price" id="edit-variant-price" class="field-modal" step="0.01"
        value="<?= $data_variant['precio_agregado'] ?>" required></div>

<input type="hidden" name="edit-variant-id" value="<?= $data_variant['id'] ?>">
<input type="hidden" name="menu-request" value="edit-variant">
<div class="total-center">
    <button type="submit" class="btn-execute" id="submit-edit-variant">Guardar</button>
</div>