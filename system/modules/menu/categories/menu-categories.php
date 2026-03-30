<?php
session_start();
include('../../../model/conexion.php');

if (empty($_SESSION['USERLOGED'])) {
    echo '<script>window.location.href = "../../../../index.php";</script>';
} else {
    $userloged = query_user($_SESSION['USERLOGED']);
}
?>

<div class="container-title-section align-between">
    <div class="title-section">Categorias</div>
    <button type="button" class="btn-add" onclick="open_add_category()">Agregar</button>
</div>

<div class="content-list" id="content-list"></div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-category">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Agregar Categoria</span>
            <button type="button" class="btn btn-close" onclick="close_add_category()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-add-category">
            <div align="justify"><label for="category-name">Nombre de la Categoria</label></div>
            <div class="mb-2">
                <input type="text" name="category-name" id="category-name" class="field-modal" required>
            </div>

            <div align="justify"><label for="category-description">Descripción</label></div>
            <textarea name="category-description" id="category-description" class="mb-3" rows="5"
                placeholder="Descripcion sobre los productos de la categoria."></textarea>

            <input type="hidden" name="menu-request" value="add-category">
            <div class="total-center">
                <button type="submit" class="btn-execute"
                    id="submit-add-category">Agregar</button>
            </div>
        </form>
    </div>
</div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-edit-category">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Editar Categoria</span>
            <button type="button" class="btn btn-close" onclick="close_edit_category()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-edit-category">
            <div id="container-fields-edit-category">

            </div>
        </form>
    </div>
</div>

<script src="categories/script-categories.js"></script>