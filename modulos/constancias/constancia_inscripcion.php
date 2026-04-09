<?php 
// Verificar si el usuario ha iniciado sesión y si su rol es de "usuario"
include('../verificar_session.php');

$rol = $_SESSION['id_cargo'] ?? ''; // Valor predeterminado en caso de que la variable de sesión no esté definida

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
  <link rel="stylesheet" type="text/css" href="../../css/procesare.css">
  <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
  <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
  <link rel="stylesheet" type="text/css" href="../../css/procesalogin.css">
  <link rel="stylesheet" href="../../css/footer.css">
      
    <title>Generar de Inscripción</title>
    <link rel="stylesheet" href="../../css/buscar.css">
    <script>
        function validarFormulario() {
            var cedulaInput = document.getElementById("cedula_e");
            var cedula = cedulaInput.value.trim();

            // Verificar si la cédula contiene solo dígitos y tiene 10 caracteres
            if (!/^\d{8}$/.test(cedula)) {
                alert("La cédula debe contener 8 dígitos numéricos.");
                cedulaInput.focus();
                return false;
            }

            return true;
        }
        function redireccionar() {
        // Obtener el valor del rol almacenado en el atributo "data-rol"
        var rol = document.querySelector(".a").getAttribute("data-rol");

        if (rol === '1') {
            window.location.href = "../../inicio.php";
        } else if (rol === '2') {
            window.location.href = "../../iniciousuario.php";
        } else {
            // Si el rol no coincide con ninguna opción conocida, puedes redirigir a una página de error o alguna otra acción
            alert("Rol desconocido o no válido");
        }
    }
    </script>
</head>
<style>
button {
  display: inline-block;
  padding: 10px 20px;
  font-size: 14px;
  background-image: linear-gradient(to bottom, #007bff, #0000ff);
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
}

button:hover {
  background-image: linear-gradient(to bottom, #007bff, #0000ff);
}
</style>

<body  oncontextmenu="return false;">

    <?php include('../menu.php') ?>

    <div class="content">

        <h1>Generar Constancia de Inscripción</h1>
        <form method="GET" action="../buscar/buscar_constancia_ins.php" onsubmit="return validarFormulario()">
            <label for="cedula_e">Ingrese la cédula:</label>
            <input type="text" name="cedula_e" id="cedula_e" required>
            <button type="submit">
            <img src="../../img/buscar1.png" alt="Buscar" style="width: 40px; height: 50px;">Buscar</button>
            
        </form>
    </div>

    <div class="footer">
    Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados
    </div>
</body>
</html>
