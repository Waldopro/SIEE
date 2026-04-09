<?php
include('../verificar_session.php');
include '../rol1.php';
include '../conexion/conndb.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../procesar/procesalogin.php");
    exit();
}

// Verificar CSRF
exigir_csrf();

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: ../procesar/procesalogin.php");
    exit();
}

$id = intval($_POST['id']);

// Consulta preparada para eliminar el usuario
$sql_delete_user = "DELETE FROM usuarios WHERE id = ?";
$stmt_delete_user = $conexion->prepare($sql_delete_user);
$stmt_delete_user->bind_param("i", $id);

if ($stmt_delete_user->execute()) {
    $stmt_delete_user->close();
    $conexion->close();
    header("Location: ../procesar/procesalogin.php?eliminacion_exitosa=true");
    exit();
} else {
    error_log("Error al eliminar usuario: " . $stmt_delete_user->error);
    $stmt_delete_user->close();
    $conexion->close();
    header("Location: ../procesar/procesalogin.php?error=eliminacion");
    exit();
}
?>
