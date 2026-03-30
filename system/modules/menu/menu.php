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
    <link href="style-menu.css" rel="stylesheet">
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
        <div class="container-btn-sections">
            <button type="button" class="btn-section-menu" data-path="categories/menu-categories.php" data-list="categories/categories-list.php"
                disabled="true">Categorias</button>
            <button type="button" class="btn-section-menu" data-path="combos/menu-combos.php" data-list="combos/combos-list.php">Combos</button>
            <button type="button" class="btn-section-menu" data-path="products/menu-products.php" data-list="products/products-list.php">Productos</button>
            <button type="button" class="btn-section-menu" data-path="variants/menu-variants.php" data-list="variants/variants-list.php">Variantes</button>
            <button type="button" class="btn-section-menu" data-path="extras/menu-extras.php" data-list="extras/extras-list.php">Extras</button>
        </div>

        <div class="line"></div>

        <div id="container-content-section"></div>
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
<script src="script-menu.js"></script>