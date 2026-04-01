<?php
include('../../model/conexion.php');
$data_user = query_user_byId($_POST['id_user']);
?>
<div class="total-center">
    <label class="label-photo total-center" for="edit-user-photo" id="label-photo-edit-user"
        style="background: rgb(0, 0, 0, 0.2) url(files/<?= $data_user['photo'] ?>) center center / cover no-repeat;">
        <i class="fi fi-tr-camera-viewfinder"></i>
    </label>
    <input type="file" name="edit-user-photo" id="edit-user-photo" accept="image/png, image/jpeg, image/webp">
</div>

<div align="justify"><label for="edit-user-user">Usuario</label></div>
<div class="mb-2"><input type="text" name="edit-user-user" id="edit-user-user" class="field-modal"
        value="<?= $data_user['user'] ?>" readonly></div>

<div align="justify"><label for="edit-user-name">Nombre del Usuario</label></div>
<div class="mb-2"><input type="text" name="edit-user-name" id="edit-user-name" class="field-modal"
        value="<?= $data_user['name'] ?>" required></div>

<div align="justify"><label for="edit-user-password">Contraseña</label></div>
<div class="mb-2" align="justify"><input type="password" name="edit-user-password" id="edit-user-password" class="field-modal"
        placeholder="********">
<span class="text-secondary" style="font-family: 'Buda'; font-size: 0.9rem;">*Llenar este campo solo si se desea cambiar la contraseña.</span></div>

<div align="justify"><label>¿A que modulos puede acceder?</label></div>
<div class="row mb-3">
    <div class="col-6 p-1">
        <input type="checkbox" class="btn-check" name="edit-check-modulo-menu" id="edit-check-modulo-menu"
            <?= $data_user['menu'] == 1 ? 'checked' : '' ?>>
        <label class="btn btn-outline-success check-permiss-module" for="edit-check-modulo-menu">Menu</label>
    </div>
    <div class="col-6 p-1">
        <input type="checkbox" class="btn-check" name="edit-check-modulo-comandas" id="edit-check-modulo-comandas"
            <?= $data_user['comandas'] == 1 ? 'checked' : '' ?>>
        <label class="btn btn-outline-success check-permiss-module" for="edit-check-modulo-comandas">Comandas</label>
    </div>
    <div class="col-6 p-1">
        <input type="checkbox" class="btn-check" name="edit-check-modulo-caja" id="edit-check-modulo-caja"
            <?= $data_user['caja'] == 1 ? 'checked' : '' ?>>
        <label class="btn btn-outline-success check-permiss-module" for="edit-check-modulo-caja">Caja</label>
    </div>
    <div class="col-6 p-1">
        <input type="checkbox" class="btn-check" name="edit-check-modulo-ventas" id="edit-check-modulo-ventas"
            <?= $data_user['ventas'] == 1 ? 'checked' : '' ?>>
        <label class="btn btn-outline-success check-permiss-module" for="edit-check-modulo-ventas">Ventas</label>
    </div>
    <div class="col-6 p-1">
        <input type="checkbox" class="btn-check" name="edit-check-modulo-usuarios" id="edit-check-modulo-usuarios"
            <?= $data_user['usuarios'] == 1 ? 'checked' : '' ?>>
        <label class="btn btn-outline-success check-permiss-module" for="edit-check-modulo-usuarios">Usuarios</label>
    </div>
</div>

<input type="hidden" name="user_request" value="edit-user">
<div class="total-center"><button type="submit" class="btn-execute" id="submit-edit-user">Actualizar</button></div>


<script>
    //visalizar foto de perfil
    document.addEventListener('change', e => {
        if (e.target.id === 'edit-user-photo') {
            showImgUpdateUser(e);
        }
    });

    function showImgUpdateUser(e) {
        const cont_img_newuser = document.getElementById('label-photo-edit-user');
        const input = e.target;
        const file = input.files?.[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                cont_img_newuser.style.background =
                    `rgb(0, 0, 0, 0.2) url(${ev.target.result}) center center / cover no-repeat`;
            };
            reader.readAsDataURL(file);
        } else {
            cont_img_newuser.style.background =
                `rgb(0, 0, 0, 0.2) url('modules/users/files/<?= $data_user['photo'] ?>') center center / cover no-repeat`;
        }
    }
</script>