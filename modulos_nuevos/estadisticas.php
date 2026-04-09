<?php
include '../modulos/conexion/conndb.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include ('../modulos/verificar_session.php');
include ('../modulos/rol1.php');
?>
<!DOCTYPE html>
<html>
<head>
<title>Estadísticas de Inscripción de Estudiantes</title>
    <!-- Archivos CSS optimizados -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link rel="stylesheet" href="../css/procesare.css">
    <link rel="stylesheet" href="../css/estadistica.css">
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../lib/datatable/datatables.min.css">
    <link rel="stylesheet" href="../css/footer.css">
    
    <!-- Archivos JS optimizados -->
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../lib/datatable/datatables.min.js"></script>
    <script src="../js/chart.js"></script>
</head>
<body oncontextmenu="return false;">
    <?php include('../modulos/menu.php') ?>

    <div class="content">
        <h1 class="text-center">Estadísticas de Inscripción de Estudiantes</h1>
        <div class="button-container">
            <button class="btn btn-primary" onclick="history.back()"><img src="../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</button>
            <button class="pdf-button" onclick="document.getElementById('formCurso').action='pdf_e.php'; document.getElementById('formCurso').submit();"><img src="../img/PDF_file_icon.svg.png" alt="PDF" style="width: 30px; height: 40px;"> Generar PDF</button>
            <button class="csv-button" onclick="descargarCSV()"><img src="../img/excel.png" alt="EXCEL" style="width: 30px; height: 40px;"> Descargar CSV</button>
        </div>
        <div>
    <input type="text" id="buscador" placeholder="Buscar por año o grado">
    <button onclick="cargarDatos('')">Mostrar todos</button>
    </div>
    <div class="table-container" id="tabla-container" style="display: none;">
    <table id="tabla" class="table table-hover table-condensed table-striped">
        <thead>
            <tr>
                <th>Año Escolar</th>
                <th>Grado</th>
                <th>Total Estudiantes</th>
                <th>Total Masculinos</th>
                <th>Total Femeninos</th>
                <th>Total Nuevos</th>
                <th>Total Regulares</th>
                <th>Total Repitientes</th>
            </tr>
        </thead>
        <tbody id="tablaDatos">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['anho']; ?></td>
                    <td><?php echo $row['grado']; ?></td>
                    <td><?php echo $row['total_estudiantes']; ?></td>
                    <td><?php echo $row['total_masculinos']; ?></td>
                    <td><?php echo $row['total_femeninos']; ?></td>
                    <td><?php echo $row['total_nuevos']; ?></td>
                    <td><?php echo $row['total_regulares']; ?></td>
                    <td><?php echo $row['total_repitientes']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
    <div class="footer">
        <?php include '../modulos/footer.php'; ?>
    </div>

    <script>
        $(document).ready(function(){
            $('#buscador').on('keyup', function() {
                cargarDatos(this.value);
            });
        });
        function cargarDatos(filtro) {
            $.get('modulo_estadistico.php', { filtro: filtro }, function(data) {
                let html = $(data).find('#tablaDatos').html();
                $('#tablaDatos').html(html);
            });
        }
    </script>
</body>
</html>
<?php $conexion->close(); ?>