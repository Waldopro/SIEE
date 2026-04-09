<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../verificar_session.php';
include '../conexion/conndb.php';

// Validar si sección y grado están definidos
if (isset($_GET['seccion']) && isset($_GET['grado'])) {
    $seccion = $_GET['seccion'];
    $grado = $_GET['grado'];

    if (empty($seccion) || empty($grado)) {
        echo json_encode(['success' => false, 'message' => 'Sección o grado no válidos.']);
        exit;
    }

    // Obtener el nombre de la sección
    $sql_seccion = "SELECT nombre FROM secciones WHERE id = ?";
    $stmt_seccion = $conexion->prepare($sql_seccion);
    $stmt_seccion->bind_param("i", $seccion);
    $stmt_seccion->execute();
    $result_seccion = $stmt_seccion->get_result();
    $nombre_seccion = $result_seccion->fetch_assoc()['nombre'];

    // Obtener el nombre del grado
    $sql_grado = "SELECT nombre FROM grados WHERE id = ?";
    $stmt_grado = $conexion->prepare($sql_grado);
    $stmt_grado->bind_param("i", $grado);
    $stmt_grado->execute();
    $result_grado = $stmt_grado->get_result();
    $nombre_grado = $result_grado->fetch_assoc()['nombre'];

    // Consulta para buscar los estudiantes según la sección y grado
    $sql = "SELECT e.id_estudiante,e.cedula_e, e.sexo , e.nombres_e, e.apellidos_e, e.edad_e
            FROM estudiante e
            JOIN inscripcion i ON e.id_estudiante = i.id_estudiante
            JOIN curso c ON i.id_curso = c.id
            WHERE c.id_seccion = ? AND c.id_grado = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $seccion, $grado);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = [
                'id_estudiante' => $row['id_estudiante'],
                'cedula_e' => $row['cedula_e'],
                'nombres_e' => $row['nombres_e'],
                'sexo' => $row['sexo'],
                'apellidos_e' => $row['apellidos_e'],
                'edad_e' => $row['edad_e'],
                'nombre_seccion' => $nombre_seccion,
                'nombre_grado' => $nombre_grado
            ];
        }

        // Verificar si se ha solicitado generar PDF
        if (isset($_GET['imprimir']) && $_GET['imprimir'] == "1") {
            // Incluir la biblioteca mPDF
            require_once __DIR__ . '/../../vendor/autoload.php';

            // Crear una nueva instancia de mPDF
            $mpdf = new \Mpdf\Mpdf();

            // Crear contenido del PDF
            $html = '<img src="../../img/cintillo.jpg" width="100%" height="60px">';
            $html .= '<h3 style="text-align:center;">República Bolivariana de Venezuela</h3>';
            $html .= '<h3 style="text-align:center;">Ministerio del Poder Popular para la Educación</h3>';
            $html .= '<h3 style="text-align:center;">U.E Bolivariana "Eneas Morantes"</h3>';
            $html .= '<h3 style="text-align:center;">Yaguaraparo Municipio Cajigal Estado Sucre</h3><br><br>';
            $html .= '<h1 style="text-align:center;">Lista de Estudiantes</h1><br><br>';
            $html .= '<h3 style="text-align:center;">Grado: ' . $nombre_grado . ' | Sección: ' . $nombre_seccion . '</h3><br><br>';
            $html .= '<table border="1" style="width:100%">
                          <tr>
                              <th>ID</th>
                              <th>Cédula</th>
                              <th>Nombres</th>
                              <th>Apellidos</th>
                              <th>Edad</th>
                              <th>Sexo</th>
                          </tr>';

            foreach ($students as $student) {
                $html .= '<tr>
                            <td>' . $student['id_estudiante'] . '</td>
                            <td>' . $student['cedula_e'] . '</td>
                            <td>' . $student['nombres_e'] . '</td>
                            <td>' . $student['apellidos_e'] . '</td>
                            <td>' . $student['edad_e'] . '</td>
                            <td>' . $student['sexo'] . '</td>
                          </tr>';
            }

            $html .= '</table>';

            // Escribir el contenido en el PDF
            $mpdf->WriteHTML($html);

            // Generar el PDF
            $mpdf->Output();
            exit;
        }

        // Enviar la respuesta en formato JSON si no se va a generar PDF
        echo json_encode(['success' => true, 'data' => $students]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron estudiantes.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Parámetros "seccion" y "grado" no definidos o incorrectos.']);
}

$conexion->close();
?>
