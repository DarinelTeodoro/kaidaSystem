<?php
session_start();
include('model/conexion.php');
include('../librerias.php');

if (empty($_SESSION['USERLOGED'])) {
    echo '<script>window.location.href = "../index.php";</script>';
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
    <link href="../style.css" rel="stylesheet">
    <link href="style-home.css" rel="stylesheet">
</head>

<body id="html-body">
    <!--Pre-loader-->
    <div class="container-preloader fixed-top fullscreen object-visible" id="view-preloader">
        <div class="loader"></div>
    </div>


    <div class="navbar align-between">
        <div>Logo</div>
        <button type="button" class="btn-info-user">
            <span><?= $userloged['name'] ?></span>
            <div class="photo-perfil" style="background: rgb(0, 0, 0, 0) url('modules/users/files/<?= $userloged['photo'] ?>') center center / cover no-repeat;"></div>
        </button>
    </div>



    <!--Alerta-->
    <div class="system-alert fixed-top fullscreen total-center" id="system-alert">
        <div class="content-alert">
            <div class="head-alert total-center text-uppercase">
                <span id="title-alert">TEXT ALERT</span>
            </div>
            <div class="body-alert total-center">
                <div class="mb-2"><span id="text-alert">Message Alert</span></div>
                <div id="container-btn-acept">
                    <button type="button" class="btn-accept" onclick="hide_alert()">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script src="../script.js"></script>