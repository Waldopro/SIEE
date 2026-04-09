<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include 'error.php';
include('verificar_session.php');

$rol = $_SESSION['id_cargo'] ?? '';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script src="../js/sweetalert.min.js"></script>
    
    <title>Lista de Estudiantes Inscritos</title>
</head>
<body oncontextmenu="return false;">   
    
   
    <?php include('menu.php'); ?>
    <div class="content">
    <center><h2>Estudiantes Inscritos</h2></center>
    <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"><img src="../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</button>

    <table id="tabla" class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Nombre del Estudiante</th>
                <th>Curso</th>
                <th>Representante</th>
                <th>Tipo de Inscripción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'conexion/conndb.php';
            
            $query = "
                SELECT DISTINCT i.id AS inscripcion_id,
                e.cedula_e AS cedula, 
                CONCAT(e.nombres_e, ' ', e.apellidos_e) AS estudiante,
                CONCAT(g.nombre, ' ', s.nombre) AS curso,
                CONCAT(r.nombres, ' ', r.apellidos) AS representante,
                t.tipo AS tipo_inscripcion
FROM inscripcion i
INNER JOIN estudiante e ON i.id_estudiante = e.id_estudiante
INNER JOIN curso c ON i.id_curso = c.id
INNER JOIN grados g ON c.id_grado = g.id
INNER JOIN secciones s ON c.id_seccion = s.id
INNER JOIN familia f ON i.id_representante = f.id_representante
INNER JOIN representante r ON f.id_representante = r.id_representante
INNER JOIN tipo_ins t ON i.id_tipo_ins = t.id";

            $result = $conexion->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . esc($row['inscripcion_id']) . "</td>
                             <td>" . esc($row['cedula']) . "</td>
                            <td>" . esc($row['estudiante']) . "</td>
                            <td>" . esc($row['curso']) . "</td>
                            <td>" . esc($row['representante']) . "</td>
                            <td>" . esc($row['tipo_inscripcion']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay estudiantes inscritos</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div> 
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>

<script>
    $(document).ready(function(){
    $('#tabla').DataTable({
        'language':{
        "lengthMenu": "Mostrar <select class='custom-select custom-select-sm form-control form-control-sm' >\
                        <option value='5' >5</option>\
                        <option value='10'>10</option>\
                        <option value='50'>50</option> \
                        <option value='-1'>Todos</option>\
                        </select > Registros", 
        "zeroRecords":"Nada encontrado - disculpa",
        "info":"mostrando la pagina _PAGE_ de _PAGES_",
        "infoEmpty":"No hay resultados disponibles",
        "infoFiltered":"(filtrado de _MAX_ registros totales)",
        "search":"Buscar",
        "paginate":{
            "next":"siguiente",
            "previous":"Anterior"
        },                    
        "emptyTable":"No hay Registros",                   
    }
    });
    // Mostrar alerta si la inscripción fue exitosa
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'success') {
            swal("Inscripción Exitosa", "El estudiante ha sido inscrito correctamente.", "success");
        }
    });
</script>
</body>
</html>