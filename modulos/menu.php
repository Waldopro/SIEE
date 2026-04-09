<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
if (session_status() == PHP_SESSION_NONE) {
    configurar_sesion_segura();
}
// Generar CSRF token para operaciones de menú
$csrf_token_menu = generar_token_csrf();
?>
<div class="navbar">
    <ul>
        <?php if (basename($_SERVER['PHP_SELF']) != 'inicio.php' && basename($_SERVER['PHP_SELF']) != 'iniciousuario.php' && basename($_SERVER['PHP_SELF']) != 'iniciodocente.php'): ?>
            <li>
            <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '1'): ?>
                <a href="<?php echo BASE_URL; ?>/inicio.php"><h3><img src="/sistema-UEBEM/img/inicio2.png" alt="Inicio" style="width: 50px; height: 60px;"> Inicio</h3></a>
            <?php elseif (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '2'): ?>
                <a href="<?php echo BASE_URL; ?>/iniciousuario.php"><h3><img src="/sistema-UEBEM/img/inicio2.png" alt="Inicio" style="width: 50px; height: 60px;"> Inicio</h3></a>
            <?php elseif (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '3'): ?>
                <a href="<?php echo BASE_URL; ?>/iniciodocente.php"><h3> <img src="/sistema-UEBEM/img/inicio2.png" alt="Inicio" style="width: 50px; height: 60px;"> Inicio</h3></a>
            <?php endif; ?>
            </li>
        <?php endif; ?>
        <li>
            <a><h3><img src="/sistema-UEBEM/img/gestion3.png" alt="Gestion" style="width: 50px; height: 60px;"> Gestión</h3></a>
            <ul class="submenu">
            <?php if (isset($_SESSION['id_cargo']) && ($_SESSION['id_cargo'] == '1' || $_SESSION['id_cargo'] == '2')): ?>
                <li><a href="<?php echo BASE_URL; ?>/modulos/registro/registro_representante_estudiante.php"><h3>Registrar estudiante</h3></a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '1'): ?>
                    <li><a href="<?php echo BASE_URL; ?>/modulos/inscribir.php"><h3>Inscribir Estudiantes</h3></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/registrar_d.php"><h3>Registrar docente</h3></a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modulos/registro/registro_u.php"><h3>Registrar usuario</h3></a></li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '3'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/evaluacion.php?id_docente=<?php echo intval($_SESSION['id_docente']); ?>"><h3>Evaluar Estudiante</h3></a></li>  
                <?php endif; ?>
            </ul>
        </li>
        <li>
    <a><h3><img src="/sistema-UEBEM/img/listado1.png" alt="Listado" style="width: 50px; height: 60px;"> Listado</h3></a>
    <ul class="submenu">
        <?php if (isset($_SESSION['id_cargo']) && ($_SESSION['id_cargo'] == '1' || $_SESSION['id_cargo'] == '2')): ?>
            <li><a href="<?php echo BASE_URL; ?>/modulos/procesar/procesare.php"><h3>Estudiantes</h3></a></li>
            <li><a href="<?php echo BASE_URL; ?>/modulos/inscritos.php"><h3>Estudiantes Inscritos</h3></a></li>
            <li><a href="<?php echo BASE_URL; ?>/modulos/procesar/procesarr.php"><h3>Representantes</h3></a></li>
            <li><a href="<?php echo BASE_URL; ?>/modulos/procesar/procesarp.php"><h3>Padre o Madre</h3></a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '1'): ?>
            <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/gestion_docentes.php"><h3>Docentes</h3></a></li>
            <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/gestion_curso.php"><h3>Cursos</h3></a></li>
            <li><a href="<?php echo BASE_URL; ?>/modulos/procesar/procesalogin.php"><h3>Usuarios</h3></a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '3'): ?>
            <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/docente.php?id_docente=<?php echo intval($_SESSION['id_docente']); ?>"><h3>Mi Curso</h3></a></li>
            <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/ver_evaluaciones.php?id_docente=<?php echo intval($_SESSION['id_docente']); ?>"><h3>Estudiantes evaluados</h3></a></li>
        <?php endif; ?>
    </ul>
</li>

        <li>
            <a><h3><img src="/sistema-UEBEM/img/documentos2.png" alt="Documentos" style="width: 40px; height: 50px;"> Documentos</h3></a>
            <ul class="submenu">
            <?php if (isset($_SESSION['id_cargo']) && ($_SESSION['id_cargo'] == '1' || $_SESSION['id_cargo'] == '2')): ?>
                <li><a href="<?php echo BASE_URL; ?>/modulos/buscar/buscar_matricula.php">Matricula General</a></li>
                <li><a href="<?php echo BASE_URL; ?>/modulos/buscar/buscar_estudiante.php">Matricula por sección</a></li>
                <li><a href="<?php echo BASE_URL; ?>/modulos/constancias/constancia_inscripcion.php">Constancia de Inscripción</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '1'): ?>
                <li><a href="<?php echo BASE_URL; ?>/modulos_nuevos/estadistica_a.php"><h3>Análisis por año</h3></a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '3'): ?>
                <li><a href="<?php echo BASE_URL; ?>/modulos/actividades.php?id_docente=<?php echo intval($_SESSION['id_docente']); ?>">Actividades</a></li>
                <?php endif; ?>
            </ul>
        </li>
        <li>
            <a><h3><img src="/sistema-UEBEM/img/ayuda2.png" alt="Ayuda" style="width: 40px; height: 50px;"> Ayuda</h3></a>
            <ul class="submenu">
                <li><a href="<?php echo BASE_URL; ?>/modulos/manual_ua.php">Manual de usuario</a></li>
                <li><a href="<?php echo BASE_URL; ?>/modulos/manual_tec.php">Manual Técnico</a></li>
                <?php if (isset($_SESSION['id_cargo']) && $_SESSION['id_cargo'] == '1'): ?>
                <li><a href="<?php echo BASE_URL; ?>/modulos/historial.php">Historial inicio de sesión</a></li>
                <?php endif; ?>
                <li><a href="<?php echo BASE_URL; ?>/modulos/acerca.php">Acerca de</a></li>

            </ul>
        </li>
        <li>
            <button class="logout-button" onclick="cerrarSesion()" style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;"><img src="<?php echo BASE_URL; ?>/img/cerrar.png" alt="Volver" style="width: 40px; height: 50px;">Cerrar Sesión</button>
        </li>
    </ul>
</div>

<script src="<?php echo BASE_URL; ?>/js/sweetalert.min.js"></script>
<script type="text/javascript">
    // Token CSRF global para operaciones JS
    var csrfToken = '<?php echo esc($csrf_token_menu); ?>';

    function cerrarSesion() {
        swal({
            title: "¿Estás seguro?",
            text: "¿Deseas cerrar sesión?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willLogout) => {
            if (willLogout) {
                window.location.href = "<?php echo BASE_URL; ?>/logout.php";
            }
        });
    }

    // Función global para enviar POST con CSRF
    function enviarPostSeguro(url, id) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        var inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = id;
        form.appendChild(inputId);

        var inputCsrf = document.createElement('input');
        inputCsrf.type = 'hidden';
        inputCsrf.name = 'csrf_token';
        inputCsrf.value = csrfToken;
        form.appendChild(inputCsrf);

        document.body.appendChild(form);
        form.submit();
    }
</script>