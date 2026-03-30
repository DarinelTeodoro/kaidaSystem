<?php
include('../../model/conexion.php');
$response = array();

//EDITAR CATEGORIA
if ($_POST['menu-request'] == 'edit-category') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['edit-category-id'];
        $name = $_POST['edit-category-name'];
        $description = $_POST['edit-category-description'];

        $update = $conexion->prepare('UPDATE menu_categorias SET categoria =:category, descripcion = :description WHERE id = :id');
        $update->bindParam(':category', $name);
        $update->bindParam(':description', $description);
        $update->bindParam(':id', $id);
        $update->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Categoria Actualizada';
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





//EDITAR COMBO
if ($_POST['menu-request'] == 'edit-combo') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['edit-combo-id'];
        $name = $_POST['edit-combo-name'];
        $description = $_POST['edit-combo-description'];
        $price = $_POST['edit-combo-price'];

        $update = $conexion->prepare('UPDATE menu_combos SET combo =:combo, descripcion = :description, precio = :price WHERE id = :id');
        $update->bindParam(':combo', $name);
        $update->bindParam(':description', $description);
        $update->bindParam(':price', $price);
        $update->bindParam(':id', $id);
        $update->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Combo Actualizado';
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




//EDITAR PRODUCTO
if ($_POST['menu-request'] == 'edit-product') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();
    $directorio_destino = "files/";

    try {
        $exist = query_product($_POST['edit-product-id']);

        if ($_FILES['edit-product-photo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['edit-product-photo'];
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
            $name_img = $exist['imagen'];
        }

        $id = $_POST['edit-product-id'];
        $name = $_POST['edit-product-name'];
        $description = $_POST['edit-product-description'];
        $price = $_POST['edit-product-price'];
        $categoria = $_POST['edit-product-category'];

        $update = $conexion->prepare('UPDATE menu_productos SET producto =:product, descripcion = :description, imagen = :img, precio = :price, id_categoria = :idcat WHERE id = :id');
        $update->bindParam(':product', $name);
        $update->bindParam(':description', $description);
        $update->bindParam(':img', $name_img);
        $update->bindParam(':price', $price);
        $update->bindParam(':idcat', $categoria);
        $update->bindParam(':id', $id);
        $update->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Producto Actualizado';
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


//EDITAR VARIANTE
if ($_POST['menu-request'] == 'edit-variant') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['edit-variant-id'];
        $name = $_POST['edit-variant-name'];
        $price = $_POST['edit-variant-price'];

        $update = $conexion->prepare('UPDATE menu_variantes SET variante =:variant, precio_agregado = :price WHERE id = :id');
        $update->bindParam(':variant', $name);
        $update->bindParam(':price', $price);
        $update->bindParam(':id', $id);
        $update->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Variante Actualizado';
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



//EDITAR EXTRA
if ($_POST['menu-request'] == 'edit-extra') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['edit-extra-id'];
        $name = $_POST['edit-extra-name'];
        $price = $_POST['edit-extra-price'];

        $update = $conexion->prepare('UPDATE menu_extras SET extra =:extra, precio = :price WHERE id = :id');
        $update->bindParam(':extra', $name);
        $update->bindParam(':price', $price);
        $update->bindParam(':id', $id);
        $update->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Extra Actualizado';
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