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
        <div>Logo</div>
        <a href="../../home.php" class="btn-home">
            <i class="bi bi-house-fill"></i>
        </a>
    </div>

    <div class="container-main-menu">
        <a href="ticket-create.php" class="btn-floating total-center">
            <div><i class="fi fi-tr-holding-hand-dinner"></i></div>
            <div><span>Agregar</span></div>
        </a>

        <div class="container-title-section align-between">
            <div class="title-section">Comandas</div>
        </div>

        <div class="line"></div>

        <div class="container-filter-tickets">
            <button class="option-filter">Pendientes</button>
            <button class="option-filter">Finalizados</button>
            <button class="option-filter">Todas</button>
        </div>

        <div id="container-tickets-list">
            <table id="table-tickets">
                <thead>
                    <tr>
                        <th>Comanda</th>
                        <th>Cliente</th>
                        <th>Estatus</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>XX</td>
                    </tr>
                </tbody>
            </table>
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