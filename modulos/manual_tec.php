<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('verificar_session.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manual de Usuario</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../js/tiempo_sesion.js"></script>
    <script src="../js/alerta.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/sweetalert211.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/fontawesome.js"></script>
</head>
<body oncontextmenu="return false;">
<div class="wrapper">
    <?php include('menu.php') ?>
    <div class="container">
        <div class="content">
    <center><h1>Manual Técnico</h1></center>

    <embed src="../docs/Manual_t.pdf" type="application/pdf" width="100%" height="600px" />
    </div>
    
</div>
<div class="footer">
        <?php include 'footer.php';?>
    </div>
</body>
</html>