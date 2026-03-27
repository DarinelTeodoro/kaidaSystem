$('#form-login').submit(function (event) {
    event.preventDefault();
    document.getElementById("view-preloader").classList.add('object-visible');
    document.getElementById('submit-login-form').disabled = true;
    
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
                $('#key-user').val('');
                $('#key-password').val('');
                window.location.href = response.path;
            } else {
                document.getElementById("view-preloader").classList.remove('object-visible');
                document.getElementById('submit-login-form').disabled = false;
                show_alert(response.bg, response.access, response.message, true);
            }
        },
        error: function (xhr, status, error) {
            document.getElementById('submit-login-form').disabled = false;
            show_alert('danger', 'Error', 'Error de conexión: ' + error, true);
        }
    });
});