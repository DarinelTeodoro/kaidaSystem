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
    <div class="title-section">Variantes</div>
    <button type="button" class="btn-add" onclick="open_add_variant()">Agregar</button>
</div>


<div class="content-list" id="content-list"></div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-variant">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Agregar Variante</span>
            <button type="button" class="btn btn-close" onclick="close_add_variant()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-add-variant">
            <div align="justify"><label for="variant-product">Selecciona Producto</label></div>
            <div class="mb-2">
                <select name="variant-product" id="variant-product" class="field-modal">
                    
                </select>
            </div>

            <div align="justify"><label for="variant-name">Variante</label></div>
            <div class="mb-2"><input type="text" name="variant-name" id="variant-name" class="field-modal" required>
            </div>

            <div align="justify"><label for="variant-price">Precio Agregado</label></div>
            <div class="mb-3"><input type="number" name="variant-price" id="variant-price" class="field-modal"
                    step="0.01" placeholder="$0.00" required></div>

            <input type="hidden" name="menu-request" value="add-variant">
            <div class="total-center">
                <button type="submit" class="btn-execute" id="submit-add-variant">Agregar</button>
            </div>
        </form>
    </div>
</div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-edit-variant">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Editar Variante</span>
            <button type="button" class="btn btn-close" onclick="close_edit_variant()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-edit-variant">
            <div id="container-fields-edit-variant">

            </div>
        </form>
    </div>
</div>

<script src="variants/script-variants.js"></script>