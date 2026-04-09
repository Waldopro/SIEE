<?php
// Cargar configuración si no se ha cargado
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';

$servername = defined('DB_HOST') ? DB_HOST : 'localhost';
$username = defined('DB_USER') ? DB_USER : 'root';
$password = defined('DB_PASS') ? DB_PASS : '';
$dbname = defined('DB_NAME') ? DB_NAME : 'escuela';

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    error_log("Error de conexión a BD: " . $conexion->connect_error);
    die("Error de conexión a la base de datos. Contacte al administrador.");
}

// Establecer charset
$conexion->set_charset("utf8mb4");
?>
