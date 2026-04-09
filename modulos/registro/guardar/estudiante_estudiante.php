<?php
include '../../verificar_session.php';
include '../../conexion/conndb.php';
include '../../validacion.php';
include '../insertar.php';

$response = array();

try {
    $estudiante = $_POST['estudiante'];
    $id_estudiante = insertarEstudiante($estudiante, $conexion);
    $id_representante = $_SESSION['representante_estudiante'] ?? null;
    $id_padre_madre = $_SESSION['padre_madre_estudiante'] ?? null;

    if (!$id_representante || !$id_padre_madre) {
        throw new Exception('Datos de sesión faltantes.');
    }

    $cedula_estudiante = $_POST['estudiante']['cedula_e'] ?? $_SESSION['cedula_representante'] ?? null;

    // Procesar foto
    $foto = null;
    if (!empty($_FILES['foto']['tmp_name']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $foto_name = uniqid() . '_' . basename($_FILES['foto']['name']);
        $upload_dir = '../../uploads/estudiantes/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $foto_path = $upload_dir . $foto_name;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_path)) {
            $foto = $foto_name; // Guarda solo el nombre
        } else {
            throw new Exception('Error al mover la foto.');
        }
    }

    // Insertar en tabla familia
    $sql_familia = 'INSERT INTO familia (id_estudiante, id_representante, id_padre_madre, foto) VALUES (?, ?, ?, ?)';
    $stmt_familia = $conexion->prepare($sql_familia);
    $stmt_familia->bind_param('iiis', $id_estudiante, $id_representante, $id_padre_madre, $foto);

    if (!$stmt_familia->execute()) {
        throw new Exception('Error al registrar familia: ' . $stmt_familia->error);
    }

    $response['success'] = true;
} catch (Exception $e) {
    $response['success'] = false;
    $response['error'] = $e->getMessage();
} finally {
    if (isset($stmt_familia)) $stmt_familia->close();
    $conexion->close();
}

header('Content-Type: application/json');
echo json_encode($response);

// Redirección si fue exitoso
if ($response['success']) {
    header('Location: ../../procesar/procesare.php?status=success');
    exit();
} else {
    header('Location: ../../procesar/procesare.php?status=error');
    exit();
}
