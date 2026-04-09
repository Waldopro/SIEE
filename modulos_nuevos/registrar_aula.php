<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../css/registro_e.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" href="../lib/fontawesome-free-6.4.2-web/css/all.css">
    <title>Registrar aula</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body oncontextmenu="return false;">
<?php include('../modulos/menu.php') ?>

<h1 class="h1"><center>Registrar aula</center></h1>

<form action="procesar_aula.php" method="POST">
    <div class="contenedor-formularios">
        <div class="formulario">
            <label for="aula">Aula:</label>
            <input type="text" id="nombre" name="nombre" required><br>
  
            <input type="submit" class="btn btn-primary" value="Guardar">
            <button id="boton1" style="background-color: blue;color: #f2f2f2;padding: 10px 20px;text-decoration: none; border: none; border-radius: 20px; cursor: pointer;" onclick="history.back()">Regresar</button>  
        </div>
    </div>
</form>


<div class="footer">
    <?php include '../modulos/footer.php';?>
</div>
</body>
</html>
