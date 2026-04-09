<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../modulos/conexion/conndb.php';
// Verificar si el usuario ha iniciado sesión y si su rol es de "usuario"
include '../modulos/verificar_session.php';

// Obtener el valor de id de la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar los datos del docente
    $sql_docente = "SELECT * FROM docentes WHERE id='$id'";
    $result_docente = $conexion->query($sql_docente);

    if ($result_docente->num_rows > 0) {
        $docente = $result_docente->fetch_assoc();
    } else {
        die("Docente no encontrado.");
    } }

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $cedula = $_POST['cedula'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $gdo_ins = $_POST['gdo_ins'];

    $sql = "UPDATE docentes SET 
    cedula='$cedula',
    nombres='$nombres',
    apellidos='$apellidos',
    gdo_ins='$gdo_ins'
    WHERE id='$id'";

    if ($conexion->query($sql) === TRUE) {
        header("Location: gestion_docentes.php?edicion_exitosa=true");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../css/registro_e.css">
    <link rel="stylesheet" type="text/css" href="../css/contraseña.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" href="../lib/fontawesome-free-6.4.2-web/css/all.css">
    <title>Editar Docente</title>
</head>
<body oncontextmenu="return false;">
<?php include('../modulos/menu.php') ?>

<h1 class="h1">
            <center>Editar Docente</center>
        </h1>
    <form method="POST" action="editard.php?id=<?php echo $id; ?>">
    <div class="contenedor-formularios">
        
        <div class="formulario"> 
            <input type="hidden" name="id" value="<?php echo $docente['id']; ?>">
            <label for="cedula">Cedula:</label>
            <input type="number" name="cedula" id="cedula" required value="<?php echo $docente['cedula']; ?>" placeholder="14105489">
            <label for="nombres">Nombres:</label>
            <input type="text" id="nombres" name="nombres" required value="<?php echo $docente['nombres']; ?>" placeholder="Ej: Jose">
                <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" required value="<?php echo $docente['apellidos']; ?>" placeholder="Ej: Granado">
                <label for="gdo_ins">Grado de instrucción:</label>
            <select id="gdo_ins" name="gdo_ins" required>
                <option value="">Seleccionar</option>
                <option value="grado 1" <?php if ($docente['gdo_ins'] == "grado 1") echo 'selected'; ?>>Grado 1</option>
                <option value="grado 2" <?php if ($docente['gdo_ins'] == "grado 2") echo 'selected'; ?>>Grado 2</option>
                <option value="grado 3" <?php if ($docente['gdo_ins'] == "grado 3") echo 'selected'; ?>>Grado 3</option>
                <option value="grado 4" <?php if ($docente['gdo_ins'] == "grado 4") echo 'selected'; ?>>Grado 4</option>
                <option value="grado 5" <?php if ($docente['gdo_ins'] == "grado 5") echo 'selected'; ?>>Grado 5</option>
                <option value="grado 6" <?php if ($docente['gdo_ins'] == "grado 6") echo 'selected'; ?>>Grado 6</option>
                <option value="grado 7" <?php if ($docente['gdo_ins'] == "grado 7") echo 'selected'; ?>>Grado 7</option>
            </select>
            
            <input class="btn btn-primary btn-actualizar" type='submit' name="update" value="Actualizar">
            <style>
  .btn-actualizar {
    background-image: url('../img/actualizar.png');
    background-repeat: no-repeat;
    background-position: left center;
    background-size: 30px 40px; /* Ajusta el tamaño de la imagen */
    padding-left: 30px; /* Espacio para el texto */
    height: 40px;
  }
</style>
           <button id="back-button" style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="back()" class="btn-back">
            <img src="../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;"> Volver</button>  
        </div>
    </div>
    </form>
    <div class="footer">
        <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
    </div>
    <script>function back() {
    window.history.back(); // Regresa a la página anterior
}
</script>
</body>
</html>