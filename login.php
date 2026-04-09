<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
configurar_sesion_segura();

// Destruir sesión anterior si existe
session_unset();
session_destroy();

// Reiniciar sesión limpia
configurar_sesion_segura();
$_SESSION['id_cargo'] = '';

// Generar token CSRF para el formulario
$csrf_token = generar_token_csrf();
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inicio de Sesión</title>
	<link rel="stylesheet" href="lib/fontawesome-free-6.4.2-web/css/all.css">
	<link rel="stylesheet" href="css/cdnjs.cloudflare.com_ajax_libs_animate.css_4.0.0_animate.min.css">
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/footer_e.css">
</head>

<body>
		<div class="container">
			<div class="content">
				<h1>Bienvenido al Sistema de inscripción Escolar de la U.E "Eneas Morante"</h1>
				<p>La Unidad Educativa Bolivariana "Eneas Morante", inicia sus actividades el año 1.952 aproximadamente,
					con el nombre de Escuela Unitaria N.º 506, funcionaba en una casa prestada de un habitante de la comunidad de Bella Vista,
					cerca de la orilla del Río Claro, Laboraba como docente la Ciudadana Carmen Zorrilla. En 1.968 comienza a
					funcionar como Escuela Unitaria Nº 702 con la integración de una nueva maestra llamada; Egda de Marcano. Para el año 1973
					ingresa la maestra Leónides de Carrera en sustitución de Carmen Zorrilla y logran la asistencia de una persona de la comunidad
					llamada Prudencia Rivas, el cual se desempeña como bedel y atendida monetariamente por la Alcaldía del Municipio Cajigal.</p>
			</div>

			<div class="form-container">
				<form id="loginForm" action="modulos/procesar/procesainicio.php" method="post" class="animate__animated animate__backInLeft">
					<h1>Bienvenido, por favor ingrese sus credenciales</h1>

					<?php if (isset($_GET['error'])): ?>
						<div class="alert-container">
							<p class="<?php echo ($_GET['error'] == '1' || $_GET['error'] == '2') ? 'bad' : 'error'; ?>">
								<?php
								// Mensajes de error predefinidos (previene XSS)
								$errores_login = [
									'1' => 'Credenciales inválidas',
									'2' => 'Usuario inactivo. Contacta al administrador para más información.',
									'3' => 'Usuario inactivo o credenciales inválidas.',
									'4' => 'Token de seguridad inválido. Intente de nuevo.'
								];
								$codigo_error = $_GET['error'];
								echo esc($errores_login[$codigo_error] ?? 'Error desconocido');
								?>
							</p>
						</div>
					<?php endif; ?>

					<?php echo campo_csrf(); ?>

					<div class="input-container">
						<i class="fas fa-user"></i>
						<input type="text" placeholder="Ingrese su Usuario" name="usuario" required>
					</div>

					<div class="input-container">
						<i class="fas fa-lock"></i>
						<input type="password" id="contrasena" name="contrasena" required placeholder="Ingrese su Contraseña">
						<span class="input-group-text" id="toggle-password">
							<i class="fa fa-eye" aria-hidden="true"></i>
						</span>
					</div>

					<input type="submit" value="Ingresar" class="boton-imagen">
				</form>
			</div>


			<script src="js/login.js"></script>

			<div class="footer"><?php include 'modulos/footer.php'; ?></div>

</body>

</html>