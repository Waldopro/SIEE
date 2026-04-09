function cerrarSesion() {
    var confirmacion = confirm("¿Estás seguro de que deseas cerrar sesión?");
    if (confirmacion) {
        // Redireccionar al archivo de cierre de sesión
        window.location.href = "logout.php";
    }
}