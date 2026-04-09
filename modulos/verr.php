<?php
include 'conexion/conndb.php';
include 'verificar_session.php';

// Verificar si se proporcionó un ID de estudiante
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta SQL
    $sql = "SELECT * FROM representante WHERE id_representante = ?";

    // Preparar la declaración
    if ($stmt = $conexion->prepare($sql)) {
        // Vincular variables a la declaración preparada como parámetros
        $stmt->bind_param("i", $id);

        // Intentar ejecutar la declaración preparada
        if ($stmt->execute()) {
            // Almacenar el resultado
            $result = $stmt->get_result();

            // Verificar si se encontró el estudiante
            if ($result->num_rows == 1) {
                // Obtener la fila de resultados como un array asociativo
                $row = $result->fetch_assoc();

                // Devolver los datos del estudiante como JSON
                echo json_encode($row);
            } else {
                echo json_encode(array("mensaje" => "No se encontró ningún representante con ese ID."));
            }
        } else {
            echo json_encode(array("mensaje" => "No se pudo ejecutar la consulta."));
        }
    } else {
        echo json_encode(array("mensaje" => "No se pudo preparar la consulta."));
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conexion->close();
} else {
    echo json_encode(array("mensaje" => "No se proporcionó ningún ID de representante."));
}
?>