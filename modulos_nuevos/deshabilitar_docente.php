<?php
include '../modulos/verificar_session.php';
include '../modulos/rol1.php';

// Configuración de la conexión a la base de datos
include '../modulos/conexion/conndb.php';

// Obtener el ID del docente y la acción (habilitar/deshabilitar) desde la URL
$id = $_GET['id'];
$accion = $_GET['accion'];

// Verificar que se haya proporcionado un ID y una acción válidos
if (!isset($id) || !isset($accion) || !in_array($accion, ['habilitar', 'deshabilitar'])) {
    die("ID o acción no válidos.");
}

// Determinar el valor de habilitado según la acción
$habilitado = ($accion == 'habilitar') ? 1 : 0;

// Realizar la acción de habilitar o deshabilitar al docente
$sql = "UPDATE docentes SET habilitado = $habilitado WHERE id = '$id'";

// Ejecutar la consulta
if (mysqli_query($conexion, $sql)) {
    // La consulta se ejecutó con éxito
    $mensaje = ($habilitado) ? "habilitado" : "deshabilitado";
    header("Location: gestion_docentes.php?estado=$mensaje");
    exit();
} else {
    // Error al ejecutar la consulta
    echo "Error al actualizar el estado del docente: " . mysqli_error($conexion);
}

// Cerrar la conexión
mysqli_close($conexion);
?>