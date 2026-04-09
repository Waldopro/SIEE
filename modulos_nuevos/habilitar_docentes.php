<?php
// Obtener el ID del usuario a activar
$id = $_GET['id'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../modulos/verificar_session.php';
include '../modulos/rol1.php';
// Configuración de la conexión a la base de datos
include '../modulos/conexion/conndb.php';


// Realizar las acciones necesarias para activar al usuario
$sql = "UPDATE docentes SET habilitado = 1 WHERE id = '$id'";

if (mysqli_query($conexion, $sql)) {
    echo '<script>alert("Estado del usuario actualizado correctamente.");</script>';
     // Redireccionar a la página de usuarios registrados después de activar al usuario
     header("Location: gestion_docentes.php");
     exit();
} else {
    echo "Error al actualizar el estado del usuario: " . mysqli_error($conexion);  
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

