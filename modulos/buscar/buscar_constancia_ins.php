<?php
include '../verificar_session.php';
include '../conexion/conndb.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';

// Obtener la cédula ingresada por el usuario
$cedula_e = $_GET['cedula_e'];

// Consultar si el estudiante está inscrito en la tabla inscripcion
$sql_inscripcion = "SELECT i.id, i.id_curso, e.nombres_e, e.apellidos_e, e.cedula_e, e.edad_e, c.anho, c.id_grado, c.id_seccion 
                    FROM inscripcion i
                    INNER JOIN estudiante e ON i.id_estudiante = e.id_estudiante
                    INNER JOIN curso c ON i.id_curso = c.id
                    WHERE e.cedula_e = '$cedula_e'";

$result = mysqli_query($conexion, $sql_inscripcion);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Verificar si se encontraron resultados
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    
    // Obtener los datos del grado y sección a partir del curso
    $grado_id = $row['id_grado'];
    $seccion_id = $row['id_seccion'];
    $anho = $row['anho'];

    // Consultar el nombre del grado
    $sql_grado = "SELECT nombre FROM grados WHERE id = '$grado_id'";
    $result_grado = mysqli_query($conexion, $sql_grado);

    if (!$result_grado) {
        die("Error en la consulta del grado: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($result_grado) > 0) {
        $row_grado = mysqli_fetch_assoc($result_grado);
        $current_grado = $row_grado['nombre'];
    } else {
        $current_grado = "Grado no encontrado";
    }

    // Consultar el nombre de la sección
    $sql_seccion = "SELECT nombre FROM secciones WHERE id = '$seccion_id'";
    $result_seccion = mysqli_query($conexion, $sql_seccion);

    if (!$result_seccion) {
        die("Error en la consulta de la sección: " . mysqli_error($conexion));
    }

    if (mysqli_num_rows($result_seccion) > 0) {
        $row_seccion = mysqli_fetch_assoc($result_seccion);
        $current_seccion = $row_seccion['nombre'];
    } else {
        $current_seccion = "Sección no encontrada";
    }

} else {
    echo "No se encontraron resultados para la cédula ingresada o el estudiante no está inscrito.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Inscripción</title>
    <link rel="stylesheet" href="../../css/constancia_e.css">
    <link rel="stylesheet" href="../../css/footer_e.css">
</head>
<body oncontextmenu="return false;">
    <div class="content">
        <img src="../../img/cintillo.jpg" alt="Cabecera de la página" class="header-image">
        <center>
            <h3>Republica Bolivariana de Venezuela<br>
                Ministerio del Poder Popular para la Educacion<br>
                U.E Bolivariana "Eneas Morantes"<br>
                Yaguaraparo Municipio Cajigal Estado Sucre</h3>
        </center>
        <h2>
            <center>Constancia de Inscripción</center>
        </h2>
        <p class="co" style="text-align: center; margin-left: 2.45mm; margin-right: 2.45mm;">
            Quien suscribe, Directora de la Unidad Educativa Bolivariana “Eneas Morante”,
            Código del Plantel Nº 17-006590284, que funciona en la comunidad de la La Chivera, Parroquia Yaguaraparo,
            Municipio Cajigal Estado Sucre, por medio de la presente hace constar que el (la) alumno (a):
            <?php echo $row['nombres_e']; ?> <?php echo $row['apellidos_e']; ?> titular de la Cédula Nº <?php echo $row['cedula_e']; ?>
            de <?php echo $row['edad_e']; ?> años de edad, ha sido inscrito (a) para cursar el grado <?php echo $current_grado; ?> 
            en la sección <?php echo $current_seccion; ?> durante el Año Escolar <?php echo $anho; ?>.
            Constancia que se expide a petición de la parte interesada en Yaguaraparo,
            a los <?php echo date('d'); ?> días del mes <?php echo date('m'); ?> de <?php echo date('Y'); ?>.
        </p><br><br>
        <center>
            <p class="co">
                <center>Atentamente,<br>
                    Olidys Alfonzo<br>
                    Directora</center>
            </p>
        </center>
    </div>

    <div class="button-container">
    <button class="pdf" type="button" onclick="generarPDF('<?php echo $cedula_e; ?>')">
    <img src="../../img/PDF_file_icon.svg.png" alt="PDF" style="width: 30px; height: 40px;">  Generar PDF
</button>
        <button class="volver" onclick="volver()"><img src="../../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</button>
    </div>
    <script>
        function generarPDF(cedula_e) {
            window.location.href = "../pdf/pdf_cons.php?cedula_e=" + encodeURIComponent(cedula_e);
        }

        function volver() {
            window.history.back();
        }
    </script>
    <div class="footer">
        <?php include '../footer.php'; ?>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conexion);
?>
