<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include("../modulos/verificar_session.php");
include("../modulos/conexion/conndb.php");

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

if (isset($_POST['id_estudiante']) && isset($_POST['calificacion']) && isset($_POST['id_curso']) && isset($_POST['id_lapso'])) {
    $id_estudiante = $_POST['id_estudiante'];
    $calificacion = $_POST['calificacion'];
    $id_curso = $_POST['id_curso'];
    $id_lapso = $_POST['id_lapso'];
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : '';

    // Validar que la calificación esté dentro de las opciones permitidas
    $calificaciones_validas = ['A', 'B', 'C', 'D', 'E'];
    if (in_array($calificacion, $calificaciones_validas)) {
        $query = "INSERT INTO evaluaciones (id_estudiante, id_curso, id_lapso, calificacion, observaciones)
                  VALUES (?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE calificacion = ?, observaciones = ?";
        $stmt = $conexion->prepare($query);
        if ($stmt) {
            $stmt->bind_param('iiissss', $id_estudiante, $id_curso, $id_lapso, $calificacion, $observaciones, $calificacion, $observaciones);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar la evaluación']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en la consulta SQL']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Calificación no válida']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
}

$conexion->close();
?>
