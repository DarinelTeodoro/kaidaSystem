<?php
session_start();
include('../../model/conexion.php');
include('../../../libraries.php');

if (empty($_SESSION['USERLOGED'])) {
    echo '<script>window.location.href = "../../../index.php";</script>';
} else {
    $userloged = query_user($_SESSION['USERLOGED']);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SYSTEM DEMO</title>
    <link href="../../../style.css" rel="stylesheet">
    <link href="style-users.css" rel="stylesheet">
</head>

<body id="html-body">
    <!--Contenido-->
    <div class="navbar fixed-top align-between">
        <div>Logo</div>
        <a href="../../home.php" class="btn-home">
            <i class="bi bi-house-fill"></i>
        </a>
    </div>

    <div class="container-main-menu">
        <div class="container-title-section align-between">
            <div class="title-section">Usuarios</div>
            <button type="button" class="btn-add" onclick="open_add_user()">Agregar</button>
        </div>

        <div class="line"></div>

        <div id="container-users-list"></div>
    </div>

    <!--Modal agrgar usuario-->
    <div class="fade-modal-system fullscreen total-center fixed-top" id="modal-add-user">
        <div class="modal-system-sm">
            <div class="head-modal">
                <span>Agregar Usuario</span>
                <button type="button" class="btn btn-close" onclick="close_add_user()"></button>
            </div>
            <form method="post" action="" class="body-modal" id="form-add-user" enctype="multipart/form-data">
                <div class="total-center mb-2">
                    <label class="label-photo total-center" for="user-photo" id="label-photo-new-user"
                        style="background: rgb(0, 0, 0, 0.2) url(files/default.webp) center center / cover no-repeat;">
                        <i class="fi fi-tr-camera-viewfinder"></i>
                    </label>
                    <input type="file" name="user-photo" id="user-photo" accept="image/png, image/jpeg, image/webp">
                </div>

                <div align="justify"><label for="user-user">Usuario</label></div>
                <div class="mb-2"><input type="text" name="user-user" id="user-user" class="field-modal" required>
                </div>

                <div align="justify"><label for="user-name">Nombre del Usuario</label></div>
                <div class="mb-2"><input type="text" name="user-name" id="user-name" class="field-modal" required>
                </div>

                <div align="justify"><label for="user-password">Contraseña</label></div>
                <div class="mb-2"><input type="password" name="user-password" id="user-password" class="field-modal"
                        required></div>

                <div align="justify"><label>¿A que modulos puede acceder?</label></div>
                <div class="row mb-3">
                    <div class="col-6 p-1">
                        <input type="checkbox" class="btn-check" name="check-modulo-menu" id="check-modulo-menu"
                            autocomplete="off">
                        <label class="btn btn-outline-success check-permiss-module"
                            for="check-modulo-menu">Menu</label><br>
                    </div>
                    <div class="col-6 p-1">
                        <input type="checkbox" class="btn-check" name="check-modulo-comandas" id="check-modulo-comandas"
                            autocomplete="off">
                        <label class="btn btn-outline-success check-permiss-module"
                            for="check-modulo-comandas">Comandas</label><br>
                    </div>
                    <div class="col-6 p-1">
                        <input type="checkbox" class="btn-check" name="check-modulo-caja" id="check-modulo-caja"
                            autocomplete="off">
                        <label class="btn btn-outline-success check-permiss-module"
                            for="check-modulo-caja">Caja</label><br>
                    </div>
                    <div class="col-6 p-1">
                        <input type="checkbox" class="btn-check" name="check-modulo-ventas" id="check-modulo-ventas"
                            autocomplete="off">
                        <label class="btn btn-outline-success check-permiss-module"
                            for="check-modulo-ventas">Ventas</label><br>
                    </div>
                    <div class="col-6 p-1">
                        <input type="checkbox" class="btn-check" name="check-modulo-usuarios" id="check-modulo-usuarios"
                            autocomplete="off">
                        <label class="btn btn-outline-success check-permiss-module"
                            for="check-modulo-usuarios">Usuarios</label><br>
                    </div>
                </div>

                <div class="total-center">
                    <input type="hidden" name="user_request" value="add-user">
                    <button type="submit" class="btn-execute" id="submit-add-user">Agregar</button>
                </div>
            </form>
        </div>
    </div>


    <div class="fade-modal-system fullscreen total-center fixed-top" id="modal-edit-user">
        <div class="modal-system-sm">
            <div class="head-modal">
                <span>Editar Usuario</span>
                <button type="button" class="btn btn-close" onclick="close_edit_user()"></button>
            </div>
            <form method="post" action="" class="body-modal" id="form-edit-user" enctype="multipart/form-data">
                <div id="container-fields-edit-user">

                </div>
            </form>
        </div>
    </div>


    <!--Alerta-->
    <div class="system-alert fixed-top fullscreen total-center" id="system-alert">
        <div class="content-alert">
            <div class="head-alert total-center text-uppercase" id="head-alert">
                <span id="title-alert">TEXT ALERT</span>
            </div>
            <div class="body-alert total-center">
                <div class="mb-3"><span id="text-alert">Message Alert</span></div>
                <div id="container-btn-acept">
                    <button type="button" class="btn-accept" onclick="hide_alert()">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Pre-loader-->
    <div class="container-preloader fixed-top fullscreen object-visible" id="view-preloader">
        <div class="loader"></div>
    </div>
</body>

</html>

<script src="../../../script.js"></script>
<script src="script-users.js"></script>