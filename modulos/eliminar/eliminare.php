<?php
include '../verificar_session.php';
include '../conexion/conndb.php';

// Solo aceptar POST (prevenir eliminación por URL)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../procesar/procesare.php");
    exit();
}

// Verificar CSRF
exigir_csrf();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: ../procesar/procesare.php");
    exit();
}

$id_estudiante = intval($_POST['id']);

$consulta = "DELETE FROM estudiante WHERE id_estudiante = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $id_estudiante);

if ($stmt->execute()) {
    $stmt->close();
    $conexion->close();
    header("Location: ../procesar/procesare.php?eliminacion_exitosa=true");
    exit();
} else {
    error_log("Error al eliminar estudiante: " . $stmt->error);
    $stmt->close();
    $conexion->close();
    header("Location: ../procesar/procesare.php?error=eliminacion");
    exit();
}
?>
