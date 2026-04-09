<?php
include '../verificar_session.php';
include '../conexion/conndb.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../procesar/procesarp.php");
    exit();
}

// Verificar CSRF
exigir_csrf();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: ../procesar/procesarp.php");
    exit();
}

$id_padre_madre = intval($_POST['id']);

$consulta = "DELETE FROM padre_madre WHERE id_padre_madre = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $id_padre_madre);

if ($stmt->execute()) {
    $stmt->close();
    $conexion->close();
    header("Location: ../procesar/procesarp.php?eliminacion_exitosa=true");
    exit();
} else {
    error_log("Error al eliminar padre/madre: " . $stmt->error);
    $stmt->close();
    $conexion->close();
    header("Location: ../procesar/procesarp.php?error=eliminacion");
    exit();
}
?>
