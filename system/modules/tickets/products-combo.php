<?php
include('../../model/conexion.php');

$id_combo = $_POST['id_combo'];
$data_grupos = query_all_sections($id_combo);

$combo = query_combo($id_combo);
$comboNombre = $combo ? $combo['combo'] : 'Combo';
$comboPrecio = $combo ? $combo['precio'] : 0;
?>

<input type="hidden" id="combo_id_actual" value="<?= (int) $id_combo ?>">
<input type="hidden" id="combo_nombre_actual" value="<?= htmlspecialchars($comboNombre) ?>">
<input type="hidden" id="combo_precio_actual" value="<?= htmlspecialchars($comboPrecio) ?>">

<?php
if ($data_grupos) {

    foreach ($data_grupos as $grupo) {

        $grupo_id = $grupo['id'];
        $grupo_tipo = $grupo['type'];
        $tipo_js = $grupo['type'] == 1 ? 'predeterminado' : ($grupo['type'] == 3 ? 'multiple' : 'unico');
        $grupo_nombre = $grupo['section'];
        $instruccion = $grupo['instruction'];

        $productos_guardados = $grupo['products'];
        $productos = json_decode($productos_guardados, true) ?? [];

        if (!empty($productos)) {

            echo "<div class='mt-2 mb-2' data-grupo-id='{$grupo_id}' data-tipo='{$tipo_js}'>";
            echo "<div class='title-combo-section lh-1' align='justify'>
                <div><span class='title-section'>{$grupo_nombre}</span></div>
                <div><span class='instruction-section'>{$instruccion}</span></div>
            </div>";

            foreach ($productos as $producto_id) {

                $prod = query_product($producto_id);

                if (!$prod)
                    continue;

                if ($grupo_tipo == 1) {
                    echo "
                    <div class='pt-1 pb-1 d-grid align-items-center' style='grid-template-columns: auto 1fr;'>
                        <i class='bi bi-check-lg me-1' style='color: #0a6f1e; font-size: 1.3rem;'></i><input value='{$prod['producto']}' data-idproducto='{$prod['id']}' readonly style='border: none; cursor: default; background: #ffffff;'>
                    </div>
                    ";
                } else {
                    $input_type = ($grupo_tipo === 3) ? 'checkbox' : 'radio';

                    // IMPORTANTE:
                    // radio → mismo name por grupo
                    // checkbox → name como array

                    $input_name = ($grupo_tipo === 3)
                        ? "grupo_{$grupo_id}[]"
                        : "grupo_{$grupo_id}";

                    echo "
                        <div class='btn-group d-grid' role='group'>
                            <input 
                                class='btn-check'
                                type='{$input_type}'
                                name='{$input_name}'
                                value='{$producto_id}'
                                id='{$grupo_id}prod_{$producto_id}'
                            >
                            <label class='btn btn-outline-primary label_check' for='{$grupo_id}prod_{$producto_id}' style='border-radius: 0px; margin-top: 6px;'>
                                {$prod['producto']}
                            </label>
                        </div>
                    ";
                }
            }

            echo "</div>";
        }
    }
    echo '<div class="d-grid mb-2">
        <div align="justify"><label for="notas_combo">Notas</label></div>
        <textarea name="notas_combo" id="notas_combo" rows="3" placeholder="Sin cebolla, poco hielo, etc."></textarea>
    </div>';
    echo "<div class='d-grid'><button type='button' class='btn-add' onclick='combo_to_car()'>Agregar al Carrito</button></div>";
} else {
    ?>
    <div class="d-flex align-items-center justify-content-center ps-3 pe-3" style="width: 100%; height: 100%;">
        <span class="fw-bold">Combo no Disponible</span>
    </div>
    <?php
}
?>