function disable_form() {
    document.getElementById("view-preloader").classList.add('object-visible');
    document.getElementById('submit-login-form').disabled = true;
}
function enable_form() {
    document.getElementById("view-preloader").classList.remove('object-visible');
    document.getElementById('submit-login-form').disabled = false;
}

$('#form-login').submit(function (event) {
    event.preventDefault();
    disable_form();

    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: 'system/controller/access-user.php',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.access === 'ACCESSGRANTED') {
                document.getElementById('form-login').reset();
                window.location.href = response.path;
            } else {
                enable_form();
                show_alert(response.bg, response.access, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            enable_form();
            show_alert('danger', 'Error', 'Error de conexión: ' + error, true);
        }
    });
});