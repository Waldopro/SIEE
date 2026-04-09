<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../conexion/conndb.php';


// Verifica que se haya recibido el grado
if (isset($_GET['grado'])) {
    $grado = $_GET['grado'];

    // Consulta para obtener las secciones con estudiantes registrados para el grado seleccionado
    $query = "
        SELECT DISTINCT s.id, s.nombre
        FROM secciones s
        JOIN curso c ON s.id = c.id_seccion
        JOIN inscripcion i ON c.id = i.id_curso
        WHERE c.id_grado = ? AND i.id_estudiante IS NOT NULL
    ";

    // Prepara y ejecuta la consulta
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $grado);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear el array para las secciones
    $secciones = array();
    while ($row = $result->fetch_assoc()) {
        $secciones[] = $row;
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($secciones);
} else {
    // Si no se recibió un grado, devolver un array vacío
    echo json_encode([]);
}
?>

