<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include ('../modulos/verificar_session.php');
include ('../modulos/rol1.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../lib/datatable/datatables.min.js"></script>
    <script src="../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../lib/datatable/jquery.dataTables.min.js"></script>
</head>
<body oncontextmenu="return false;">   
    <?php include('../modulos/menu.php')?>
        
    <div class="content">
    <h2><center>Lista de Cursos</center></h2>

    <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"><img src="../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</button>
    <table id="tabla" class="table table-hover table-condensed" >
    <thead>

  <tr>
    <th>ID</th>
    <th>Aula</th>
    <th>Grado</th>
    <th>Sección</th>
    <th>Docente</th>
    <th>Capacidad</th>
    <th>Año</th>
  </tr>
  </thead>
  <tbody>    

  <?php
  // Conexión a la base de datos y consulta de las aulas
  include '../modulos/conexion/conndb.php';
  $consulta = "
    SELECT 
        curso.id, 
        aulas.nombre AS nombre_aula, 
        grados.nombre AS nombre_grado, 
        secciones.nombre AS nombre_seccion, 
        CONCAT(docentes.nombres, ' ', docentes.apellidos) AS nombre_docente, 
        curso.capacidad, 
        curso.anho 
    FROM curso
    JOIN aulas ON curso.id_aula = aulas.id_aula
    JOIN grados ON curso.id_grado = grados.id
    JOIN secciones ON curso.id_seccion = secciones.id
    JOIN docentes ON curso.id_docente = docentes.id";
  $resultado = $conexion->query($consulta);

  // Verificar si se encontraron aulas registradas
  if ($resultado->num_rows > 0) {
      // Mostrar las aulas en la tabla
      while ($fila = $resultado->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $fila['id'] . "</td>";
          echo "<td>" . $fila['nombre_aula'] . "</td>";
          echo "<td>" . $fila['nombre_grado'] . "</td>";
          echo "<td>" . $fila['nombre_seccion'] . "</td>";
          echo "<td>" . $fila['nombre_docente'] . "</td>";
          echo "<td>" . $fila['capacidad'] . "</td>";
          echo "<td>" . $fila['anho'] . "</td>";
          echo "</tr>";
      }
  } else {
      // Mostrar mensaje si no se encontraron aulas registradas
      echo "<tr><td colspan='7'>No se encontraron aulas registradas</td></tr>";
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
</body>
</html>
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
});
</script>