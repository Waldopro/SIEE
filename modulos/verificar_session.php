<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
configurar_sesion_segura();

// Verificar si las variables de sesión esenciales existen
if (!isset($_SESSION["usuario"]) || !isset($_SESSION["id_usuario"])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}

// Verificar si es docente y si tiene el id_docente
if (isset($_SESSION["id_cargo"]) && $_SESSION["id_cargo"] == 3 && !isset($_SESSION["id_docente"])) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}
?>
