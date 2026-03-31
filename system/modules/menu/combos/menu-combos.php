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
    <div class="title-section">Combos</div>
    <button type="button" class="btn-add" onclick="open_add_combo()">Agregar</button>
</div>

<div class="content-list" id="content-list"></div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-combo">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Agregar Combo</span>
            <button type="button" class="btn btn-close" onclick="close_add_combo()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-add-combo">
            <div align="justify"><label for="combo-name">Nombre del Combo</label></div>
            <div class="mb-2">
                <input type="text" name="combo-name" id="combo-name" class="field-modal" required>
            </div>

            <div align="justify"><label for="combo-description">Descripción</label></div>
            <textarea name="combo-description" id="combo-description" class="mb-2" rows="5"
                placeholder="Descripcion sobre los productos del combo."></textarea>

            <div align="justify"><label for="combo-price">Precio</label></div>
            <div>
                <input type="number" name="combo-price" id="combo-price" class="field-modal mb-3" step="0.01"
                    placeholder="$0.00" required>
            </div>

            <input type="hidden" name="menu-request" value="add-combo">
            <div class="total-center">
                <button type="submit" class="btn-execute" id="submit-add-combo">Agregar</button>
            </div>
        </form>
    </div>
</div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-edit-combo">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Editar Combo</span>
            <button type="button" class="btn btn-close" onclick="close_edit_combo()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-edit-combo">
            <div id="container-fields-edit-combo">

            </div>
        </form>
    </div>
</div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-setting-combo">
    <div class="modal-system-sm">
        <div class="head-modal">
            <span>Detalles del Combo</span>
            <button type="button" class="btn btn-close" onclick="close_setting_combo()"></button>
        </div>
        <div class="body-modal">
            <div id="container-details-combo">

            </div>
        </div>
    </div>
</div>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-sections">
    <div class="modal-system-xl">
        <div class="head-modal">
            <span>Personalizar Combo</span>
            <button type="button" class="btn btn-close" onclick="close_add_section()"></button>
        </div>
        <form method="post" action="" class="body-modal" id="form-section-combo">
            <div align="justify"><label for="section-name">Nombre de la Sección</label></div>
            <div class="mb-2">
                <input type="text" name="section-name" id="section-name" class="field-modal" required>
            </div>

            <div align="justify"><label for="section-method">Interactuación con la Sección</label></div>
            <div class="mb-2">
                <select name="section-method" id="section-method" class="field-modal">
                    <option value="1">Productos Siempre Incluidos en el Combo</option>
                    <option value="2">Solo se puede seleccionar uno de los productos</option>
                    <option value="3">Se pueden escoger mas de uno de los productos</option>
                </select>
            </div>

            <div align="justify"><label for="section-instruction">Instrucción</label></div>
            <textarea type="text" name="section-instruction" id="section-instruction" class="mb-2" rows="3"
                placeholder="Instruccion sobre la seleccion de productos de la sección. Sino se ingresa una instrucción se pondra una predeterminada."></textarea>

            <div class="mb-2 d-flex justify-content-end">
                <input type="search" class="field-modal" name="combos-search-product" id="combos-search-product"
                    placeholder="Buscar Producto" style="width: 300px;">
            </div>

            <div class="mb-3" id="products-for-combo"></div>

            <input type="hidden" name="menu-request" value="add-combo-section">
            <div class="total-center"><button type="submit" class="btn-execute" id="submit-add-section">Agregar
                    Sección</button>
        </form>
    </div>
</div>

<script src="combos/script-combos.js"></script>