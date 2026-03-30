<?php
session_start();
include('model/conexion.php');
include('../libraries.php');

if (empty($_SESSION['USERLOGED'])) {
    echo '<script>window.location.href = "../index.php";</script>';
} else {
    $userloged = query_user($_SESSION['USERLOGED']);
}

if (isset($_POST['destroy-session'])) {
    session_destroy();
    echo '<script>window.location.href = "../index.php";</script>';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SYSTEM DEMO</title>
    <link href="../style.css" rel="stylesheet">
    <link href="style-home.css" rel="stylesheet">
</head>

<body id="html-body">

    <!--Contenido-->
    <div class="navbar fixed-top align-between">
        <div>Logo</div>
        <button type="button" class="btn-info-user" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-menu"
            aria-controls="offcanvas-menu">
            <span><?= $userloged['name'] ?></span>
            <div class="photo-perfil"
                style="background: rgb(0, 0, 0, 0) url('modules/users/files/<?= $userloged['photo'] ?>') center center / cover no-repeat;">
            </div>
        </button>
    </div>


    <!--menu-->
    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvas-menu"
        aria-labelledby="offcanvas-menu">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center">
                <div class="info-photo-perfil"
                    style="background: rgb(0, 0, 0, 0) url('modules/users/files/<?= $userloged['photo'] ?>') center center / cover no-repeat;">
                </div>

                <span class="name-session"><?= $userloged['name'] ?></span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php
            if ($userloged['menu'] == 1) {
                ?>
                <a href="modules/menu/menu.php" class="option-menu total-center">Menu</a>
                <?php
            }
            if ($userloged['caja'] == 1) {
                ?>
                <a href="modules/caja/caja.php" class="option-menu total-center">Caja</a>
                <?php
            }
            if ($userloged['comandas'] == 1) {
                ?>
                <a href="modules/comandas/comandas.php" class="option-menu total-center">Comandas</a>
                <?php
            }
            if ($userloged['ventas'] == 1) {
                ?>
                <a href="modules/ventas/ventas.php" class="option-menu total-center">Ventas</a>
                <?php
            }
            if ($userloged['usuarios'] == 1) {
                ?>
                <a href="modules/usuarios/usuarios.php" class="option-menu total-center">Usuarios</a>
                <?php
            }
            ?>
        </div>
        <form method="post" action="">
            <button class="btn-logout" name="destroy-session">Cerrar Sesión</button>
        </form>
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

<script src="../script.js"></script>
<script src="script-home.js"></script>