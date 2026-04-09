<?php
include '../../verificar_session.php';
include('../../conexion/conndb.php');
include('../../validacion.php');
include('../insertar.php');

// Suponiendo que has añadido la columna foto a la tabla familia
$estudiante = $_POST['estudiante'];
$id_estudiante = insertarEstudiante($estudiante, $conexion);
$id_representante = $_SESSION['representante_estudiante'];
$id_padre_madre = $_SESSION['padre_madre_estudiante'];

if (isset($_POST['no_cedula'])) {
    $cedula_estudiante = $_SESSION['cedula_representante'];
} else {
    $cedula_estudiante = $_POST['estudiante']['cedula_e'];
}

// Procesar el archivo de imagen si se ha subido una foto
$foto = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $foto_tmp_name = $_FILES['foto']['tmp_name'];
    $foto_ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_name = uniqid('estudiante_', true) . '.' . $foto_ext; // Nombre único para evitar conflictos
    $upload_dir = '../../uploads/estudiantes/';

    // Verifica si la carpeta de destino existe
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $foto_path = $upload_dir . $foto_name;

    // Verifica si el archivo temporal existe y lo mueve
    if (file_exists($foto_tmp_name)) {
        if (move_uploaded_file($foto_tmp_name, $foto_path)) {
            $foto = 'uploads/estudiantes/' . $foto_name; // Guardar la ruta relativa
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        echo "El archivo temporal no existe.";
    }
} else {
    echo "Error al subir la foto: " . ($_FILES['foto']['error'] ?? "No se ha subido ninguna foto.");
}

$sql_familia = 'INSERT INTO familia (id_estudiante, id_representante, id_padre_madre, foto) VALUES (?, ?, ?, ?)';
$stmt_familia = $conexion->prepare($sql_familia);
$stmt_familia->bind_param('iiis', $id_estudiante, $id_representante, $id_padre_madre, $foto);

$response = array();

if ($stmt_familia->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = 'Error al registrar familia: ' . $stmt_familia->error;
}

$stmt_familia->close();
$conexion->close();

header('Content-Type: application/json');
echo json_encode($response);

// Redirigir a procesare.php si todo fue exitoso
if ($response['success']) {
    header('Location: ../../procesar/procesare.php');
    exit();
}
?>
