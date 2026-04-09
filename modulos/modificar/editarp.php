<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../conexion/conndb.php';
// Verificar si el usuario ha iniciado sesión y si su rol es de "usuario"
include '../verificar_session.php';

if (isset($_POST['update'])) {
    $id_padre_madre = $_POST['id_padre_madre'];
    $cedula_p = $_POST['cedula_p'];
    $apellidos_p = $_POST['apellidos_p'];
    $nombres_p = $_POST['nombres_p'];
    $edad = $_POST['edad'];
    $ocupacion_p = $_POST['ocupacion_p'];
    $tel_p = $_POST['tel_p'];
    $direccion_p = $_POST['direccion_p'];

    $sql = "UPDATE padre_madre SET 
    cedula_p='$cedula_p',
    apellidos_p='$apellidos_p',
    nombres_p='$nombres_p',
    edad='$edad',
    ocupacion_p='$ocupacion_p',
    tel_p='$tel_p',
    direccion_p='$direccion_p'
        
    WHERE id_padre_madre='$id_padre_madre'";

    if ($conexion->query($sql) === TRUE) {
        header("Location: ../procesar/procesarp.php?edicion_exitosa=true.");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}

// Obtener el valor de id_padre_madre de la URL
if (isset($_GET['id'])) {
    $id_padre_madre = $_GET['id'];

    $sql = "SELECT * FROM padre_madre WHERE id_padre_madre='$id_padre_madre'";
    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();
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
    <title>Editar Padre/Madre</title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/editare.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
</head>

<body oncontextmenu="return false;">
<?php include('../menu.php') ?>
    <h1 class="h1">
        <center>Editar Padre/Madre</center>
    </h1>
    <form method="POST" action="">
    <div class="contenedor-formularios">
			<div class="formulario">
        <input type="hidden" name="id_padre_madre" value="<?php echo $id_padre_madre; ?>" readonly>

        <label>Cédula:</label>
        <input type="text" name="cedula_p" maxlength="10" value="<?php echo $row['cedula_p']; ?>" required><br><br>


        <label>Apellidos:</label>
        <input type="text" name="apellidos_p" value="<?php echo $row['apellidos_p']; ?>" required><br><br>

        <label>Nombres:</label>
        <input type="text" name="nombres_p" value="<?php echo $row['nombres_p']; ?>" required><br><br>

        <label>Edad:</label>
        <input id="edad" name="edad" type="number" inputmode="numeric" pattern="\d{1,2}" maxlength="2" value="<?php echo $row['edad']; ?>" required><br><br>


        <label for="ocupacion_pp">Ocupación:</label>
        <select id="ocupacion_p" name="ocupacion_p">
            <option value="Estudiante" <?php if (isset($row['ocupacion_p']) && $row['ocupacion_p'] == "Estudiante") {
                                            echo "selected";
                                        } ?>>Estudiante</option>
            <option value="Empleado" <?php if (isset($row['ocupacion_p']) && $row['ocupacion_p'] == "Empleado") {
                                            echo "selected";
                                        } ?>>Empleado</option>
            <option value="Obrero" <?php if (isset($row['ocupacion_p']) && $row['ocupacion_p'] == "Obrero") {
                                        echo "selected";
                                    } ?>>Obrero</option>
            <option value="Desempleado" <?php if (isset($row['ocupacion_p']) && $row['ocupacion_p'] == "Desempleado") {
                                            echo "selected";
                                        } ?>>Desempleado</option>
            <option value="Otro" <?php if (isset($row['ocupacion_p']) && $row['ocupacion_p'] == "Otro") {
                                        echo "selected";
                                    } ?>>Otro</option>
        </select>

        <label>Teléfono:</label>
        <input id="tel_p" name="tel_p" maxlength="11" type="tel" value="<?php echo $row['tel_p']; ?>" required>

        <label>Direccion:</label>
        <input type="text" name="direccion_p" value="<?php echo $row['direccion_p']; ?>" required><br><br>

        <input class="btn btn-primary btn-actualizar" type='submit' name="update" value="Actualizar">
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
					<a href="../procesar/procesarp.php" class="back-button" style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;"> <img src="../../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</a>

                                </div>
                                </div>
    </form>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div> 
</body>

</html>