<?php
include('../verificar_session.php');
include '../rol1.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';

$csrf_token = generar_token_csrf();
?>
<!DOCTYPE html>
<html lang="es">

<head>
<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
    <link rel="stylesheet" type="text/css" href="../../css/contraseña.css">
    <link rel="stylesheet" href="../../lib/fontawesome-free-6.4.2-web/css/all.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <title>Registro de Usuario</title>
</head>
<body oncontextmenu="return false;">
<?php include('../menu.php') ?>
<script>
        function validarContrasena() {
            var contrasena = document.getElementById('contrasena').value;
            var mensaje = document.getElementById('mensaje-contrasena');
            mensaje.innerHTML = '';

            if (contrasena.length < 5) {
                mensaje.innerHTML += 'La contraseña debe tener al menos 5 caracteres.<br>';
            }
            if (!/\d/.test(contrasena)) {
                mensaje.innerHTML += 'La contraseña debe contener al menos un número.<br>';
            }
            if (!/[!@#$%&*]/.test(contrasena)) {
                mensaje.innerHTML += 'La contraseña debe contener al menos un carácter especial (!@#$%&*).<br>';
            }

            if (mensaje.innerHTML === '') {
                return true;
            } else {
                mensaje.classList.add("error-message");
                return false;
            }
        }
    </script>
<h1 class="h1">
            <center>Registro de Usuario</center>
        </h1>
    <form method="POST" action="../procesar/procesalogin.php">
        <?php echo campo_csrf(); ?>
    <div class="contenedor-formularios">
        
        <div class="formulario">
            <label for="nom_ape">Nombre y Apellido:<span style="color: red;">*</span></label>
            <input type="text" id="nom_ape" name="nom_ape" required placeholder="Ej: Jose Angel">
                <label for="usuario">Nombre de Usuario:<span style="color: red;">*</span></label>
            <input type="text" id="usuario" name="usuario" required placeholder="Ej: FreeArt">
                <label for="gdo_ins">Grado de instrucción:<span style="color: red;">*</span></label>
            <select id="gdo_ins" name="gdo_ins" required>
                <option value="">Seleccionar</option>
                <option value="Licenciado">Licenciado</option>
                <option value="T.S.U">T.S.U</option>
                <option value="Bachiller">Bachiller</option>
            </select>

            <label for="contrasena">Contraseña:<span style="color: red;">*</span></label>
            <div class="input-group">
    <input type="password" id="contrasena" name="contrasena" required oninput="validarContrasena()">
    <span class="input-group-text" id="toggle-password">
        <i class="fa fa-eye" aria-hidden="true"></i>
    </span>
    <p id="mensaje-contrasena"></p>
</div>
                <label for="id_cargo">Rol:<span style="color: red;">*</span></label>
            <select id="id_cargo" name="id_cargo" required>
                <option value="">Seleccionar</option>
                <option value="1">Administrador</option>
                <option value="2">Usuario</option>
                <option value="3">Docente</option>
            </select>
            <button type="submit" onclick="return validarContrasena()" class="btn-primary" value="Guardar"><img src="../../img/guardar.png" alt="Guardar" style="width: 40px; height: 50px;"> Guardar</button>
            <button id="boton1" style="background-color: blue;color: #f2f2f2;padding: 10px 20px;text-decoration: none; border: none; border-radius: 20px;  cursor: pointer;" onclick="history.back()"> <img src="../../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</button>  
        </div>
    </div>
        </form>
        <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div>
    <script>
       document.getElementById("toggle-password").addEventListener("click", function(e) {
    var passwordInput = document.getElementById("contrasena");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        e.target.classList.add("fa-eye-slash");
        e.target.classList.remove("fa-eye");
    } else {
        passwordInput.type = "password";
        e.target.classList.add("fa-eye");
        e.target.classList.remove("fa-eye-slash");
    }
});
    </script>

</body>

</html>