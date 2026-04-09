<?php
// Conectar a la base de datos
include '../modulos/conexion/conndb.php';

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Incluir el archivo de verificación de sesión
include '../modulos/verificar_session.php';

// Función para obtener la lista de cursos
function obtenerListaCursos() {
    global $conexion;
    $sql = "SELECT id, CONCAT('Curso ', id) AS nombre FROM curso ORDER BY id ASC";
    $result = $conexion->query($sql);

    $cursos = [];
    while ($row = $result->fetch_assoc()) {
        $cursos[] = $row;
    }

    return $cursos;
}

// Función para obtener grado y sección de un curso
function obtenerGradoYSeccion($idCurso) {
    global $conexion;
    $sql = "SELECT g.nombre AS grado, s.nombre AS seccion
            FROM curso c
            LEFT JOIN grados g ON c.id_grado = g.id
            LEFT JOIN secciones s ON c.id_seccion = s.id
            WHERE c.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCurso);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Función para obtener estudiantes de un curso
function obtenerEstudiantesPorCurso($idCurso) {
    global $conexion;

    $sql = "
        SELECT e.id_estudiante, e.cedula_e, e.nombres_e, e.apellidos_e, ev.calificacion
        FROM inscripcion i
        LEFT JOIN estudiante e ON i.id_estudiante = e.id_estudiante
        LEFT JOIN evaluaciones ev ON ev.id_estudiante = e.id_estudiante
        WHERE i.id_curso = ?
        ORDER BY e.cedula_e ASC
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idCurso);
    $stmt->execute();
    $result = $stmt->get_result();

    $estudiantes = [];
    while ($row = $result->fetch_assoc()) {
        $idEstudiante = $row['id_estudiante'];

        if (!isset($estudiantes[$idEstudiante])) {
            $estudiantes[$idEstudiante] = [
                'id_estudiante' => $idEstudiante,
                'cedula_e' => $row['cedula_e'],
                'nombres_e' => $row['nombres_e'],
                'apellidos_e' => $row['apellidos_e'],
                'calificaciones' => []
            ];
        }

        if ($row['calificacion']) {
            $estudiantes[$idEstudiante]['calificaciones'][] = $row['calificacion'];
        }
    }

    return $estudiantes;
}

// Función para determinar el estado del estudiante
function determinarEstado($calificaciones) {
    if (empty($calificaciones)) {
        return 'Sin evaluaciones';
    }

    foreach ($calificaciones as $calificacion) {
        if ($calificacion === 'D' || $calificacion === 'E') {
            return 'Reprobado';
        }
    }

    return 'Aprobado';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes aprobados y reprobados</title>
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
            $('#table').DataTable({
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
<body>   
    <?php include('../modulos/menu.php')?>
    <div class="content">
        <center><h1>Estudiantes aprobados y reprobados</h1></center>
    
    <!-- Selector de cursos -->
    <form method="get">
        <label for="id_curso">Seleccione un curso:</label>
        <select name="id_curso" id="id_curso" onchange="this.form.submit()">
            <option value="">-- Seleccione un curso --</option>
            <?php
            $cursos = obtenerListaCursos();
            foreach ($cursos as $curso) {
                $selected = (isset($_GET['id_curso']) && $_GET['id_curso'] == $curso['id']) ? 'selected' : '';
                echo "<option value='{$curso['id']}' $selected>{$curso['nombre']}</option>";
            }
            ?>
        </select>
    </form>

    <br>

    <?php if (isset($_GET['id_curso']) && $_GET['id_curso']): ?>
        <?php
        $idCursoSeleccionado = $_GET['id_curso'];
        // Obtener grado y sección del curso seleccionado
        $gradoYSeccion = obtenerGradoYSeccion($idCursoSeleccionado);
        ?>
        <center><h2>Estudiantes del Curso <?php echo $gradoYSeccion['grado']; ?> - <?php echo $gradoYSeccion['seccion']; ?></h2></center>
        <table id="table"   class="table table-hover table-condensed">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $estudiantes = obtenerEstudiantesPorCurso($idCursoSeleccionado);
            if (!empty($estudiantes)) {
                foreach ($estudiantes as $estudiante) {
                    $estado = determinarEstado($estudiante['calificaciones']);
                    echo "<tr>
                            <td>{$estudiante['cedula_e']}</td>
                            <td>{$estudiante['nombres_e']}</td>
                            <td>{$estudiante['apellidos_e']}</td>
                            <td>{$estado}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay estudiantes en este curso</td></tr>";
            }
            ?>
        </tbody>
        </table>
    <?php endif; ?>
    </div>
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>  
</body>
</html>
