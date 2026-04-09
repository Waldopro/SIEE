<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include ("../modulos/verificar_session.php");
include ("../modulos/rol3.php");
require '../modulos/conexion/conndb.php';

// Verificar si se envió el ID del docente en la URL (validado como entero)
if (isset($_GET['id_docente']) && is_numeric($_GET['id_docente'])) {
    $id_docente = intval($_GET['id_docente']);
} else {
    header("Location: " . BASE_URL . "/iniciodocente.php");
    exit();
}

// Verificar que el id_docente coincida con la sesión
if (isset($_SESSION['id_docente']) && $_SESSION['id_docente'] != $id_docente) {
    header("Location: " . BASE_URL . "/iniciodocente.php");
    exit();
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

    $cursos = array();
    while ($fila = $resultado_cursos->fetch_assoc()) {
        $cursos[$fila['id']] = $fila;
    }
    $stmt_cursos->close();
} else {
    error_log("Error en la consulta de cursos: " . $conexion->error);
    die("Error al cargar los cursos. Contacte al administrador.");
}

// Si no hay cursos asignados al docente
if (empty($cursos)) {
    // No die(), sino mensaje informativo
    $sin_cursos = true;
}

// Para cada curso, obtener los estudiantes
if (!isset($sin_cursos)) {
    foreach ($cursos as &$curso) {
        $consulta_estudiantes = "
            SELECT e.id_estudiante, e.cedula_e, e.nombres_e, e.apellidos_e
            FROM estudiante e
            INNER JOIN inscripcion i ON e.id_estudiante = i.id_estudiante
            WHERE i.id_curso = ?
        ";
        $stmt_estudiantes = $conexion->prepare($consulta_estudiantes);
        if ($stmt_estudiantes) {
            $stmt_estudiantes->bind_param("i", $curso['id']);
            $stmt_estudiantes->execute();
            $resultado_estudiantes = $stmt_estudiantes->get_result();

            $estudiantes = array();
            while ($fila_estudiante = $resultado_estudiantes->fetch_assoc()) {
                $estudiantes[] = $fila_estudiante;
            }

            $curso['estudiantes'] = $estudiantes;
            $stmt_estudiantes->close();
        } else {
            error_log("Error en la consulta de estudiantes: " . $conexion->error);
        }
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluar Estudiantes</title>
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
    <link rel="stylesheet" href="../css/sweetalert.min.css">
    <script src="../js/sweetalert.min.js"></script>
</head>
<body oncontextmenu="return false;">   
    <?php include('../modulos/menu.php')?>
    <div class="content">
        <center><h2>Estudiantes asignados al Docente</h2></center>

        <?php if (isset($sin_cursos)): ?>
            <p>El docente no tiene cursos asignados.</p>
        <?php else: ?>
            <?php
            $tabla_index = 0;
            foreach ($cursos as $curso) {
                $tabla_index++;
                echo "<h3>Curso: " . esc($curso['grado']) . " - " . esc($curso['seccion']) . " - Aula: " . esc($curso['aula']) . "</h3>";

                if (empty($curso['estudiantes'])) {
                    echo "<p>No hay estudiantes inscritos en este curso.</p>";
                } else {
                    echo "<table id='tabla_eval_" . $tabla_index . "' class='table table-hover table-condensed'>
                            <thead>
                                <tr>
                                    <th>ID Estudiante</th>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Evaluar</th>
                                </tr>
                            </thead>
                            <tbody>";
                            
                    foreach ($curso['estudiantes'] as $estudiante) {
                        echo "<tr>
                                <td>" . esc($estudiante['id_estudiante']) . "</td>
                                <td>" . esc($estudiante['cedula_e']) . "</td>
                                <td>" . esc($estudiante['nombres_e']) . "</td>
                                <td>" . esc($estudiante['apellidos_e']) . "</td>
                                <td>
                                    <a href='javascript:void(0);' 
                                       onclick='mostrarEvaluacion(" . intval($estudiante['id_estudiante']) . ", \"" . esc($estudiante['nombres_e']) . "\", \"" . esc($estudiante['apellidos_e']) . "\", " . intval($curso['id']) . ")'>Evaluar</a>
                                </td>                  
                            </tr>";
                    }

                    echo "</tbody>
                    </table> ";
                }
            }
            ?>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>  

    <script>
        function mostrarEvaluacion(id_estudiante, nombres, apellidos, id_curso) {
            swal({
                title: 'Evaluar a ' + nombres + ' ' + apellidos,
                text: "Seleccione una calificación:",
                content: {
                    element: "div",
                    attributes: {
                        innerHTML: '<select id="calificacion"><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="E">E</option></select><br><br>Lapso: <select id="id_lapso"><option value="1">Primer Lapso</option><option value="2">Segundo Lapso</option><option value="3">Tercer Lapso</option></select><br><br>Observaciones: <textarea id="observaciones" placeholder="Escriba observaciones (opcional)"></textarea>'
                    }
                },
                buttons: {
                    cancel: { text: "Cancelar", value: null, visible: true },
                    confirm: { text: "Guardar Evaluación", value: true, visible: true }
                },
            }).then(function(confirm) {
                if (confirm) {
                    var calificacion = document.getElementById('calificacion').value;
                    var id_lapso = document.getElementById('id_lapso').value;
                    var observaciones = document.getElementById('observaciones').value;

                    $.ajax({
                        url: 'guardar_evaluacion.php',
                        method: 'POST',
                        data: {
                            id_estudiante: id_estudiante,
                            calificacion: calificacion,
                            id_curso: id_curso,
                            id_lapso: id_lapso,
                            observaciones: observaciones
                        },
                        success: function(response) {
                            var res = JSON.parse(response);
                            if(res.success) {
                                swal("Evaluación guardada!", "La evaluación de " + nombres + " " + apellidos + " ha sido guardada con éxito.", "success");
                            } else {
                                swal("Error", res.message, "error");
                            }
                        },
                        error: function() {
                            swal("Error", "Hubo un problema al guardar la evaluación.", "error");
                        }
                    });
                }
            });
        }
        
        $(document).ready(function() {
            // Inicializar DataTables para cada tabla de curso
            $('table[id^="tabla_eval_"]').each(function() {
                $(this).DataTable({
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
        });
    </script>
</body>
</html>
