<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('../verificar_session.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula</title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/procesarr.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../lib/datatable/datatables.min.js"></script>
    <script src="../../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../../lib/datatable/jquery.dataTables.min.js"></script>
</head>
<body oncontextmenu="return false;">
    
    <?php include('../menu.php') ?>

    <div class="content">
        <center><h1>Matrícula estudiantil</h1></center>
        
        <!-- Botón para generar PDF -->
        <div class="text-center mb-3">
    <a class="pdf" href="../pdf/generar_pdf.php">
        <img src="../../img/s.png" alt="Generar PDF" style="width: 40px; height: 50px;"> Generar PDF
    </a>
</div>

        
        <table id="tabla" class="table table-hover table-striped table-condensed">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Grado</th>
                    <th>Sección</th>
                    <th>Año Escolar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../conexion/conndb.php';

                // Consulta para obtener estudiantes que están inscritos en cursos
                $sql = "SELECT g.nombre AS nombre_grado, 
                            s.nombre AS nombre_seccion, 
                            c.anho AS anho_escolar, 
                            e.nombres_e AS nombre_estudiante, 
                            e.apellidos_e AS apellido_estudiante, 
                            e.cedula_e AS cedula_estudiante
                        FROM curso c
                        INNER JOIN grados g ON c.id_grado = g.id
                        INNER JOIN secciones s ON c.id_seccion = s.id
                        INNER JOIN inscripcion i ON c.id = i.id_curso
                        INNER JOIN estudiante e ON i.id_estudiante = e.id_estudiante
                        ORDER BY g.orden, s.nombre, e.apellidos_e, e.nombres_e";

                if ($result = $conexion->query($sql)) {
                    $current_grado = "";
                    $current_seccion = "";

                    while ($row = $result->fetch_assoc()) {
                        // Si el grado o sección cambia, imprimir encabezado
                        if ($row['nombre_grado'] != $current_grado || $row['nombre_seccion'] != $current_seccion) {
                            $current_grado = $row['nombre_grado'];
                            $current_seccion = $row['nombre_seccion'];

                            echo "<tr class='table-primary'>";
                            echo "<th colspan='6'>Grado: {$row['nombre_grado']} - Sección: {$row['nombre_seccion']} - Año Escolar: {$row['anho_escolar']}</th>";
                            echo "</tr>";
                        }

                        // Imprimir detalles del estudiante
                        echo "<tr>";
                        echo "<td>{$row['cedula_estudiante']}</td>";
                        echo "<td>{$row['nombre_estudiante']}</td>";
                        echo "<td>{$row['apellido_estudiante']}</td>";
                        echo "<td>{$row['nombre_grado']}</td>";
                        echo "<td>{$row['nombre_seccion']}</td>";
                        echo "<td>{$row['anho_escolar']}</td>";
                        echo "</tr>";
                    }

                    $result->free();
                } else {
                    echo "<tr><td colspan='6'>No se encontraron datos de matrícula</td></tr>";
                }

                $conexion->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>
</body>
</html>
