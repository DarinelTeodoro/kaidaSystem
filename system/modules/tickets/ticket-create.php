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
    <link href="style-tickets.css" rel="stylesheet">
</head>

<body id="html-body">
    <!--Contenido-->
    <div class="navbar fixed-top align-between">
        <a href="tickets.php" class="btn-back" id="btn-back">
            <i class="fi fi-tr-arrow-small-left"></i><span>Volver</span>
        </a>

        <button type="button" class="btn-car" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
            aria-controls="offcanvasScrolling"><i class="fi fi-tr-cart-arrow-down"></i></button>
    </div>

    <!--Productos seleccionables-->
    <div class="column-products">
        <div class="align-between">
            <span class="title-section">Productos</span>
            <input type="search" class="field-modal" id="search-product" style="width: 300px;"
                placeholder="Buscar Producto">
        </div>
        <div class="line"></div>
        <div class="products-for-ticket" id="products-for-ticket"></div>
    </div>

    <!--Carrito comanda-->
    <div class="offcanvas offcanvas-end show" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
        id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="head-column-details align-between">
            <span>Productos de la Comanda</span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="body-column-details">
            <div>
                <div align="justify"><label for="type-delivery">Tipo de Entrega</label></div>
                <select name="type-delivery" id="type-delivery" class="field-modal mb-2">
                    <option value="1">Para comer aqui</option>
                    <option value="2">Para llevar</option>
                </select>

                <div align="justify"><label for="delivary-client">Cliente</label></div>
                <div class="mb-2">
                    <input type="text" name="delivary-client" id="delivary-client" class="field-modal" required>
                </div>

                <div id="container-cost-delivery">
                    <div align="justify"><label for="delivery-price">Costo Entrega</label></div>
                    <div class="mb-2">
                        <input type="number" name="delivery-price" id="delivery-price" class="field-modal" step="0.01"
                            placeholder="$0.00">
                    </div>
                </div>

                <div class=""></div>

            </div>
        </div>

        <div class="total-column-details align-between">
            <span>Total</span>
            <span class="text-success">$0.00</span>
        </div>

        <div class="footer-column-details">
            <button class="btn-execute" name="send-ticket" id="send-ticket">Enviar Comanda</button>
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
<script src="script-tickets.js"></script>
<script src="script-create.js"></script>