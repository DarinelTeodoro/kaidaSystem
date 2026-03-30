<?php
include('../../../model/conexion.php');
$data_combo = query_combo($_POST['id_combo']);
?>

<div align="justify"><label for="edit-combo-name">Nombre del Combo</label></div>
<div class="mb-2">
    <input type="text" name="edit-combo-name" id="edit-combo-name" class="field-modal"
        value="<?= $data_combo['combo'] ?>" required>
</div>

<div align="justify"><label for="edit-combo-description">Descripción</label></div>
<textarea name="edit-combo-description" id="edit-combo-description" class="mb-2" rows="5"
    placeholder="Descripcion sobre los productos del combo."><?= $data_combo['descripcion'] ?></textarea>

<div align="justify"><label for="edit-combo-price">Precio</label></div>
<div class="mb-3">
    <input type="number" name="edit-combo-price" id="edit-combo-price" class="field-modal" step="0.01"
        placeholder="$0.00" value="<?= $data_combo['precio'] ?>" required>
</div>

<input type="hidden" name="edit-combo-id" value="<?= $data_combo['id'] ?>">
<input type="hidden" name="menu-request" value="edit-combo">
<div class="total-center">
    <button type="submit" class="btn-execute" id="submit-edit-combo">Guardar</button>
</div>