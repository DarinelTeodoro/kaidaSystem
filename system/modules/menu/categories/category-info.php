<?php
include('../../../model/conexion.php');
$data_categoria = query_category($_POST['id_category']);
?>

<div align="justify"><label for="edit-category-name">Nombre de la Categoria</label></div>
<div class="mb-2">
    <input type="text" name="edit-category-name" id="edit-category-name" class="field-modal"
        value="<?= $data_categoria['categoria'] ?>" required>
</div>

<div align="justify"><label for="edit-category-description">Descripción</label></div>
<textarea name="edit-category-description" id="edit-category-description" class="mb-3" rows="5"
    placeholder="Descripcion sobre los productos de la categoria."><?= $data_categoria['descripcion'] ?></textarea>

<input type="hidden" name="edit-category-id" value="<?= $data_categoria['id'] ?>">
<input type="hidden" name="menu-request" value="edit-category">
<div class="total-center">
    <button type="submit" class="btn-execute" id="submit-edit-category">Guardar</button>
</div>