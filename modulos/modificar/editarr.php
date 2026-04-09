<?php
include '../conexion/conndb.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../verificar_session.php';

if (isset($_POST['update'])) {
    $id_representante = $_POST['id_representante'];
    $cedula_r = $_POST['cedula_r'];
    $apellidos = $_POST['apellidos'];
    $nombres = $_POST['nombres'];
    $edad = $_POST['edad'];
    $ocupacion = $_POST['ocupacion'];
    $tel_r = $_POST['tel_r'];
    $direccion_r = $_POST['direccion_r'];
    $parentesco = $_POST['parentesco'];
    $ingreso_mes = isset($_POST['ingreso_mes']) ? $_POST['ingreso_mes'] : '';
    $carnet_patria = $_POST['carnet_patria'];
    $ser_car = $_POST['ser_car'];
    $codigo_car = $_POST['codigo_car'];
    $entidad_ban = $_POST['entidad_ban'];
    $tipo_cta = $_POST['tipo_cta'];
    $num_cuenta = $_POST['num_cuenta'];

    $sql = "UPDATE representante SET 
        cedula_r='$cedula_r',
        apellidos='$apellidos',
        nombres='$nombres',
        edad='$edad',
        ocupacion='$ocupacion',
        tel_r='$tel_r',
        direccion_r='$direccion_r',
        parentesco='$parentesco',
        ingreso_mes='$ingreso_mes',
        carnet_patria='$carnet_patria',
        ser_car='$ser_car',
        codigo_car='$codigo_car',
        entidad_ban='$entidad_ban',
        tipo_cta='$tipo_cta',
        num_cuenta='$num_cuenta'
        
        WHERE id_representante='$id_representante'";

    if ($conexion->query($sql) === TRUE) {
        header("Location: ../procesar/procesarr.php?edicion_exitosa=true.");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}

// Obtener el valor de id_representante de la URL
if (isset($_GET['id'])) {
    $id_representante = $_GET['id'];

    $sql = "SELECT * FROM representante WHERE id_representante='$id_representante'";
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
    <title>Editar Representante</title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../css/editare.css">
    <link rel="stylesheet" type="text/css" href="../../css/registro_e.css">
    <link rel="stylesheet" href="../../css/footer.css">
</head>

<body oncontextmenu="return false;">
<?php include('../menu.php') ?>
    <h1 class="h1">
        <center>Editar Representante</center>
    </h1>
    <form method="POST" action="">
    <div class="contenedor-formularios">
			<div class="formulario">
     
    <input type="hidden" name="id_representante" value="<?php echo $id_representante; ?>" readonly>

        <label>Cédula:</label>
        <input type="number" name="cedula_r" value="<?php echo $row['cedula_r']; ?>"><br><br>
        
        <label>Apellidos:</label>
        <input type="text" name="apellidos" value="<?php echo $row['apellidos']; ?>" required><br><br>
        <label>Nombres:</label>
        <input type="text" name="nombres" value="<?php echo $row['nombres']; ?>" required><br><br>
        <label>Edad:</label>
        <input type="text" name="edad" value="<?php echo $row['edad']; ?>" required><br><br>
        <label for="ocupacion">Ocupacion:</label>
        <select name="ocupacion" required>
            <option value="Estudiante" <?php if ($row['ocupacion'] == 'Estudiante') echo 'selected'; ?>>Estudiante</option>
            <option value="Empleado" <?php if ($row['ocupacion'] == 'Empleado') echo 'selected'; ?>>Empleado</option>
            <option value="Obrero" <?php if ($row['ocupacion'] == 'Obrero') echo 'selected'; ?>>Obrero</option>
            <option value="Desempleado" <?php if ($row['ocupacion'] == 'Desempleado') echo 'selected'; ?>>Desempleado</option>
            <option value="Otro" <?php if ($row['ocupacion'] == 'Otro') echo 'selected'; ?>>Otro</option>
        </select><br><br>
       
        <label>Telefono:</label>
        <input type="Tel" name="tel_r" value="<?php echo $row['tel_r']; ?>" required><br><br>
        
        <label>Direccion:</label>
        <input type="text" name="direccion_r" value="<?php echo $row['direccion_r']; ?>" required><br><br>
        
        <label for="parentesco">Parentesco:</label>
        <select name="parentesco" required>
            <option value="Padre" <?php if ($row['parentesco'] == 'Padre') echo 'selected'; ?>>Padre</option>
            <option value="Madre" <?php if ($row['parentesco'] == 'Madre') echo 'selected'; ?>>Madre</option>
            <option value="Tio" <?php if ($row['parentesco'] == 'Tio') echo 'selected'; ?>>Tio</option>
            <option value="Hermano" <?php if ($row['parentesco'] == 'Hermano') echo 'selected'; ?>>Hermano</option>
            <option value="Otro" <?php if ($row['parentesco'] == 'Otro') echo 'selected'; ?>>Otro</option>
        </select><br><br>
        
        <label>Ingreso Mensual:</label>
        <input type="number" name="ingreso_mes" value="<?php echo $row['ingreso_mes']; ?>" required><br><br>
        
        <label>Carner de la Patria:</label>
        <select name="carnet_patria" required>
            <option value="Si" <?php if (isset($carnet_patria) && $carnet_patria == 'Si') {
                                            echo 'selected';
                                        } ?>>Si</option>
            <option value="No" <?php if (isset($carnet_patria) && $carnet_patria == 'No') {
                                            echo 'selected';
                                        } ?>>No</option>
        </select><br><br>
        
        <label>Serial del carnet:</label>
        <input type="text" name="ser_car" value="<?php echo $row['ser_car']; ?>" required><br><br>
        
        <label>Codigo del carnet:</label>
        <input type="text" name="codigo_car" value="<?php echo $row['codigo_car']; ?>" required><br><br>
        
        <label for="entidad_ban">Entidad Bancaria:</label>
        <select name="entidad_ban" required>
            <option value="Banco de Venezuela" <?php if ($row['entidad_ban'] == 'Banco de Venezuela') echo 'selected'; ?>>Banco de Venezuela</option>
            <option value="Banco Provincial" <?php if ($row['entidad_ban'] == 'Banco Provincial') echo 'selected'; ?>>Banco Provincial</option>
            <option value="Banco Banesco" <?php if ($row['entidad_ban'] == 'Banco Banesco') echo 'selected'; ?>>Banco Banesco</option>
            <option value="Banco Mercantil" <?php if ($row['entidad_ban'] == 'Banco Mercantil') echo 'selected'; ?>>Banco Mercantil</option>
            <option value="Banco Nacional de Crédito" <?php if ($row['entidad_ban'] == 'Banco Nacional de Crédito') echo 'selected'; ?>>Banco Nacional de Crédito</option>
            <option value="Banco Occidental de Descuento (BOD)" <?php if ($row['entidad_ban'] == 'Banco Occidental de Descuento (BOD)') echo 'selected'; ?>>Banco Occidental de Descuento (BOD)</option>
            <option value="Banco Exterior" <?php if ($row['entidad_ban'] == 'Banco Exterior') echo 'selected'; ?>>Banco Exterior</option>
            <option value="Banco del Tesoro" <?php if ($row['entidad_ban'] == 'Banco del Tesoro') echo 'selected'; ?>>Banco del Tesoro</option>
            <option value="Banco Fondo Común" <?php if ($row['entidad_ban'] == 'Banco Fondo Común') echo 'selected'; ?>>Banco Fondo Común</option>
            <option value="Banco Activo" <?php if ($row['entidad_ban'] == 'Banco Activo') echo 'selected'; ?>>Banco Activo</option>
            <option value="Banco Bicentenario del Pueblo" <?php if ($row['entidad_ban'] == 'Banco Bicentenario del Pueblo') echo 'selected'; ?>>Banco Bicentenario del Pueblo</option>
            <option value="Banco Sofitasa" <?php if ($row['entidad_ban'] == 'Banco Sofitasa') echo 'selected'; ?>>Banco Sofitasa</option>
            <option value="Banco Plaza" <?php if ($row['entidad_ban'] == 'Banco Plaza') echo 'selected'; ?>>Banco Plaza</option>
            <option value="Banco 100% Banco" <?php if ($row['entidad_ban'] == 'Banco 100% Banco') echo 'selected'; ?>>Banco 100% Banco</option>
            <option value="Banco Venezolano de Crédito" <?php if ($row['entidad_ban'] == 'Banco Venezolano de Crédito') echo 'selected'; ?>>Banco Venezolano de Crédito</option>
            <option value="Banco Banplus" <?php if ($row['entidad_ban'] == 'Banco Banplus') echo 'selected'; ?>>Banco Banplus</option>
            <option value="Banco BNC" <?php if ($row['entidad_ban'] == 'Banco BNC') echo 'selected'; ?>>Banco BNC</option>
            <option value="Banco Caroní" <?php if ($row['entidad_ban'] == 'Banco Caroní') echo 'selected'; ?>>Banco Caroní</option>
            <option value="Banco BFC" <?php if ($row['entidad_ban'] == 'Banco BFC') echo 'selected'; ?>>Banco BFC</option>
            <option value="Banco Bancamiga" <?php if ($row['entidad_ban'] == 'Banco Bancamiga') echo 'selected'; ?>>Banco Bancamiga</option>
            <option value="Banco Bancrecer" <?php if ($row['entidad_ban'] == 'Banco Bancrecer') echo 'selected'; ?>>Banco Bancrecer</option>
            <option value="Otro" <?php if ($row['entidad_ban'] == 'Otro') echo 'selected'; ?>>Otro</option>
        </select><br><br>
        
        <label>Tipo de cuenta:</label>
        <select name="tipo_cta" required>
            <option value="Ahorro" <?php if (isset($tipo_cta) && $tipo_cta == 'Ahorro') {
                                            echo 'selected';
                                        } ?>>Ahorro</option>
            <option value="Corriente" <?php if (isset($tipo_cta) && $tipo_cta == 'Corriente') {
                                            echo 'selected';
                                        } ?>>Corriente</option>
            <option value="No posee" <?php if (isset($tipo_cta) && $tipo_cta == 'No posee') {
                                            echo 'selected';
                                        } ?>>No posee</option>
        </select><br><br>                               

        <label>Numero de cuenta:</label>
        <input type="text" name="num_cuenta" value="<?php echo $row['num_cuenta']; ?>" required><br><br>
        
        
         
         
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
					<a href="../procesar/procesarr.php" class="back-button" style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;"> <img src="../../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</a>

                                    </div>
                                    </div>
    </form>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div> 
</body>

</html>
