<?php
include('../../model/conexion.php');
$response = array();

//AGREGAR CATEGORIA
if ($_POST['menu-request'] == 'add-category') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $name = $_POST['category-name'];
        $description = $_POST['category-description'];

        $insert = $conexion->prepare('INSERT INTO menu_categorias(categoria, descripcion) VALUES (:category, :description)');
        $insert->bindParam(':category', $name);
        $insert->bindParam(':description', $description);
        $insert->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Categoria Agregada';
        $response['bg'] = 'success';

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}




//AGREGAR COMBO
if ($_POST['menu-request'] == 'add-combo') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $name = $_POST['combo-name'];
        $description = $_POST['combo-description'];
        $price = $_POST['combo-price'];

        $insert = $conexion->prepare('INSERT INTO menu_combos(combo, descripcion, precio) VALUES (:combo, :description, :price)');
        $insert->bindParam(':combo', $name);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':price', $price);
        $insert->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Combo Agregado';
        $response['bg'] = 'success';

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}


//AGREGAR SECCION AL COMBO
if ($_POST['menu-request'] == 'add-combo-section') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $name = $_POST['section-name'];
        $type = $_POST['section-method'];

        if (empty($_POST['section-instruction'])) {
            if ($type == 1) {
                $instruction = 'Estos productos estan inlcuidos en el combo.';
            } else if ($type == 2){
                $instruction = 'Solo se puede seleccionar uno de los siguientes productos.';
            } else if ($type == 3){
                $instruction = 'Puede seleccionar uno o mas productos de los siguientes.';
            }
        } else {
            $instruction = $_POST['section-instruction'];
        }

        if (!isset($_POST['producto-combo'])) {
            $response['status'] = 400;
            $response['alerta'] = 'Alerta';
            $response['message'] = 'Debe de Seleccionar al menos un producto.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }

        $productos = $_POST['producto-combo'] ?? [];
        $productos = array_values(array_unique(array_map('intval', (array) $productos)));
        $productos_json = json_encode($productos, JSON_UNESCAPED_UNICODE);

        $id_combo = $_POST['section-combo-id'];

        $insert = $conexion->prepare('INSERT INTO menu_combos_secciones(section, instruction, type, products, id_combo) VALUES (:section, :instruction, :type, :products, :id_combo)');
        $insert->bindParam(':section', $name);
        $insert->bindParam(':type', $type);
        $insert->bindParam(':instruction', $instruction);
        $insert->bindParam(':products', $productos_json);
        $insert->bindParam(':id_combo', $id_combo);
        $insert->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Sección Agregada';
        $response['combo'] = $id_combo;
        $response['bg'] = 'success';

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}




//AGREGAR PRODUCTO
if ($_POST['menu-request'] == 'add-product') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();
    $directorio_destino = "products/files/";

    try {
        if ($_FILES['product-photo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['product-photo'];
            $nombre_original = $archivo['name'];
            $tipo_temporal = $archivo['tmp_name'];
            $tamano = $archivo['size'];
            $error = $archivo['error'];

            // Validar tamaño del archivo (ejemplo: máximo 5MB)
            $tamano_maximo = 5 * 1024 * 1024; // 5MB
            if ($tamano > $tamano_maximo) {
                $response['status'] = 400;
                $response['alerta'] = 'Error';
                $response['message'] = 'El archivo es demasiado grande. Máximo 5MB.';
                $response['bg'] = 'danger';
                echo json_encode($response);
                exit;
            }

            // Extensión y validacion de formato
            $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
            $formatos_permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($extension, $formatos_permitidos)) {
                $response['status'] = 400;
                $response['alerta'] = 'Error';
                $response['message'] = 'Formato de archivo no permitido. Use JPG, PNG, GIF o WEBP.';
                $response['bg'] = 'danger';
                echo json_encode($response);
                exit;
            }

            // Generar nombre único para evitar sobreescrituras
            $img_name = uniqid('img_', true) . '.' . $extension;
            $path = $directorio_destino . $img_name;

            // Crear directorio si no existe
            if (!is_dir($directorio_destino)) {
                mkdir($directorio_destino, 0755, true);
            }

            // Mover el archivo temporal a su ubicación final
            if (!move_uploaded_file($tipo_temporal, $path)) {
                $response['status'] = 500;
                $response['alerta'] = 'Error';
                $response['message'] = 'Error al subir el archivo';
                $response['bg'] = 'danger';
                echo json_encode($response);
                exit;
            }

            $name_img = $img_name;
        } else {
            $name_img = 'default.webp';
        }

        $name = $_POST['product-name'];
        $description = $_POST['product-description'];
        $price = $_POST['product-price'];
        $category = $_POST['product-category'];

        $insert = $conexion->prepare('INSERT INTO menu_productos(producto, descripcion, imagen, precio, id_categoria) VALUES (:product, :description, :img, :precio, :idcat)');
        $insert->bindParam(':product', $name);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':img', $name_img);
        $insert->bindParam(':precio', $price);
        $insert->bindParam(':idcat', $category);
        $insert->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Producto Agregado';
        $response['bg'] = 'success';
        $response['idcat'] = $category;

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}



//AGREGAR VARIANTE
if ($_POST['menu-request'] == 'add-variant') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        if ($_POST['variant-product'] == 0) {
            $response['status'] = 400;
            $response['alerta'] = 'Alerta';
            $response['message'] = 'Seleccione un producto para la variante.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }

        $id_product = $_POST['variant-product'];
        $variant = $_POST['variant-name'];
        $price = $_POST['variant-price'];

        $insert = $conexion->prepare('INSERT INTO menu_variantes(variante, precio_agregado, id_producto) VALUES (:variant, :price, :id)');
        $insert->bindParam(':variant', $variant);
        $insert->bindParam(':price', $price);
        $insert->bindParam(':id', $id_product);
        $insert->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Variante Agregado';
        $response['bg'] = 'success';
        $response['idprod'] = $id_product;

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}



//AGREGAR EXTRA
if ($_POST['menu-request'] == 'add-extra') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $name = $_POST['extra-name'];
        $price = $_POST['extra-price'];

        $insert = $conexion->prepare('INSERT INTO menu_extras(extra, precio) VALUES (:extra, :price)');
        $insert->bindParam(':extra', $name);
        $insert->bindParam(':price', $price);
        $insert->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Extra Agregado';
        $response['bg'] = 'success';

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}

echo json_encode($response);
?>