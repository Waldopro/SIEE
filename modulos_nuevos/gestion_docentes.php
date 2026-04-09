<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include ('../modulos/verificar_session.php');
include ('../modulos/rol1.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de docentes</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/dataTables.bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../lib/datatable/datatables.min.js"></script>
    <script src="../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../lib/datatable/jquery.dataTables.min.js"></script>
    <script src="../js/h.d.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="../css/footer.css">

</head>
<body oncontextmenu="return false;">   
    <?php include('../modulos/menu.php')?>
        
    <div class="content">
            
            <center><h2 class="">Lista de docentes</h2></center><br>
            <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"><img src="../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</button>
        <table id="tabla" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>ID:</th>
                    <th>Cedula:</th>
                    <th>nombres:</th>
                    <th>apellido:</th>
                    <th>grado instucción:</th>
                    <th>Actv/Des</th>
                    
                    <th class="opciones">Opciones</th>
                </tr>    
            </thead>
            
            <tbody>    

            <?php
            include '../modulos/conexion/conndb.php';
        
            // Realizar la consulta a la base de datos
            // Consultar la información del aula asignada a cada docente
$sql = "SELECT docentes.* FROM docentes";
$result= $conexion->query($sql);
          
            
            // Verificar si se obtuvieron resultados

            if ($result->num_rows > 0) {
                // Mostrar los datos en la tabla
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["cedula"] . "</td>";
                    echo "<td>" . $row["nombres"] . "</td>";
                    echo "<td>" . $row["apellidos"] . "</td>";
                    echo "<td>" . $row["gdo_ins"] . "</td>";
                    echo "<td>";
                    if (isset($row['habilitado']) && $row['habilitado']) {
                        echo "<a class='btn btn-danger a btn-eliminar' href='#' onclick='desactivardocente(" . $row['id'] . "); return false;'><img src='../img/desactivar2.png' alt='Desactivar' style='width: 40px; height: 50px;'>Deshabilitar</a>";
                    } else {
                        echo "<button class='btn btn-primary a' onclick='habilitardocente(" . $row['id'] . ");'>
                        <img src='../img/habilitar3.png' alt='Habilitar' style='width: 40px; height: 50px;'> 
                        Habilitar
                      </button>";
                                    }
                    echo "</td>";
                    echo "<td>";
                    echo " <a class='btn btn-primary a ' href='editard.php?id=" . $row["id"] . "'><img src='../img/modificar.png' alt='Modificar' style='width: 40px; height: 50px;'> Modificar</a>  ";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No se encontraron docentes registrados</td></tr>";
            }

            // Cerrar la conexión a la base de datos
            $conexion->close();
            ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <?php include '../modulos/footer.php'; ?>
    </div>          
    <script>
        function habilitardocente(id) {
            swal({
                title: "¿Estás seguro?",
                text: "¿Estás seguro de que deseas habilitar a este docente?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willEnable) => {
                if (willEnable) {
                    window.location.href = 'deshabilitar_docente.php?id=' + id + '&accion=habilitar';
                }
            });
        }

        function desactivardocente(id) {
            swal({
                title: "¿Estás seguro?",
                text: "¿Estás seguro de que deseas deshabilitar a este docente?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDisable) => {
                if (willDisable) {
                    window.location.href = 'deshabilitar_docente.php?id=' + id + '&accion=deshabilitar';
                }
            });
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

            // Mostrar alerta si la operación fue exitosa
            const urlParams = new URLSearchParams(window.location.search);
            const estado = urlParams.get('estado');
            if (estado === 'habilitado') {
                swal("Éxito", "Docente habilitado correctamente.", "success");
            } else if (estado === 'deshabilitado') {
                swal("Éxito", "Docente deshabilitado correctamente.", "success");
            }
        });
    </script>
</body>
</html>