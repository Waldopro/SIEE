<?php
include '../verificar_session.php';
include '../conexion/conndb.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../procesar/procesarr.php");
    exit();
}

// Verificar CSRF
exigir_csrf();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: ../procesar/procesarr.php");
    exit();
}

$id_representante = intval($_POST['id']);

$consulta = "DELETE FROM representante WHERE id_representante = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $id_representante);

if ($stmt->execute()) {
    $stmt->close();
    $conexion->close();
    header("Location: ../procesar/procesarr.php?eliminacion_exitosa=true");
    exit();
} else {
    error_log("Error al eliminar representante: " . $stmt->error);
    $stmt->close();
    $conexion->close();
    header("Location: ../procesar/procesarr.php?error=eliminacion");
    exit();
}
?>
