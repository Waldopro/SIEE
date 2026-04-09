<?php
include ("../modulos/verificar_session.php");
include ("../modulos/rol3.php");
require '../modulos/conexion/conndb.php';

// Verificar si se envió el ID del docente
if (!empty($_GET['id_docente'])) {
    $id_docente = $_GET['id_docente'];

// Consulta para obtener el curso asignado al docente (con DISTINCT para evitar duplicados)
$consulta = "
SELECT DISTINCT c.id, g.nombre AS grado, s.nombre AS seccion, a.nombre AS aula
FROM curso c
INNER JOIN grados g ON c.id_grado = g.id
INNER JOIN secciones s ON c.id_seccion = s.id
INNER JOIN aulas a ON a.id_aula = c.id_aula
WHERE c.id_docente = ?
";
$stmt = $conexion->prepare($consulta);
if ($stmt) {
$stmt->bind_param("i", $id_docente);
$stmt->execute();
$resultado = $stmt->get_result();

$cursos = array();
while ($fila = $resultado->fetch_assoc()) {
    $cursos[$fila['id']] = $fila; // Usar ID como clave para evitar duplicados
}

$stmt->close();
} else {
echo "Error en la consulta: " . $conexion->error;
}

// Consulta para obtener los estudiantes de cada curso
foreach ($cursos as &$curso) {
$consulta_estudiantes = "
    SELECT e.id_estudiante, e.nombres_e, e.apellidos_e
    FROM estudiante e
    INNER JOIN inscripcion i ON e.id_estudiante = i.id_estudiante
    WHERE i.id_curso = ?";

$stmt_estudiantes = $conexion->prepare($consulta_estudiantes);
if ($stmt_estudiantes) {
    $stmt_estudiantes->bind_param("i", $curso['id']);
    $stmt_estudiantes->execute();
    $resultado_estudiantes = $stmt_estudiantes->get_result();

    $estudiantes = array();
    while ($fila_estudiante = $resultado_estudiantes->fetch_assoc()) {
        $estudiantes[] = $fila_estudiante;
    }

    $curso['estudiante'] = $estudiantes; // Asociar los estudiantes al curso
    $stmt_estudiantes->close();
} else {
    echo "Error en la consulta de estudiantes: " . $conexion->error;
}
    }
} else {
    echo "No se proporcionó el ID del docente.";
    $cursos = []; // Asegurar que $cursos esté definido
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Curso Asignado</title>

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
    <script src="../js/sweetalert211.js"></script>
    <script>
    function redireccionar() {
        var rol = document.querySelector(".a").getAttribute("data-rol");

        if (rol === '1') {
            window.location.href = "../../../inicio.php"; // Redirige a Administrador
        } else if (rol === '2') {
            window.location.href = "../../../iniciousuario.php"; // Redirige a Usuario
        } else if (rol === '3') {
            window.location.href = "../iniciodocente.php"; // Redirige a Docente
        } else {
            alert("Rol desconocido o no válido");
        }
    }
</script>

</head>
<body oncontextmenu="return false;">   
    <?php include('../modulos/menu.php')?>
    <div class="content">
        <center><h2>Curso Asignado al Docente</h2></center>
        <button style="background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 4px;" onclick="history.back()">Volver</button>

        <?php if (!empty($cursos)): ?>
                <table id="tabla" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>ID Curso</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Aula</th>
                        <th>Estudiantes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursos as $curso): ?>
                        <tr>
                            <td><?php echo $curso['id']; ?></td>
                            <td><?php echo $curso['grado']; ?></td>
                            <td><?php echo $curso['seccion']; ?></td>
                            <td><?php echo $curso['aula']; ?></td>
                            <td>
                                <?php if (!empty($curso['estudiante'])): ?>
                                    <button style="background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 4px;" onclick="mostrarEstudiantes(<?php echo htmlspecialchars(json_encode($curso['estudiante']), ENT_QUOTES, 'UTF-8'); ?>)">
                                        Ver Estudiantes
                                    </button>
                                <?php else: ?>
                                    <p>No hay estudiantes asignados.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>El docente no tiene un curso asignado.</p>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>  

    <script>
        // Función para mostrar los estudiantes en una alerta SweetAlert
        function mostrarEstudiantes(estudiantes) {
            let listaHTML = "<ul>";
            estudiantes.forEach(est => {
                listaHTML += `<li>${est.nombres_e} ${est.apellidos_e}</li>`;
            });
            listaHTML += "</ul>";

            Swal.fire({
                title: 'Estudiantes Asignados',
                html: listaHTML,
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        }

        // Inicializar DataTables
        $(document).ready(function() {
            $('#tabla').DataTable({
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
</body>
</html>
