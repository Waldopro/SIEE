<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('modulos/verificar_session.php');
include('modulos/rol3.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="css/inicio.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/fontawesome.js"></script>
    <script src="js/sweetalert211.js"></script>
    <script src="js/tiempo_sesion.js"></script>
    <script src="js/alerta.js"></script>
    <script src="js/menu.js"></script>
    <link rel="stylesheet" href="css/footer.css">
</head>

<body oncontextmenu="return false;">
<div class="wrapper">
    
    <?php include('modulos/menu.php') ?>

    <div class="container">
    <div class="welcome-info">
            <?php
            if (isset($_SESSION['nombre_usuario'])) {
                echo "<span class='bienvenida'>Bienvenido(a), " . esc($_SESSION['nombre_usuario']) . "</span><br>";
            } else {
                echo "<span class='bienvenida'>Nombre de usuario desconocido</span><br>";
            }

             if (isset($_SESSION['tiempo_cierre']) && $_SESSION['tiempo_cierre'] !== '0000-00-00 00:00:00') {
            $hora_conexion = date("d-m-y h:i:s a", strtotime($_SESSION['tiempo_cierre']));
            echo "<span class='ultima-conexion'>Última conexión anterior: " . esc($hora_conexion) . "</span><br>";
            } else {
            echo "<span class='ultima-conexion'>No hay información sobre la última conexión anterior.</span><br>";
    }
    
            ?>
        </div>
        
    <div class="content">
    <h1>Bienvenido al Sistema de inscripción Escolar de la U.E "Eneas Morante"</h1>
    <p>La Unidad Educativa Bolivariana "Eneas Morante", inicia sus actividades el año 1.952 aproximadamente, con el nombre de Escuela Unitaria N.º 506, funcionaba en una casa prestada de un habitante de la comunidad de Bella Vista, cerca de la orilla del Río Claro, Laboraba como docente la Ciudadana Carmen Zorrilla. En 1.968 comienza a funcionar como Escuela Unitaria Nº 702 con la integración de una nueva maestra llamada; Egda de Marcano. Para el año 1973 ingresa la maestra Leónides de Carrera en sustitución de Carmen Zorrilla y logran la asistencia de una persona de la comunidad llamada Prudencia Rivas, el cual se desempeña como bedel y atendida monetariamente por la Alcaldía del Municipio Cajigal.</p>
    </div>
        </div>
        </div>

<div class="footer">
    <?php include './modulos/footer.php';?></div>
</body>

</html>
