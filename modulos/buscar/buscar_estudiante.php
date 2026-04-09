<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <link rel="stylesheet" href="../../css/fontawesome.css">
  <link rel="stylesheet" href="../../css/procesare.css">
  <link rel="stylesheet" href="../../css/inicio.css">
  <link rel="stylesheet" href="../../css/registro_e.css">
  <link rel="stylesheet" href="../../css/procesalogin.css">
  <link rel="stylesheet" href="../../css/footer.css">
  <link rel="stylesheet" type="text/css" href="../../css/alerta.css">
  <title>Buscador de Estudiantes</title>
  <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../../js/sweetalert211.js"></script> <!-- Cargar SweetAlert2 -->
</head>
<body oncontextmenu="return false;">
  <?php include('../menu.php'); ?>
  <h1 class="h1"><center>Matricula por seccion</center></h1>

  <form id="search-form">
    <div class="contenedor-formularios">
      <div class="formulario">
        <label for="grado">Grado:</label>
        <select name="grado" id="grado">
          <option value="">Seleccione un grado</option>
          <option value="1">Preescolar</option>
          <option value="2">1ro</option>
          <option value="3">2do</option>
          <option value="4">3ro</option>
          <option value="5">4to</option>
          <option value="6">5to</option>
          <option value="7">6to</option>
        </select>

        <label for="seccion" id="seccion-label" style="display:none;">Sección:</label>
        <select name="seccion" id="seccion" style="display:none;">
          <option value="">Seleccione una sección</option>
        </select>

        <button class="btn btn-primary a " type="submit">
  <img src="../../img/buscar1.png" alt="Buscar" style="width: 40px; height: 50px;">
  Buscar
</button>
        </div>
    </div>
  </form>

  <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
  </div>

  <script>
  $(document).ready(function() {
    // Manejar el cambio de selección en el campo "grado"
    $("#grado").change(function() {
      var selectedGrado = $(this).val();

      if (selectedGrado != "") {
        // Realizar una solicitud AJAX para obtener las secciones disponibles para el grado seleccionado
        $.ajax({
          url: "buscar_secciones.php", // Archivo PHP que devolverá las secciones disponibles
          method: "GET",
          data: { grado: selectedGrado },
          dataType: "json",
          success: function(response) {
            // Limpiar las opciones actuales
            $("#seccion").empty();
            $("#seccion").append('<option value="">Seleccione una sección</option>');

            // Verificar si hay secciones disponibles
            if (response.length > 0) {
              // Agregar las nuevas opciones
              $.each(response, function(index, seccion) {
                $("#seccion").append('<option value="' + seccion.id + '">' + seccion.nombre + '</option>');
              });
              // Mostrar el selector de sección
              $("#seccion-label").show();
              $("#seccion").show();
            } else {
              // Si no hay secciones, ocultar el selector de sección
              $("#seccion-label").hide();
              $("#seccion").hide();
            }
          },
          error: function() {
            alert("Hubo un error al cargar las secciones.");
          }
        });
      } else {
        // Si no se selecciona un grado, ocultar el selector de sección
        $("#seccion-label").hide();
        $("#seccion").hide();
      }
    });

    // Escuchar el evento de envío del formulario
    $("#search-form").submit(function(event) {
      event.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

      // Obtener los valores de los campos del formulario
      var seccion = $("#seccion").val();
      var grado = $("#grado").val();

      // Realizar la solicitud AJAX
      $.ajax({
        url: "../procesar/procesar_busqueda.php", // Archivo PHP que realizará la búsqueda en la base de datos
        method: "GET",
        data: { seccion: seccion, grado: grado }, // Datos enviados al servidor
        dataType: "json",
        success: function(response) {
          if (response.success) {
            // Mostrar los resultados en una alerta de SweetAlert
            var results = '<table class="table table-hover table-condensed"><thead><tr><th>ID</th><th>Cédula</th><th>Nombres</th><th>Apellidos</th><th>Edad</th><th>Sexo</th><th>Sección</th><th>Grado</th></tr></thead><tbody>';
            
            response.data.forEach(function(student) {
              results += '<tr>';
              results += '<td>' + student.id_estudiante + '</td>';
              results += '<td>' + student.cedula_e + '</td>';
              results += '<td>' + student.nombres_e + '</td>';
              results += '<td>' + student.apellidos_e + '</td>';
              results += '<td>' + student.edad_e + '</td>';
              results += '<td>' + student.sexo + '</td>';
              results += '<td>' + student.nombre_seccion + '</td>';
              results += '<td>' + student.nombre_grado + '</td>';
              results += '</tr>';
            });

            results += '</tbody></table>';

            // Mostrar la alerta con los resultados y el botón para imprimir
            Swal.fire({
              title: 'Resultados de la búsqueda',
              html: results,
              width: '80%',  // Establece el ancho de la alerta
              showCancelButton: true,
              confirmButtonText: '<img src="../../img/s.png"" alt="PDF" style="width: 40px; height: 50px;" > Generar PDF',  // Imagen personalizada
              confirmButtonColor: '#ff0000',  // Color rojo para el botón
              cancelButtonText: 'Cerrar',
              cancelButtonColor: '#0000ff',  // Color azul para el botón
              customClass: {
    confirmButton: 'btn-imprimir',  // Clase para el botón "Imprimir PDF"
    cancelButton: 'btn-cerrar'  // Clase para el botón "Cerrar"
  },
              preConfirm: () => {
                imprimirPDF(seccion, grado); // Llamada a la función de imprimir
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'No se encontraron estudiantes',
              text: 'No hay estudiantes para el grado y sección seleccionados.'
            });
          }
        },
        error: function() {
          alert("Hubo un error al realizar la búsqueda.");
        }
      });
    });

    // Función para imprimir el PDF
    function imprimirPDF(seccion, grado) {
      window.location.href = `../procesar/procesar_busqueda.php?seccion=${seccion}&grado=${grado}&imprimir=1`;
    }
  });
  </script>
</body>
</html>
