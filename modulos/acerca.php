<?php 
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('verificar_session.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de</title>
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
            <h1 class=""><center>Acerca de</center></h1>
            <section class="system-version">
                <h2>Versión del Sistema</h2>
                <p>Versión actual: 2.0.0</p>
            </section>

            <section class="technologies">
                <h2>Tecnologías Utilizadas</h2>
                <ul>
                    <li>PHP 8</li>
                    <li>JavaScript</li>
                    <li>JQUERY</li>
                    <li>Bootstrap</li>
                    <li>Html 5</li>
                    <li>CSS 3</li>
                    <li>AJAX</li>
                    <li>MySQL</li>
                </ul>
            </section>
            <section class="intro">
                <h2>Quiénes Somos</h2>
                <p>Somos unos estudiantes de la U.P.T.P "Luis Mariano Rivero" extensión Cajigal que 
                    nos dedicamos día a día a aprender e implementar cosas nuevas para así mejorar
                    nuestros sistemas que estamos elaborando.</p>
            </section>

            <section class="mission-vision">
                <h2>Misión y Visión</h2>
                <p><strong>Misión:</strong> Nuestra misión es desarrollar soluciones tecnológicas innovadoras que mejoren la eficiencia y productividad de nuestros usuarios.</p>
                <p><strong>Visión:</strong> Ser reconocidos como líderes en el desarrollo de software de alta calidad y contribuir al avance tecnológico de nuestra comunidad.</p>
            </section>

            <section class="history">
                <h2>Nuestra Historia</h2>
                <p>El proyecto comenzó en 2023 con el objetivo de crear un sistema de inscripción eficiente para la UEBEM. Desde entonces, hemos trabajado arduamente para mejorar y expandir nuestras funcionalidades.</p>
            </section>

            <section class="achievements">
                <h2>Logros</h2>
                <ul>
                    <li>Implementación exitosa del sistema de inscripcion estadistico en la UEBEM.</li>
                    </ul>
            </section>

            <section class="team">
                <h2>Conoce a Nuestro Equipo</h2>
                <div class="team-member">
                    <img src="../img/equipo/foto1.jpg" alt="Foto del miembro del equipo">
                    <h3>Oswaldo Monasterio</h3>
                </div>
                <div class="team-member">
                    <img src="../img/equipo/foto2.jpeg" alt="Foto del miembro del equipo">
                    <h3>Danny Marcano</h3>
                </div>
                <div class="team-member">
                    <img src="../img/equipo/foto3.jpeg" alt="Foto del miembro del equipo">
                    <h3>Yudennys Espinoza</h3>
                </div>
            </section>

            <section class="contact">
                <h2>Contacto</h2>
                <p>Puedes contactarnos en:</p>
                <p>Email: oswaldojmonasterioa@gmail.com</p>
                <p>Teléfono: 04248205484, 04124466061 y 04123474422</p>
        </div>
    </div>
    <div class="footer">
        <?php include 'footer.php';?>
    </div>
</div>
</body>
</html>