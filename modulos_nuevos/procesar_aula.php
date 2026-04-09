<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
// Verificar si se han recibido los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
$nombre = $_POST['nombre'];

include '../modulos/conexion/conndb.php';
// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Preparar la consulta SQL para insertar el aula en la tabla
$insertar_aula = "INSERT INTO aulas (nombre) VALUES ('$nombre')";
// Ejecutar la consulta y verificar si se realizó correctamente
if ($conexion->query($insertar_aula) === TRUE) {
    echo "Aula registrada exitosamente.";
} else {
    echo "Error al registrar el aula: " . $conexion->error;
}

// Cerrar la conexión a la base de datos
$conexion->close();
} else {
// Si no se ha recibido ningún dato del formulario, redireccionar al formulario
header("Location: gestion_aula.php");
exit();
}
?>