<?php
include('../../../model/conexion.php');
$data_producto = query_product($_POST['id_product']);
?>

<div class="total-center mb-2">
    <label class="label-photo total-center" for="edit-product-photo" id="label-photo-edit-product"
        style="background: rgb(0, 0, 0, 0.2) url(products/files/<?= $data_producto['imagen'] ?>) center center / cover no-repeat;">
        <i class="fi fi-tr-camera-viewfinder"></i>
    </label>
    <input type="file" name="edit-product-photo" id="edit-product-photo" accept="image/png, image/jpeg, image/webp">
</div>

<div align="justify"><label for="edit-product-name">Nombre del Producto</label></div>
<div class="mb-2"><input type="text" name="edit-product-name" id="edit-product-name" class="field-modal"
        value="<?= $data_producto['producto'] ?>" required>
</div>

<div align="justify"><label for="edit-product-description">Descripción</label></div>
<textarea type="text" name="edit-product-description" id="edit-product-description" class="mb-2" rows="5"
    value="<?= $data_producto['descripcion'] ?>"
    placeholder="Descripcion del producto."><?= $data_producto['descripcion'] ?></textarea>

<div align="justify"><label for="edit-product-price">Precio</label></div>
<div class="mb-2"><input type="number" name="edit-product-price" id="edit-product-price" class="field-modal" step="0.01"
        value="<?= $data_producto['precio'] ?>" placeholder="$0.00" required>
</div>

<div align="justify"><label for="edit-product-category">Selecciona Categoria</label></div>
<div class="mb-3">
    <select name="edit-product-category" id="edit-product-category" class="field-modal">
        <option value="1">Sin categoria</option>
        <?php
        $options_cat = query_all_categories('all_registers');

        if ($options_cat) {
            foreach ($options_cat as $option) {
                if ($option['id'] == 1) {
                    continue;
                }
                echo '<option value="' . $option['id'] . '" ' . ($option['id'] == $data_producto['id_categoria'] ? 'selected' : '') . '>' . $option['categoria'] . '</option>';
            }
        }
        ?>
    </select>
</div>

<input type="hidden" name="edit-product-id" value="<?= $data_producto['id'] ?>">
<input type="hidden" name="menu-request" value="edit-product">
<div class="total-center"><button type="submit" class="btn-execute" id="submit-edit-product">Actualizar</button>
</div>


<script>
    /*new TomSelect("#edit-product-category", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });*/

    //visalizar foto de perfil
    document.addEventListener('change', e => {
        if (e.target.id === 'edit-product-photo') {
            showImgUpdateProduct(e);
        }
    });

    function showImgUpdateProduct(e) {
        const cont_img_newuser = document.getElementById('label-photo-edit-product');
        const input = e.target;
        const file = input.files?.[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                cont_img_newuser.style.background =
                    `rgb(0, 0, 0, 0.2) url(${ev.target.result}) center center / cover no-repeat`;
            };
            reader.readAsDataURL(file);
        } else {
            cont_img_newuser.style.background =
                `rgb(0, 0, 0, 0.2) url('products/files/<?= $data_producto['imagen'] ?>') center center / cover no-repeat`;
        }
    }
</script>