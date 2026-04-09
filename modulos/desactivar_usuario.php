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

// Verificar que no sea el último admin activo
$consulta_cargo = "SELECT id_cargo FROM usuarios WHERE id = ?";
$stmt_cargo = $conexion->prepare($consulta_cargo);
$stmt_cargo->bind_param("i", $id);
$stmt_cargo->execute();
$resultado_cargo = $stmt_cargo->get_result();
$fila_cargo = $resultado_cargo->fetch_assoc();
$stmt_cargo->close();

if ($fila_cargo && $fila_cargo['id_cargo'] == 1) {
    // Contar admins activos
    $consulta_count = "SELECT COUNT(*) AS total FROM usuarios WHERE id_cargo = 1 AND activo = 1";
    $result_count = $conexion->query($consulta_count);
    $count = $result_count->fetch_assoc()['total'];
    if ($count <= 1) {
        header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php?error=ultimo_admin");
        exit();
    }
}

// Desactivar usuario con prepared statement
$sql = "UPDATE usuarios SET activo = 0 WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conexion->close();
    header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php");
    exit();
} else {
    error_log("Error al desactivar usuario: " . $stmt->error);
    $stmt->close();
    $conexion->close();
    header("Location: " . BASE_URL . "/modulos/procesar/procesalogin.php?error=desactivar");
    exit();
}
?>