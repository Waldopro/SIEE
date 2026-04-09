<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include ('../modulos/verificar_session.php');
include ('../modulos/rol1.php');

include '../modulos/conexion/conndb.php';

$csrf_token = generar_token_csrf();
?>
<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../css/registro_e.css">
    <link rel="stylesheet" type="text/css" href="../css/contraseña.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" href="../lib/fontawesome-free-6.4.2-web/css/all.css">
    <title>Registro de Docente</title>
</head>
<body oncontextmenu="return false;">
<?php include('../modulos/menu.php') ?>

<h1 class="h1">
            <center>Registro de Docente</center>
        </h1>
    <form method="POST" action="procesar_d.php">
        <?php echo campo_csrf(); ?>
    <div class="contenedor-formularios">
        
        <div class="formulario"> 
            <label for="cedula">Cedula:<span style="color: red;">*</span></label>
            <input type="number" name="cedula" id="cedula" required placeholder="14105489">
            <label for="nombres">Nombres:<span style="color: red;">*</span></label>
            <input type="text" id="nombres" name="nombres" required placeholder="Ej: Jose">
                <label for="apellidos">Apellidos:<span style="color: red;">*</span></label>
            <input type="text" id="apellidos" name="apellidos" required placeholder="Ej: Granado">
                <label for="gdo_ins">Grado de instrucción:<span style="color: red;">*</span></label>
            <select id="gdo_ins" name="gdo_ins" required>
                <option value="">Seleccionar</option>
                <option value="1">Grado 1</option>
                <option value="2">Grado 2</option>
                <option value="3">Grado 3</option>
                <option value="4">Grado 4</option>
                <option value="5">Grado 5</option>
                <option value="6">Grado 6</option>
                <option value="7">Grado 7</option>
            </select>
                
                <button type="submit" class="btn-primary" value="Guardar"><img src="../img/guardar.png" alt="Guardar" style="width: 40px; height: 50px;"> Guardar</button>
            <button id="boton1" style="background-color: blue;color: #f2f2f2;padding: 10px 20px;text-decoration: none; border: none; border-radius: 20px;  cursor: pointer;" onclick="history.back()"> <img src="../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</button>  
        </div>
    </div>
    </form>
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>
</body>
</html>