<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
// Iniciar sesión
include '../verificar_session.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Página de Búsqueda</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 20px;
    }

    h1 {
      color: #333;
    }

    p {
      color: #555;
      margin-bottom: 10px;
    }

    .resultado {
      background-color: #fff;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
  </style>
  <script>
    // Función para realizar la búsqueda asincrónica
    function buscar() {
      var query = document.getElementById("query").value;
      var resultadosDiv = document.getElementById("resultados");

      // Verificar si se ha ingresado un valor de búsqueda
      if (query.trim() !== "") {
        // Realizar una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "buscar.php?query=" + encodeURIComponent(query), true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Actualizar el contenido del div de resultados
            resultadosDiv.innerHTML = xhr.responseText;
          }
        };
        xhr.send();
      } else {
        // Limpiar el div de resultados si no hay consulta
        resultadosDiv.innerHTML = "";
      }
    }
  </script>
</head>
<body oncontextmenu="return false;">
  <h1>Página de Búsqueda</h1>

  <form onsubmit="event.preventDefault(); buscar();">
    <input type="text" id="query" placeholder="Ingrese su búsqueda">
    <input type="submit" value="Buscar">
  </form>

  <!-- Aquí se mostrarán los resultados de la búsqueda -->
  <div id="resultados">
    <!-- Los resultados se mostrarán aquí -->
  </div>
</body>
</html>
