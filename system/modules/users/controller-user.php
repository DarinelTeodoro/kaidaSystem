<?php
include('../../model/conexion.php');
$response = array();

//agregar usuario
if ($_POST['user_request'] == 'add-user') {
    $directorio_destino = "files/";

    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $exist = query_user($_POST['user-user']);

        //Verificar que el usuario no exista
        if ($exist) {
            $response['status'] = 400;
            $response['alerta'] = 'Advertencia';
            $response['message'] = 'El usuario ya esta ocupado.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }

        //verificar que se le de acceso por lo menos a un modulo
        if (!isset($_POST['check-modulo-menu']) && !isset($_POST['check-modulo-comandas']) && !isset($_POST['check-modulo-caja']) && !isset($_POST['check-modulo-ventas']) && !isset($_POST['check-modulo-inventario']) && !isset($_POST['check-modulo-usuarios'])) {
            $response['status'] = 400;
            $response['alerta'] = 'Advertencia';
            $response['message'] = 'Seleccione al menos uno de los módulos.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }


        if ($_FILES['user-photo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['user-photo'];
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

        $user_user = $_POST['user-user'];
        $user_name = $_POST['user-name'];
        $password = $_POST['user-password'];
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        // Validar datos obligatorios
        if (empty($user_user) || empty($password) || empty($user_name)) {
            $response['status'] = 400;
            $response['alerta'] = 'Advertencia';
            $response['message'] = 'Todos los campos son obligatorios';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }

        $modulo_menu = isset($_POST['check-modulo-menu']) ? 1 : 0;
        $modulo_comandas = isset($_POST['check-modulo-comandas']) ? 1 : 0;
        $modulo_caja = isset($_POST['check-modulo-caja']) ? 1 : 0;
        $modulo_ventas = isset($_POST['check-modulo-ventas']) ? 1 : 0;
        $modulo_inventario = isset($_POST['check-modulo-inventario']) ? 1 : 0;
        $modulo_usuarios = isset($_POST['check-modulo-usuarios']) ? 1 : 0;

        $insert_user = $conexion->prepare('INSERT INTO usuarios(user, password, name, photo, menu, caja, inventario, comandas, ventas, usuarios) VALUES (:user, :psw, :name, :photo, :menu, :caja, :inventario, :comandas, :ventas, :usuarios)');
        $insert_user->bindParam(':user', $user_user);
        $insert_user->bindParam(':psw', $passwordHashed);
        $insert_user->bindParam(':name', $user_name);
        $insert_user->bindParam(':photo', $name_img);
        $insert_user->bindParam(':menu', $modulo_menu);
        $insert_user->bindParam(':caja', $modulo_caja);
        $insert_user->bindParam(':inventario', $modulo_inventario);
        $insert_user->bindParam(':comandas', $modulo_comandas);
        $insert_user->bindParam(':ventas', $modulo_ventas);
        $insert_user->bindParam(':usuarios', $modulo_usuarios);
        $insert_user->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Usuario Creado Correctamente';
        $response['bg'] = 'success';

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        // Si se subió un archivo pero falló la BD, eliminamos el archivo
        if (isset($path) && file_exists($path) && $insert_name_img !== 'default.webp') {
            unlink($path);
        }

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}

//editar usuario
if ($_POST['user_request'] == 'edit-user') {
    $directorio_destino = "files/";

    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $exist = query_user($_POST['edit-user-user']);

        //Verificar que el usuario no exista
        if (!$exist) {
            $response['status'] = 400;
            $response['alerta'] = 'Advertencia';
            $response['message'] = 'No se encontro el usuario.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }

        //verificar que se le de acceso por lo menos a un modulo
        if (!isset($_POST['edit-check-modulo-menu']) && !isset($_POST['edit-check-modulo-comandas']) && !isset($_POST['edit-check-modulo-caja']) && !isset($_POST['edit-check-modulo-ventas']) && !isset($_POST['edit-check-modulo-inventario']) && !isset($_POST['edit-check-modulo-usuarios'])) {
            $response['status'] = 400;
            $response['alerta'] = 'Advertencia';
            $response['message'] = 'Seleccione al menos uno de los módulos.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }


        if ($_FILES['edit-user-photo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['edit-user-photo'];
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
            $name_img = $exist['photo'];
        }

        $user_user = $_POST['edit-user-user'];
        $user_name = $_POST['edit-user-name'];
        $password = !empty($_POST['edit-user-password']) ? $_POST['edit-user-password'] : $exist['password'];
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        // Validar datos obligatorios
        if (empty($user_user) || empty($user_name)) {
            $response['status'] = 400;
            $response['alerta'] = 'Advertencia';
            $response['message'] = 'Hay campos obligatorios.';
            $response['bg'] = 'warning';
            echo json_encode($response);
            exit;
        }

        $modulo_menu = isset($_POST['edit-check-modulo-menu']) ? 1 : 0;
        $modulo_comandas = isset($_POST['edit-check-modulo-comandas']) ? 1 : 0;
        $modulo_caja = isset($_POST['edit-check-modulo-caja']) ? 1 : 0;
        $modulo_ventas = isset($_POST['edit-check-modulo-ventas']) ? 1 : 0;
        $modulo_inventario = isset($_POST['edit-check-modulo-inventario']) ? 1 : 0;
        $modulo_usuarios = isset($_POST['edit-check-modulo-usuarios']) ? 1 : 0;

        $update_user = $conexion->prepare('UPDATE usuarios SET password = :psw, name = :name, photo = :photo, menu = :menu, caja = :caja, inventario = :inventario, comandas = :comandas, ventas = :ventas, usuarios = :usuarios WHERE user = :user');
        $update_user->bindParam(':user', $user_user);
        $update_user->bindParam(':psw', $passwordHashed);
        $update_user->bindParam(':name', $user_name);
        $update_user->bindParam(':photo', $name_img);
        $update_user->bindParam(':menu', $modulo_menu);
        $update_user->bindParam(':caja', $modulo_caja);
        $update_user->bindParam(':inventario', $modulo_inventario);
        $update_user->bindParam(':comandas', $modulo_comandas);
        $update_user->bindParam(':ventas', $modulo_ventas);
        $update_user->bindParam(':usuarios', $modulo_usuarios);
        $update_user->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 201;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Usuario Actualizado Exitosamente.';
        $response['bg'] = 'success';

    } catch (Exception $e) {
        // Si algo salió mal, revertimos la transacción
        $conexion->rollBack();

        // Si se subió un archivo pero falló la BD, eliminamos el archivo
        if (isset($path) && file_exists($path)) {
            unlink($path);
        }

        $response['status'] = 500;
        $response['alerta'] = 'Error';
        $response['message'] = $e->getMessage();
        $response['bg'] = 'danger';
    }
}

//eliminar usuario
if ($_POST['user_request'] == 'delete-user') {
    // Iniciar transacción
    $conexion = new Conexion();
    $conexion->beginTransaction();

    try {
        $id = $_POST['id_user'];

        $delete = $conexion->prepare('UPDATE usuarios SET status = 0 WHERE id = :user');
        $delete->bindParam(':user', $id);
        $delete->execute();

        // Si todo salió bien, confirmamos la transacción
        $conexion->commit();

        $response['status'] = 200;
        $response['alerta'] = 'Éxito';
        $response['message'] = 'Usuario Eliminado';
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