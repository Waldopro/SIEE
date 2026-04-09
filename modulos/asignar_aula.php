<?php
// Conexión a la base de datos
include 'conexion/conndb.php';

$id_estudiante = $_GET['id_estudiante'];
$id_aula = $_GET['id_aula'];

// Actualizar la base de datos
$query = "UPDATE estudiante SET aula_id = $id_aula WHERE id_estudiante = $id_estudiante";

if ($conexion->query($query) === TRUE) {
    echo "Aula asignada correctamente";
} else {
    echo "Error al asignar aula: " . $conexion->error;
}

$conexion->close();
?>
