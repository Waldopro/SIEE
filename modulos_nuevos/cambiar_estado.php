<?php
include '../modulos/conexion/conndb.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Obtener el estado actual del docente
    $sql = "SELECT habilitado FROM docentes WHERE id = $id";
    $result = $conexion->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nuevo_estado = $row['habilitado'] ? 0 : 1;
        
        // Actualizar el estado del docente
        $sql = "UPDATE docentes SET habilitado = $nuevo_estado WHERE id = $id";
        if ($conexion->query($sql) === TRUE) {
            echo "Estado actualizado";
        } else {
            echo "Error al actualizar el estado: " . $conexion->error;
        }
    } else {
        echo "Docente no encontrado";
    }
    
    $conexion->close();
}
?>
