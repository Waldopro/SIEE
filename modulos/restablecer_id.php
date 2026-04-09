<?
// Nombre de la tabla y campo de id
$tabla = 'nombre_de_tu_tabla';
$campo_id = 'id';

// Verificar si la tabla está vacía o no tiene un registro con id 1
$sql_check = "SELECT COUNT(*) AS count FROM $tabla WHERE $campo_id = 1";
$result_check = mysqli_query($conexion, $sql_check);
$row = mysqli_fetch_assoc($result_check);

if ($row['count'] == 0) {
    // Si no existe el registro con id 1 o la tabla está vacía, restablecer la secuencia
    $sql_reset = "ALTER TABLE $tabla AUTO_INCREMENT = 1";
    if (mysqli_query($conexion, $sql_reset)) {
        // La secuencia se restableció correctamente
        // echo "La secuencia se restableció.";
    } else {
        echo "Error al restablecer la secuencia: " . mysqli_error($conexion);
    }
}
?>