// Iniciar temporizador después de 1 minuto de inactividad
var inactivityTimeout = setTimeout(logout, 1 * 60 * 1000);
var alertTimeout = setTimeout(showAlert, 0.5 * 60 * 1000); // Mostrar alerta a los 30 segundos

// Reiniciar los temporizadores cuando se detecte una actividad del usuario
document.addEventListener('mousemove', resetTimer);
document.addEventListener('keydown', resetTimer);

// Función para reiniciar los temporizadores
function resetTimer() {
    clearTimeout(inactivityTimeout);
    clearTimeout(alertTimeout);
    inactivityTimeout = setTimeout(logout, 1 * 60 * 1000);
    alertTimeout = setTimeout(showAlert, 0.5 * 60 * 1000);
}

// Función para cerrar la sesión
function logout() {
    var baseUrl = window.location.origin; // Obtiene el origen (protocolo + dominio)
    var logoutPath = '/sistema-UEBEM/logout.php'; // Ruta relativa al archivo de logout
    window.location.href = baseUrl + logoutPath; // Construye la URL absoluta
}

// Función para mostrar la alerta
function showAlert() {
    Swal.fire({
        title: 'Sesión a punto de expirar',
        text: 'Tu sesión se cerrará en 30 segundos por inactividad.',
        icon: 'warning',
        confirmButtonText: 'Mantener sesión abierta'
    }).then((result) => {
        if (result.isConfirmed) {
            resetTimer(); // Reiniciar el temporizador si el usuario confirma
        }
    });
}
