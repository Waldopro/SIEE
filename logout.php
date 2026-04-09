<?php
include './modulos/verificar_session.php';

// Obtener datos de sesión
$usuario = $_SESSION["usuario"];
$id_cargo = $_SESSION["id_cargo"];

// Actualizar tiempo de cierre
if ($id_cargo == 1 || $id_cargo == 2 || $id_cargo == 3) {
    include './modulos/conexion/conndb.php';

    date_default_timezone_set('America/Caracas');

    // Prepared statement para actualizar último cierre
    $consulta_actualizar = "UPDATE usuarios SET tiempo_cierre = NOW() WHERE usuario = ? AND activo = 1";
    $stmt = $conexion->prepare($consulta_actualizar);
    $stmt->bind_param("s", $usuario);

    if ($stmt->execute()) {
        $stmt->close();
        $conexion->close();
    } else {
        error_log("Error al actualizar tiempo_cierre: " . $stmt->error);
        $stmt->close();
        $conexion->close();
    }
}

// Eliminar todas las variables de sesión y destruir la sesión
session_unset();
session_destroy();

// Redirigir al inicio de sesión
header("Location: login.php");
exit();
?>
