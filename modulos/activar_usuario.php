<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include 'verificar_session.php';
include 'rol1.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php");
    exit();
}

// Verificar CSRF
exigir_csrf();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php");
    exit();
}

$id = intval($_POST['id']);

include 'conexion/conndb.php';

// Activar usuario con prepared statement
$sql = "UPDATE usuarios SET activo = 1 WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conexion->close();
    header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php");
    exit();
} else {
    error_log("Error al activar usuario: " . $stmt->error);
    $stmt->close();
    $conexion->close();
    header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php?error=activar");
    exit();
}
?>
