<?php
include '../conexion/conndb.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('../verificar_session.php');
include '../rol1.php';

// Verificar si se ha enviado el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores enviados en el formulario
    $id = $_POST['id'];
    $nom_ape = $_POST['nom_ape'];
    $usuario = $_POST['usuario'];
    $gdo_ins = $_POST['gdo_ins'];
    $id_cargo = $_POST['id_cargo'];

    // Verificar si se ha enviado una nueva contraseña en el formulario
    if (!empty($_POST['contrasena'])) {
        $contrasena = $_POST['contrasena'];

        // Hashear la nueva contraseña utilizando password_hash()
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Actualizar la contraseña hasheada en la base de datos
        $sql = "UPDATE usuarios SET 
        nom_ape='$nom_ape',
        usuario='$usuario',
        gdo_ins='$gdo_ins',
        contrasena='$hash',
        id_cargo='$id_cargo'
        WHERE id='$id'";
    } else {
        // Si no se ha enviado una nueva contraseña, actualizar los demás campos sin afectar la contraseña en la base de datos
        $sql = "UPDATE usuarios SET 
        nom_ape='$nom_ape',
        usuario='$usuario',
        gdo_ins='$gdo_ins',
        id_cargo='$id_cargo'
        WHERE id='$id'";
    }

    if ($conexion->query($sql) === TRUE) {
        header("Location: ../procesar/procesalogin.php?edicion_exitosa=true");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}

// Obtener el valor de id_representante de la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM usuarios WHERE id='$id'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No se encontró ningún usuario con el ID proporcionado";
        exit();
    }

    $conexion->close();
} else {
    echo "No se recibió el parámetro 'id' en la URL";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/editare.css">
    <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../../css/contraseña.css">
    <link rel="stylesheet" href="../../lib/fontawesome-free-6.4.2-web/css/all.css">
</head>

<body oncontextmenu="return false;">
<?php include('../menu.php') ?>
    <h1 class="h1">
        <center>Editar usuario</center>
    </h1>
        
    <form method="POST" action="">
    <div class="contenedor-formularios">
			<div class="formulario">
    <input type="hidden" name="id" value="<?php echo $id; ?>" readonly>

        <label>Nombre y Apellido:</label>
        <input type="text" name="nom_ape" value="<?php echo $row['nom_ape']; ?>"><br><br>
        
        <label>Usuario:</label>
        <input type="text" name="usuario" value="<?php echo $row['usuario']; ?>" required><br><br>

         <label for="usuario">Grado de instrucción:</label>
            <select id="gdo_ins" name="gdo_ins" required>
                <option value="Licenciado" <?php if ($row['gdo_ins'] == 'Licenciado') echo ' selected';?>>Licenciado</option>
                <option value="T.S.U" <?php if ($row['gdo_ins']== 'T.S.U') echo ' selected'; ?>>T.S.U</option>
                <option value="Bachiller" <?php if ($row['gdo_ins']== 'Bachiller') echo ' selected'; ?>>Bachiller</option>
            </select><br><br>
        
        <label for="contrasena">Contraseña:</label>
            <div class="input-group">
    <input type="password" id="contrasena" name="contrasena" required oninput="validarContrasena()" value="<?php echo $row['contrasena']; ?>">
    <span class="input-group-text" id="toggle-password">
        <i class="fa fa-eye" aria-hidden="true"></i>
    </span>
    <p id="mensaje-contrasena"></p>
</div>
        <label>Rol:</label>
        <select name="id_cargo" required>
            <option value="1" <?php if (isset($id_cargo) && $id_cargo == '1') {
                                            echo 'selected';
                                        } ?>>Administrador</option>
            <option value="2" <?php if (isset($id_cargo) && $id_cargo == '2') {
                                            echo 'selected';
                                        } ?>>Usuario</option>
            <option value="3" <?php if (isset($id_cargo) && $id_cargo == '3') {
                                            echo 'selected';
                                        } ?>>Docente</option>
        </select><br><br>
        <div class="btn-group">
                <input class="btn btn-primary btn-actualizar" type="submit" name="update" value="Actualizar">
                <style>
  .btn-actualizar {
    background-image: url('../../img/actualizar.png');
    background-repeat: no-repeat;
    background-position: left center;
    background-size: 40px 50px; /* Ajusta el tamaño de la imagen */
    padding-left: 30px; /* Espacio para el texto */
    height: 40px;
  }
</style>
                <a href="../procesar/procesalogin.php" class="back-button"> <img src="../../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</a>
            </div>
        </div>
                                </div>

        
        
        </form>
    </form>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div> 
<script src="../../js/sweetaler.min.js"></script>
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
        function mostrarConfirmacion(event) {
            event.preventDefault(); // Evitar enviar el formulario de inmediato

            // Obtener los valores de los campos requeridos
            var usuario = document.getElementById('usuario').value;
            var contraseña = document.getElementById('contrasena').value;

            // Verificar si los campos requeridos están llenos
            if (usuario.trim() === '' || contraseña.trim() === '') {
                alert('Por favor, complete todos los campos requeridos.');
            } else {
                if (confirm("Registro realizado con éxito. ¿Desea continuar?")) {
                    // Continuar si se hace clic en Aceptar
                    document.forms[0].submit(); // Enviar el formulario
                } else {
                    // Permanecer en la página si se hace clic en Cancelar
                }
            }
        }
    </script>
</body>
</html>
