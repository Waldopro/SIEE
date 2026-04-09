<?php
// Iniciar sesión
include '../verificar_session.php';
require '../conexion/conndb.php';
// Obtener la cédula ingresada por el usuario
$cedula_e = $_GET['cedula_e'];

// Consulta para obtener los datos del estudiante e inscripción
$stmt = $conexion->prepare("SELECT e.*, i.id_curso, c.anho, c.id_grado, c.id_seccion FROM estudiante e
                             INNER JOIN inscripcion i ON e.id_estudiante = i.id_estudiante
                             INNER JOIN curso c ON i.id_curso = c.id
                             WHERE e.cedula_e = ?");
$stmt->bind_param("s", $cedula_e);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Verificar si se encontraron resultados
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $grado_id = $row['id_grado'];  // Obtener el ID del grado
    $seccion_id = $row['id_seccion'];  // Obtener el ID de la sección
    $anho = $row['anho'];  // Año escolar

    // Consulta para obtener el nombre del grado
    $stmt_grado = $conexion->prepare("SELECT nombre FROM grados WHERE id = ?");
    $stmt_grado->bind_param("i", $grado_id);
    $stmt_grado->execute();
    $result_grado = $stmt_grado->get_result();

    if (!$result_grado) {
        die("Error en la consulta del grado: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($result_grado) > 0) {
        $row_grado = mysqli_fetch_assoc($result_grado);
        $current_grado = $row_grado['nombre'];  // Obtener el nombre del grado
    } else {
        $current_grado = "Grado no encontrado";
    }

    // Consulta para obtener el nombre de la sección
    $stmt_seccion = $conexion->prepare("SELECT nombre FROM secciones WHERE id = ?");
    $stmt_seccion->bind_param("i", $seccion_id);
    $stmt_seccion->execute();
    $result_seccion = $stmt_seccion->get_result();

    if (!$result_seccion) {
        die("Error en la consulta de la sección: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($result_seccion) > 0) {
        $row_seccion = mysqli_fetch_assoc($result_seccion);
        $current_seccion = $row_seccion['nombre'];  // Obtener el nombre de la sección
    } else {
        $current_seccion = "Sección no encontrada";
    }

} else {
    echo "No se encontraron resultados para la cédula ingresada.";
    exit;
}

require_once __DIR__ . '../../../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

// Agregar la cabecera al PDF
$html = '<img src="../../img/cintillo.jpg" width="100%" height="60px">';
$html .= '<h3 style="text-align:center;">República Bolivariana de Venezuela</h3>';
$html .= '<h3 style="text-align:center;">Ministerio del Poder Popular para la Educacion</h3>';
$html .= '<h3 style="text-align:center;">U.E Bolivariana "Eneas Morantes"</h3>';
$html .= '<h3 style="text-align:center;">Yaguaraparo Municipio Cajigal Estado Sucre</h3><br><br><br>';

// Agregar el contenido principal al PDF
$html .= '<p>Quien suscribe, Directora de la Unidad Educativa Bolivariana "Eneas Morante", Código del Plantel Nº 17-006590284, que funciona en la comunidad de la La Chivera, Parroquia Yaguaraparo, Municipio Cajigal Estado Sucre, por medio de la presente hace constar que el (la) alumno (a): ' . $row['nombres_e'] . ' ' . $row['apellidos_e'] . ' titular de la Cédula Nº ' . $row['cedula_e'] . ' de ' . $row['edad_e'] . ' años de edad, ha sido inscrito (a) para cursar el grado ' . $current_grado . ', en la sección ' . $current_seccion . ' durante el Año Escolar ' . $anho . '. Constancia que se expide a petición de la parte interesada en Yaguaraparo, a los ' . date('d') . ' días del mes ' . date('m') . ' de ' . date('Y') . '.</p><br><br>';

$html .= '<hr style="border: none; height: 1px; background-color: black; width: 50%;"><br>'; // Línea horizontal con estilo

$html .= '<h3 style="text-align:center;">Atentamente,</h3>';
$html .= '<h3 style="text-align:center;">Olidys Alfonzo</h3>';
$html .= '<h3 style="text-align:center;">Directora</h3>';

// Escribir el contenido HTML en el PDF
$mpdf->WriteHTML($html);

// Generar un nombre de archivo único
$filename = uniqid('constancia_', true) . '.pdf';

// Generar el archivo PDF
$mpdf->Output($filename, 'I'); // I: Enviar al navegador

exit;
?>