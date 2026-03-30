window.addEventListener("load", function () {
    setTimeout(() => {
        document.getElementById("view-preloader").classList.remove('object-visible');
    }, 400)
});

function show_alert(bg, status, message, visible = true) {
    let visibility = visible ? 'block' : 'none';
    // Mostrar la alerta
    document.getElementById('system-alert').classList.add('object-visible');
    // Cambiar color de fondo del título
    document.getElementById('head-alert').style.background = 'var(--bg-' + bg + ')';
    // Mostrar/ocultar botón según el parámetro visible
    let btnContainer = document.getElementById('container-btn-acept');
    if (btnContainer) {
        btnContainer.style.display = visibility;
    }
    // Actualizar contenido
    $('#title-alert').html(status);
    $('#text-alert').html(message);
    // Bloquear scroll
    document.getElementById('html-body').style.overflow = 'hidden';
}

function hide_alert() {
    document.getElementById('system-alert').classList.remove('object-visible');
    document.getElementById('html-body').style.overflow = 'auto';
}