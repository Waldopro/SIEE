<?php
// login.php

session_start();

// Destruir todas las variables de sesión
session_destroy();

session_start();

// Restablecer las variables de sesión que necesites
$_SESSION['id_cargo'] = '';

?>


<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inicio de Sesión</title>
	<link rel="stylesheet" href="lib/fontawesome-free-6.4.2-web/css/all.css">
	<link rel="stylesheet" href="css/cdnjs.cloudflare.com_ajax_libs_animate.css_4.0.0_animate.min.css">
	<link rel="stylesheet" href="css/cdnjs.cloudflare.com_ajax_libs_font-awesome_5.15.3_css_all.min.css">
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/footer_e.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Sección de Bienvenida -->
            <div class="col-md-6 text-center">
                <h1 class="mb-4 text-primary">Bienvenido al Sistema de Inscripción</h1>
                <p class="text-muted">La Unidad Educativa Bolivariana “Eneas Morante” inicia sus actividades...</p>
            </div>

            <!-- Formulario de Inicio de Sesión -->
            <div class="col-md-5">
                <div class="card shadow-lg p-4 animate__animated animate__backInLeft">
                    <h2 class="text-center text-primary">Iniciar Sesión</h2>

                    <?php if (isset($_GET['error'])) { ?>
                        <div class="alert alert-danger text-center">
                            <?php echo $_GET['error']; ?>
                        </div>
                    <?php } ?>

                    <form id="loginForm" action="modulos/procesar/procesainicio.php" method="post">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingrese su usuario">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                                <span class="input-group-text" id="toggle-password"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>

						<input type="submit" value="Ingresar" class="boton-imagen">                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/login.js"></script>
    <div class="footer text-center mt-4"><?php include 'modulos/footer.php'; ?></div>
</body>




<style>
    .boton-imagen {
    background-image: url('img/ingresar1.png');
    background-size: contain;
    background-repeat: no-repeat;
    padding-left: 50px; /* Ajusta el espacio según el tamaño de la imagen */
    text-align: center;
  }
</style>
				</form>
			</div>


			<script src="js/login.js"></script>

			<div class="footer"><?php include 'modulos/footer.php'; ?></div>
	</body>

</html>