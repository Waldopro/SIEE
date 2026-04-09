<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('../verificar_session.php');
include '../error.php';

$csrf_token = generar_token_csrf();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Padres</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/procesarr.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/footer_e.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/dataTables.bootstrap.min.css">
    <script src="../../lib/datatable/datatables.min.js"></script>
    <script src="../../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../../lib/datatable/jquery.dataTables.min.js"></script>
</head>

<body oncontextmenu="return false;">
<?php include('../menu.php') ?>
    
    <div class="content">
    <center><h2>Lista de Padres</h2></center>
    <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"><img src="../../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;"> Volver</button>
        <table id="tabla" class="table table-hover table-condensed">
            <thead>
        <tr>
            <th>ID</th>
            <th>Cedula</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>Fecha de nacimiento</th>
            <th>Edad</th>
            <th>Ocupacion</th>
            <th>Telefono</th>
            <th>Direccion</th>
            <th>Parentesco</th>
            <th class="opciones">Opción</th>        
        </tr>
        </thead>
<tbody>
        <?php
include '../conexion/conndb.php';

$sql = "SELECT * FROM padre_madre";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . esc($row["id_padre_madre"]) . "</td>";
        echo "<td>" . esc($row["cedula_p"]) . "</td>";
        echo "<td>" . esc($row["apellidos_p"]) . "</td>";
        echo "<td>" . esc($row["nombres_p"]) . "</td>";
        echo "<td>" . esc($row["fecha_nac_p"]) . "</td>";
        echo "<td>" . esc($row["edad"]) . "</td>";
        echo "<td>" . esc($row["ocupacion_p"]) . "</td>";
        echo "<td>" . esc($row["tel_p"]) . "</td>";
        echo "<td>" . esc($row["direccion_p"]) . "</td>";
        echo "<td>" . esc($row["parentesco"]) . "</td>";
        echo "<td>";
            echo "<center><a class='btn btn-primary a ' href='../modificar/editarp.php?id=" . intval($row["id_padre_madre"]) . "'><img src='../../img/modificar.png' alt='Modificar' style='width: 40px; height: 50px;'>modificar</a>  ";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='11'>No se encontraron Padres o Madres Registrados</td></tr>";
}

$conexion->close();
?>
</tbody>
    </table>
</div>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div>
<script src="../../js/sweetalert.min.js"></script>

<script type="text/javascript">
    
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
                "next":"siguiente" ,
                "previous":"Anterior"
            },                    
            "emptyTable":"No hay Registros",                   
        }
        });

        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('edicion_exitosa')) {
            swal("¡Éxito!", "El Padre o la Madre se editó con éxito.", "success");
        } else if (urlParams.has('eliminacion_exitosa')) {
            swal("¡Éxito!", "El Padre o la Madre se eliminó con éxito.", "success");
        }
    });

    function eliminar(id){
    swal({
        title: "¿Estás seguro?",
        text: "¿Deseas eliminar este Padre o madre?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            enviarPostSeguro('../eliminar/eliminarp.php', id);
        }
    });
}

</script>
  
</body>

</html>