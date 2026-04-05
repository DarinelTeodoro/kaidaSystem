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

        <button type="button" class="btn-car" id="btn-car" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
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
    <form method="post" action="" class="offcanvas offcanvas-end show" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
        id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="head-column-details align-between">
            <span>Productos de la Comanda</span>
            <button type="button" class="btn-close btn-close-car" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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

                <div id="productos-in-car">
                    <div class="text-center p-3 text-muted">No hay productos en el carrito. <span class="alert-dismiss">Cierra el carrito para ver el catalogo de productos completo.</span></div>
                </div>
            </div>
        </div>

        <div class="total-column-details align-between">
            <span>Total</span>
            <span class="text-success" id="text-total-car">$0.00</span>
        </div>

        <div class="footer-column-details">
            <button type="submit" class="btn-execute" name="send-ticket" id="send-ticket">Enviar Comanda</button>
        </div>
    </form>


<!--Modal Armar Combo-->
    <div class="fade-modal-system fullscreen total-center fixed-top" id="modal-arm-combo">
        <div class="modal-system-sm">
            <div class="head-modal">
                <span>Armar Combo</span>
                <button type="button" class="btn btn-close" onclick="close_arm_combo()"></button>
            </div>
            <div class="body-modal" id="arm-combo">

            </div>
        </div>
    </div>

    <!--Modal Seleccionar Variante de producto-->
    <div class="fade-modal-system fullscreen total-center fixed-top" id="modal-select-variant">
        <div class="modal-system-sm">
            <div class="head-modal">
                <span>Seleccionar Variante</span>
                <button type="button" class="btn btn-close" onclick="close_select_variant()"></button>
            </div>
            <div class="body-modal" id="select-variant">

            </div>
        </div>
    </div>

    <!--Modal Select Exxtras-->
    <div class="fade-modal-system fullscreen total-center fixed-top" id="modal-select-extras">
        <div class="modal-system-sm">
            <div class="head-modal">
                <span id="modal-extra-title">Agregar Extras</span>
                <button type="button" class="btn btn-close" onclick="close_extras_modal()"></button>
            </div>
            <div class="body-modal pt-0" id="select-extras">

            </div>
        </div>
    </div>

    <!--Modal Select Exxtras-->
    <div class="fade-modal-system fullscreen total-center fixed-top" id="modal-edit-select-extras">
        <div class="modal-system-sm">
            <div class="head-modal">
                <span id="modal-edit-extra-title">Editar Extras</span>
                <button type="button" class="btn btn-close" onclick="close_edit_extras_modal()"></button>
            </div>
            <div class="body-modal pt-0" id="edit-select-extras">

            </div>
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