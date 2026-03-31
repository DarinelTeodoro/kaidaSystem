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
    <div class="title-section">Productos</div>
    <button type="button" class="btn-add" onclick="open_add_product()">Agregar</button>
</div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-product">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Agregar Producto</span>
            <button type="button" class="btn btn-close" onclick="close_add_product()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-add-product" enctype="multipart/form-data">
            <div class="total-center">
                <label class="label-photo total-center" for="product-photo" id="label-photo-new-product"
                    style="background: rgb(0, 0, 0, 0.2) url(products/files/default.webp) center center / cover no-repeat;">
                    <i class="fi fi-tr-camera-viewfinder"></i>
                </label>
                <input type="file" name="product-photo" id="product-photo" accept="image/png, image/jpeg, image/webp">
            </div>

            <div align="justify"><label for="product-name">Nombre del Producto</label></div>
            <div class="mb-2">
                <input type="text" name="product-name" id="product-name" class="field-modal" required>
            </div>

            <div align="justify"><label for="product-description">Descripción</label></div>
            <textarea type="text" name="product-description" id="product-description" class="mb-2" rows="5"
                placeholder="Descripcion del producto."></textarea>

            <div align="justify"><label for="product-price">Precio</label></div>
            <div class="mb-2">
                <input type="number" name="product-price" id="product-price" class="field-modal" step="0.01"
                    placeholder="$0.00" required>
            </div>

            <div align="justify"><label for="product-category">Selecciona Categoria</label></div>
            <div class="mb-3">
                <select name="product-category" id="product-category" class="field-modal">

                </select>
            </div>

            <input type="hidden" name="menu-request" value="add-product">
            <div class="total-center">
                <button type="submit" class="btn-execute" id="submit-add-product">Agregar</button>
            </div>
        </form>
    </div>
</div>

<div class="content-list" id="content-list"></div>

<script src="products/script-products.js"></script>