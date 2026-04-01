<?php
include('../../../model/conexion.php');
$data_extra = query_extra($_POST['id_extra']);
?>

<div align="justify"><label for="edit-extra-name">Nombre del Extra</label></div>
<div class="mb-2"><input type="text" name="edit-extra-name" id="edit-extra-name" class="field-modal" value="<?= $data_extra['extra'] ?>" required></div>

<div align="justify"><label for="edit-extra-price">Precio</label></div>
<div class="mb-3"><input type="number" name="edit-extra-price" id="edit-extra-price" class="field-modal" step="0.01"
        value="<?= $data_extra['precio'] ?>" placeholder="$0.00" required>
</div>

<input type="hidden" name="edit-extra-id" value="<?= $data_extra['id'] ?>">
<input type="hidden" name="menu-request" value="edit-extra">

<div class="total-center"><button type="submit" class="btn-execute" id="submit-edit-extra">Actualizar</button></div>