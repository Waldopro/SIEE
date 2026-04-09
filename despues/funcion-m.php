<?php
function isSystemOperational() {
    $currentDateTime = new DateTime();
    $currentHour = (int)$currentDateTime->format('H');
    $currentDay = (int)$currentDateTime->format('w'); // 0 (Domingo) a 6 (Sábado)

    $startHour = 5;  // 5:00 AM
    $endHour = 17;   // 4:59 PM
    $operationalDays = [1, 2, 3, 4, 5]; // Lunes (1) a Viernes (5)

    return in_array($currentDay, $operationalDays) && $currentHour >= $startHour && $currentHour < $endHour;
}

function isDeveloperAuthenticated() {
    return isset($_SESSION['developer_authenticated']) && $_SESSION['developer_authenticated'] === true;
}

// Asegúrate de iniciar la sesión antes de cualquier otra salida

function authenticateDeveloper($code) {
    // Supongamos que el código de desarrollador es "dev1234"
    $developerCode = 'dev1234'; // Actualiza con el código correcto
    if ($code === $developerCode) {
        $_SESSION['developer_authenticated'] = true; // Marca al desarrollador como autenticado
        return true;
    } else {
        return false;
    }
}
?>
