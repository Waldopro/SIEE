<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../error.php';
include('../verificar_session.php');

$rol = $_SESSION['id_cargo'] ?? '';
$csrf_token = generar_token_csrf();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Procesar Formulario estudiante</title>
    <meta charset="UTF-8">
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
</head>
<body oncontextmenu="return false;">   
    <?php include('../menu.php')?>
    <div class="content">
        <center><h2 class="">Lista de estudiantes</h2></center>
        <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"><img src="../../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</button>
        
        <table id="tabla" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cédula</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Representante</th>
                    <th>Padre o Madre</th>
                    <th>Foto</th>
                    <th class="opciones">Opción</th>
                </tr>    
            </thead>
            <tbody>    
            <?php
            include '../conexion/conndb.php';
        
            $sql = "SELECT 
                        e.nombres_e AS nombre, 
                        e.cedula_e AS cedula, 
                        e.id_estudiante AS id, 
                        e.apellidos_e AS apellido, 
                        r.nombres AS representante, 
                        pm.nombres_p AS padre_madre, 
                        f.foto AS foto,
                        e.fotos AS fotos
                    FROM 
                        estudiante e
                    JOIN 
                        familia f ON e.id_estudiante = f.id_estudiante
                    JOIN 
                        representante r ON r.id_representante = f.id_representante
                    JOIN 
                        padre_madre pm ON pm.id_padre_madre = f.id_padre_madre";         
             $result = $conexion->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . esc($row["id"]) . "</td>";
                    echo "<td>" . esc($row["cedula"]) . "</td>";
                    echo "<td>" . esc($row["apellido"]) . "</td>";
                    echo "<td>" . esc($row["nombre"]) . "</td>";
                    echo "<td>" . esc($row["representante"]) . "</td>";
                    echo "<td>" . esc($row["padre_madre"]) . "</td>";
                    echo "<td><img src='../uploads/estudiantes/" . esc($row["foto"]) . "' alt='Foto de " . esc($row["nombre"]) . "' style='width:50px; height:auto; cursor:pointer;' onclick='showOverlay(\"../uploads/estudiantes/" . esc($row["foto"]) . "\")'></td>";                    
                    echo "<td>";
                    echo "<center> <a class='btn btn-primary a ver-btn' href='#' data-id=" . intval($row["id"]) . "><img src='../../img/informacion1.png' alt='Ver' style='width: 40px; height: 50px;'> Ver</a>";
                    echo " <a class='btn btn-primary a ' href='../modificar/editare.php?id=" . intval($row["id"]) . "'> <img src='../../img/modificar.png' alt='Modificar' style='width: 40px; height: 50px;'> Modificar</a>  ";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No se encontraron estudiantes registrados</td></tr>";
            }
            $conexion->close();
            ?>
            </tbody>
        </table>
        <!-- Overlay para la imagen -->
<div class="overlay" id="overlay">
    <span class="close-btn" onclick="closeOverlay()">✖</span>
    <img id="overlay-img" src="" alt="Imagen ampliada">
</div>
    </div>  
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>          

<script src="../../js/sweetalert.min.js"></script>
<script>
    var csrfToken = '<?php echo esc($csrf_token); ?>';
</script>
<script type="text/javascript">
    function showOverlay(imgSrc) {
    var overlay = document.getElementById("overlay");
    var overlayImg = document.getElementById("overlay-img");
    overlayImg.src = imgSrc;
    overlay.style.display = "flex";
}

function closeOverlay() {
    document.getElementById("overlay").style.display = "none";
}

$(document).on('click', '.ver-btn', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    DatosEstudiante(id);
});

function DatosEstudiante(id) {
    $.ajax({
        url: '../datos_estudiante.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            var datos = JSON.parse(response);
            mostrarAlerta(datos);
        },
        error: function() {
            swal("Error", "Ocurrió un error al obtener los datos del estudiante.", "error");
        }
    });
}

function mostrarAlerta(datos) {
    var texto = "Fecha de Nacimiento: " + datos.fecha_nac + "\n" +
                "Edad: " + datos.edad_e + "\n" +
                "Sexo: " + datos.sexo + "\n" +
                "Peso: " + datos.peso + "\n" +
                "Talla: " + datos.talla + "\n" +
                "Estado: " + datos.estado + "\n" +
                "Municipio: " + datos.municipio + "\n" +
                "Parroquia: " + datos.parroquia + "\n" +
                "Lugar de Nacimiento: " + datos.lugar_nac + "\n" +
                "Dirección: " + datos.direccion_e + "\n" +
                "Teléfono: " + datos.tel_e + "\n" +
                "Posee Canaima: " + datos.posee_can + "\n" +
                "Serial de la Canaima: " + (datos.ser_can || 'N/A') + "\n" +
                "Becado: " + datos.becado + "\n" +
                "Copias de Cedula: " + datos.copias_ci + "\n" +
                "Partida de Nacimiento Copia: " + datos.partida_nac_co + "\n" +
                "Fotos: " + datos.fotos + "\n" +
                "Historia Escolar: " + datos.historia_esc + "\n" +
                "Certificado de Promoción: " + datos.cert_prom + "\n" +
                "Tarjeta de Vacunación: " + datos.tarj_vac + "\n" +
                "Constancia de Conducta: " + datos.cons_conduc + "\n" +
                "Constancia de Retiro: " + datos.cons_retiro + "\n" +
                "Certificación de Notas: " + datos.cert_notas;
    swal("Datos del estudiante", texto, "info");
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

    // Alertas de estado
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    if (status === 'success') {
        swal("Éxito", "El estudiante ha sido registrado/modificado correctamente.", "success");
    } else if (status === 'error') {
        swal("Error", "Hubo un problema al procesar al estudiante.", "error");
    }
    if (urlParams.has('eliminacion_exitosa')) {
        swal("Éxito", "El estudiante ha sido eliminado correctamente.", "success");
    }
});
</script>
</body>
</html>
