<?php
include('../../model/conexion.php');
$response = array();

//eliminar categoria
if ($_POST['menu_request'] == 'delete-category') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_category'];

        $delete = $conexion->prepare('DELETE FROM menu_categorias WHERE id = :id');
        $delete->bindParam(':id', $id);
        $delete->execute();

        $update = $conexion->prepare('UPDATE menu_productos SET id_categoria = 1 WHERE id_categoria = :id');
        $update->bindParam(':id', $id);
        $update->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Categoria Eliminada';
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




//eliminar combo
if ($_POST['menu_request'] == 'delete-combo') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_combo'];

        $delete = $conexion->prepare('DELETE FROM menu_combos WHERE id = :id');
        $delete->bindParam(':id', $id);
        $delete->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Combo Eliminado';
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


//eliminar seccion de combo
if ($_POST['menu_request'] == 'delete-combo-section') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_section'];
        $data_section = query_section($id);
        $combo = $data_section['id_combo'];

        $delete = $conexion->prepare('DELETE FROM menu_combos_secciones WHERE id = :id');
        $delete->bindParam(':id', $id);
        $delete->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Sección Eliminada';
        $response['combo'] = $combo;
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




//eliminar producto
if ($_POST['menu_request'] == 'delete-product') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_product'];

        $delete = $conexion->prepare('DELETE FROM menu_productos WHERE id = :id');
        $delete->bindParam(':id', $id);
        $delete->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Producto Eliminado';
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



//eliminar variante
if ($_POST['menu_request'] == 'delete-variant') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_variant'];

        $delete = $conexion->prepare('DELETE FROM menu_variantes WHERE id = :id');
        $delete->bindParam(':id', $id);
        $delete->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Variante Eliminada';
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



//eliminar extra
if ($_POST['menu_request'] == 'delete-extra') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_extra'];

        $delete = $conexion->prepare('DELETE FROM menu_extras WHERE id = :id');
        $delete->bindParam(':id', $id);
        $delete->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Extra Eliminado';
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