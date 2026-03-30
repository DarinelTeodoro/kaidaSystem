<?php
include('../../../model/conexion.php');
$data_combo = query_combo($_POST['id_combo']);
?>

<div class="align-between">
    <span class="details-name-combo text-primary"><?= $data_combo['combo'] ?></span>
    <button class="btn-add" onclick="open_add_section()">Agregar</button>
</div>
<div class="line"></div>

<?php
$data_secciones = query_all_sections($_POST['id_combo']);

if ($data_secciones) {
    foreach ($data_secciones as $section) {
        ?>

        <div class="d-flex items-center content-between" style="padding: 0px 10px;">
            <div style="line-height: 15px;">
                <div><span><?= $section['section'] ?></span></div>
                <div><span class="text-muted" style="font-size: 0.8rem;"><?= $section['instruction'] ?></span></div>
            </div>
            <div>
                <button class="btn-delete-auxiliary delete-section" data-section="<?= $section['id'] ?>">
                    <svg viewBox="0 0 24 24" height="20" width="20">
                        <path
                            d="M19.756,5.993c-.068-.016-.862-.202-2.107-.396l-.661-3.075c-.126-.583-.584-1.036-1.169-1.155-.074-.015-1.839-.368-3.818-.368s-3.745,.354-3.819,.369c-.584,.119-1.042,.572-1.168,1.154l-.661,3.075c-1.244,.193-2.038,.379-2.106,.395-.807,.19-1.306,.999-1.115,1.805,.161,.684,.767,1.146,1.439,1.155-.1,1.28-.173,2.786-.173,4.227,0,4.659,.752,8.084,.784,8.228,.078,.353,.342,.636,.688,.74,.116,.035,2.875,.853,6.132,.853s6.015-.817,6.131-.852c.348-.104,.611-.388,.689-.743,.032-.145,.783-3.605,.783-8.225,0-1.449-.072-2.953-.171-4.228,.671-.01,1.275-.472,1.437-1.155,.19-.806-.308-1.613-1.113-1.804Zm-10.257-.763l.231-1.072c1.194-.157,3.34-.158,4.54,0l.23,1.072c-.913-.062-1.792-.091-2.674-.087-.008,0-.016,0-.025,0-.757,.004-1.517,.033-2.303,.087Zm1.336,12.715c0,.552-.447,1-1,1s-1-.448-1-1v-7.231c0-.552,.447-1,1-1s1,.448,1,1v7.231Zm4.33,0c0,.552-.447,1-1,1s-1-.448-1-1v-7.231c0-.552,.447-1,1-1s1,.448,1,1v7.231Z"
                            fill="currentColor" />
                    </svg>
                </button>
            </div>
        </div>

        <?php

        $productos = json_decode($section['products'], true) ?? [];
        if (!empty($productos)) {
            echo '<ol style="margin: 5px 5px 18px 5px; font-family: Delius; font-size: 0.9rem;">';
            foreach ($productos as $producto) {
                $prod = query_product($producto);
                echo '
                    <li>' . $prod['producto'] . '</li>
                ';
            }
            echo '</ol>';
        }
    }
} else {
    echo '<div class="container-not-charge total-center"><i class="fi fi-tr-risk-alt"></i><span class="text-not-charge">No hay Secciones Registradas</span></div>';
}
?>


<div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-sections">
    <div class="modal-system-xl">
        <div class="head-modal">
            <span>Personalizar Combo</span>
            <button type="button" class="btn btn-close" onclick="close_add_section()"></button>
        </div>
        <form method="post" action="" class="body-modal">
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
                <input type="search" class="field-modal" id="search-product" placeholder="Buscar Producto"
                    style="width: 300px;">
            </div>

            <div id="products-for-combo"></div>

            <input type="hidden" name="section-combo-id" id="section-combo-id" value="<?= $data_combo['id'] ?>">
            <input type="hidden" name="menu-request" value="add-combo-section">
            <div class="total-center"><button type="submit" class="btn-execute" id="submit-add-section">Agregar
                    Sección</button>
        </form>
    </div>
</div>