<?php
session_start();
include('../model/conexion.php');

$response = array(); // Inicia array para response

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['key-user']) && !empty($_POST['key-password'])) {
        $usuario = $_POST['key-user'];
        $password = $_POST["key-password"];

        $data_user = query_user($usuario);

        if ($data_user) { // Si hay un usuario
            $DB_PASSWORD = $data_user['password']; // Trae la contraseña de la bd

            if (password_verify($password, $DB_PASSWORD)) { // Si las contraseñas coinciden
                $_SESSION['USERLOGED'] = $data_user['user'];

                $response['access'] = 'ACCESSGRANTED';
                $response['path'] = 'system/main.php';

            } else {
                $response['bg'] = 'danger';
                $response['access'] = 'Acceso Denegado';
                $response['message'] = 'Correo/Contraseña Incorrecta';
            }
        } else {
            $response['bg'] = 'danger';
            $response['access'] = 'Acceso Denegado';
            $response['message'] = 'Correo/Contraseña Incorrecta';
        }
    } else {
        $response['bg'] = 'warning';
        $response['access'] = 'Acceso Denegado';
        $response['message'] = 'No deje Campo(s) Vacio(s)';
    }
} else {
    $response['bg'] = 'danger';
    $response['access'] = 'Acceso Denegado';
    $response['message'] = 'Error de Operación';
}

// Conversion de array a JSON para enviarlo al cliente
echo json_encode($response);
?>