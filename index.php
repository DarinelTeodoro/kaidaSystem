<?php
session_start();
include('libraries.php');

if (!empty($_SESSION['USERLOGED'])) {
  echo '<script>window.location.href = "system/home.php";</script>';
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SYSTEM DEMO</title>
  <link href="style.css" rel="stylesheet">
  <link href="style-login.css" rel="stylesheet">
</head>

<body id="html-body">

  <!--Contenedor Principal-->
  <div class="container-login-main fullscreen total-center">
    <form method="post" action="" id="form-login">
      <div class="mb-2 lh-1">
        <span class="title-login">Iniciar Sesión</span>
      </div>

      <div class="container-field mb-3">
        <div class="d-flex"><label for="key-user">Usuario</label></div>
        <div class="d-grid"><input type="text" name="key-user" id="key-user" class="field-login"></div>
      </div>

      <div class="container-field mb-4">
        <div class="d-flex"><label for="key-password">Contraseña</label></div>
        <div class="d-grid"><input type="password" name="key-password" id="key-password" class="field-login"></div>
      </div>

      <div>
        <button type="submit" class="btn-execute" id="submit-login-form">Ingresar</button>
      </div>
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

<script src="script.js"></script>
<script src="script-login.js"></script>