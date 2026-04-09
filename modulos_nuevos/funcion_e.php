<?php
require '../modulos/conexion/conndb.php';

function obtenerEstadisticas($curso_id = null) {
    global $conexion;

    // Base de la consulta SQL
    $sql = "
        SELECT 
            c.anho AS año,
            g.nombre AS grado,
            COUNT(i.id_estudiante) AS total_estudiantes,
            SUM(CASE WHEN e.sexo = 'Masculino' THEN 1 ELSE 0 END) AS total_masculinos,
            SUM(CASE WHEN e.sexo = 'Femenino' THEN 1 ELSE 0 END) AS total_femeninos,
            SUM(CASE WHEN i.id_tipo_ins = 1 THEN 1 ELSE 0 END) AS total_nuevos,
            SUM(CASE WHEN i.id_tipo_ins = 2 THEN 1 ELSE 0 END) AS total_regulares,
            SUM(CASE WHEN i.id_tipo_ins = 3 THEN 1 ELSE 0 END) AS total_repitientes
        FROM curso c
        LEFT JOIN inscripcion i ON c.id = i.id_curso
        LEFT JOIN estudiante e ON i.id_estudiante = e.id_estudiante
        LEFT JOIN grados g ON c.id_grado = g.id
        WHERE 1";

    // Si se pasa un curso_id, agregar el filtro con una consulta preparada
    if ($curso_id !== null && $curso_id !== 'todos') {
        $sql .= " AND c.id = ?";
    }

    $sql .= " GROUP BY c.anho, g.nombre";

    
    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, $sql);
    if ($curso_id !== null && $curso_id !== 'todos') {
        mysqli_stmt_bind_param($stmt, "i", $curso_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $estadisticas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $estadisticas[] = $row;
    }

    return $estadisticas;
}

function obtenerCursos() {
    global $conexion;

    $sql = "SELECT c.id, g.nombre AS grado, s.nombre AS seccion 
            FROM curso c
            JOIN grados g ON c.id_grado = g.id
            JOIN secciones s ON c.id_seccion = s.id
           ORDER BY
    CASE 
        WHEN g.nombre = 'Preescolar' THEN 1
        WHEN g.nombre = '1ro' THEN 2
        WHEN g.nombre = '2do' THEN 3
        WHEN g.nombre = '3ro' THEN 4
        WHEN g.nombre = '4to' THEN 5
        WHEN g.nombre = '5to' THEN 6
        WHEN g.nombre = '6to' THEN 7
        ELSE 999  
    END,
    s.nombre";

    $result = mysqli_query($conexion, $sql);
    $cursos = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $cursos;
}

// Si la solicitud es POST y se recibe curso_id, obtener estadísticas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['curso_id'])) {
    $curso_id = $_POST['curso_id'];
    $estadisticas = obtenerEstadisticas($curso_id);
    echo json_encode($estadisticas);
}

?>
