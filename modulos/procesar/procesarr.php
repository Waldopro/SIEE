<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('../verificar_session.php');

$csrf_token = generar_token_csrf();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/procesarr.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/dataTables.bootstrap.min.css">
    <script src="../../lib/datatable/datatables.min.js"></script>
    <script src="../../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../../lib/datatable/jquery.dataTables.min.js"></script>
    
    <title>Representantes</title>

</head>

<body oncontextmenu="return false;">
    
    <?php include('../menu.php') ?>

    <div class="content">  

        <center><h2>Lista de Representantes</h2></center>
        
        <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"> <img src="../../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</button>
        <table id="tabla" class="table table-hover table-condensed">
            <thead>
        <tr>
                <th>ID</th>
                <th>Cédula</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Fecha de nacimiento</th>
                <th>Edad</th>
                <th class="opciones">Opción</th>            
            </tr>
            </thead>
<tbody>
            <?php
    include '../conexion/conndb.php';   
    $sql = "SELECT id_representante, cedula_r, apellidos, nombres, fecha_nacimiento, edad FROM representante";
$result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . esc($row["id_representante"]) . "</td>";
            echo "<td>" . esc($row["cedula_r"]) . "</td>";
            echo "<td>" . esc($row["apellidos"]) . "</td>";
            echo "<td>" . esc($row["nombres"]) . "</td>";
            echo "<td>" . esc($row["fecha_nacimiento"]) . "</td>";
            echo "<td>" . esc($row["edad"]) . "</td>";
            echo "<td>";
            echo "<center> <a class='btn btn-primary a ver-btn-r' href='#' data-id='" . intval($row["id_representante"]) . "'> <img src='../../img/informacion1.png' alt='Ver' style='width: 40px; height: 50px;'> Ver</a>  ";
            echo "<a class='btn btn-primary a ' href='../modificar/editarr.php?id=" . intval($row["id_representante"]) . "'><img src='../../img/modificar.png' alt='Modificar' style='width: 40px; height: 50px;'> Modificar</a>  ";
            echo "</td>";
            echo "</tr>";
        }
    }else {
        echo "<tr><td colspan='7'>No se encontraron Representantes Registrados</td></tr>";
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
        $(document).on('click', '.ver-btn-r', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    DatosRepresentante(id);
});
function DatosRepresentante(id) {
    $.ajax({
        url: '../verr.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            var datos = JSON.parse(response);
            mostrarAlerta(datos);
        },
        error: function() {
            swal("Error", "Ocurrió un error al obtener los datos del representante.", "error");
        }
    });
}
function mostrarAlerta(datos) {
    var texto = 
                "Ocupación: " + (datos.ocupacion || 'N/A') + "\n" +
                "Telefono: " + (datos.tel_r || 'N/A') + "\n" +
                "Dirección: " + (datos.direccion_r || 'N/A') + "\n" +
                "Parentesco: " + (datos.parentesco || 'N/A') + "\n" +
                "Ingreso Mensual: " + (datos.ingreso_mes || 'N/A') + "\n" +
                "Carnet de la patria: " + (datos.carnet_patria || 'N/A') + "\n" +
                "Serial Carnet: " + (datos.ser_car || 'N/A') + "\n" +
                "Código Carnet: " + (datos.codigo_car || 'N/A') + "\n" +
                "Entidad Bancaria: " + (datos.entidad_ban || 'N/A') + "\n" +
                "Tipo de Cuenta: " + (datos.tipo_cta || 'N/A') + "\n" +
                "Numero de Cuenta: " + (datos.num_cuenta || 'N/A') + "\n";

    swal("Datos del representante", texto, "info");
}
    
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

        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('edicion_exitosa')) {
            swal("¡Éxito!", "El Representante se editó con éxito.", "success");
        } else if (urlParams.has('eliminacion_exitosa')) {
            swal("¡Éxito!", "El Representante se eliminó con éxito.", "success");
        }
    });

    function eliminar(id){
    swal({
        title: "¿Estás seguro?",
        text: "Deseas, eliminar este Representante!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            enviarPostSeguro('../eliminar/eliminarr.php', id);
        }
    });
}

</script>
</body>

</html>