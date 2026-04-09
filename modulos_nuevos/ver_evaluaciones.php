<?php
include("../modulos/verificar_session.php");
include("../modulos/rol3.php");
require '../modulos/conexion/conndb.php';

// Verificar si se ha pasado el ID del docente
if (isset($_GET['id_docente']) && !empty($_GET['id_docente'])) {
    $id_docente = $_GET['id_docente'];
} else {
    die("Falta el ID del docente.");
}

// Consulta para obtener los cursos asignados al docente
$consulta_cursos = "
    SELECT c.id, g.nombre AS grado, s.nombre AS seccion, a.nombre AS aula
    FROM curso c
    INNER JOIN grados g ON c.id_grado = g.id
    INNER JOIN secciones s ON c.id_seccion = s.id
    INNER JOIN aulas a ON a.id_aula = c.id_aula
    WHERE c.id_docente = ?
";
$stmt_cursos = $conexion->prepare($consulta_cursos);
if ($stmt_cursos) {
    $stmt_cursos->bind_param("i", $id_docente);
    $stmt_cursos->execute();
    $resultado_cursos = $stmt_cursos->get_result();

    // Arreglo para almacenar los cursos
    $cursos = array();
    while ($fila = $resultado_cursos->fetch_assoc()) {
        $cursos[$fila['id']] = $fila;
    }
    $stmt_cursos->close();
} else {
    echo "Error en la consulta de cursos: " . $conexion->error;
    exit;
}

// Si no hay cursos asignados al docente
if (empty($cursos)) {
    die("El docente no tiene cursos asignados.");
}

// Obtener las evaluaciones de los estudiantes por curso
foreach ($cursos as &$curso) {
    $consulta_evaluaciones = "
        SELECT e.id_estudiante, e.cedula_e, e.nombres_e, e.apellidos_e, ev.calificacion, ev.id_lapso, ev.observaciones
        FROM estudiante e
        INNER JOIN inscripcion i ON e.id_estudiante = i.id_estudiante
        INNER JOIN evaluaciones ev ON ev.id_estudiante = e.id_estudiante
        WHERE i.id_curso = ? 
        ORDER BY e.cedula_e ASC, ev.id_lapso ASC
    ";
    $stmt_evaluaciones = $conexion->prepare($consulta_evaluaciones);
    if ($stmt_evaluaciones) {
        $stmt_evaluaciones->bind_param("i", $curso['id']);
        $stmt_evaluaciones->execute();
        $resultado_evaluaciones = $stmt_evaluaciones->get_result();

        $evaluaciones = array();
        while ($fila_evaluacion = $resultado_evaluaciones->fetch_assoc()) {
            $evaluaciones[] = $fila_evaluacion;
        }

        $curso['evaluaciones'] = $evaluaciones; // Asociar las evaluaciones al curso
        $stmt_evaluaciones->close();
    } else {
        echo "Error en la consulta de evaluaciones: " . $conexion->error;
        exit;
    }
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Evaluaciones de Estudiantes</title>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesarr.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/dataTables.bootstrap.min.css">
    <script src="../lib/datatable/datatables.min.js"></script>
    <script src="../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../lib/datatable/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaEvaluaciones').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "lengthChange": false,
                "pageLength": 7,
                "language": {
                    "url": "../lib/Spanish.json"
                }
            });
        });
    </script>
</head>
<body oncontextmenu="return false;">   
    <?php include('../modulos/menu.php')?>
    <div class="content">
        <center><h2>Evaluaciones de Estudiantes</h2></center>

        <?php
        // Mostrar los cursos y sus evaluaciones
        foreach ($cursos as $curso) {
            echo "<h3>Curso: " . htmlspecialchars($curso['grado']) . " - " . htmlspecialchars($curso['seccion']) . " - Aula: " . htmlspecialchars($curso['aula']) . "</h3>";

            if (empty($curso['evaluaciones'])) {
                echo "<p>No hay evaluaciones registradas para este curso.</p>";
            } else {
                echo "<table id='tablaEvaluaciones' class='table table-hover table-condensed'>
                        <thead>
                            <tr>
                                <th>ID Estudiante</th>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Calificación</th>
                                <th>Lapso</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>";
                        
                foreach ($curso['evaluaciones'] as $evaluacion) {
                    echo "<tr>
                            <td>" . htmlspecialchars($evaluacion['id_estudiante']) . "</td>
                            <td>" . htmlspecialchars($evaluacion['cedula_e']) . "</td>
                            <td>" . htmlspecialchars($evaluacion['nombres_e']) . "</td>
                            <td>" . htmlspecialchars($evaluacion['apellidos_e']) . "</td>
                            <td>" . htmlspecialchars($evaluacion['calificacion']) . "</td>
                            <td>" . htmlspecialchars($evaluacion['id_lapso']) . "</td>
                            <td>" . htmlspecialchars($evaluacion['observaciones']) . "</td>
                        </tr>";
                }

                echo "</tbody>
                </table> ";
            }
        }
        ?>
    </div>
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>
</body>
</html>
