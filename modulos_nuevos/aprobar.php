<?php
// Avanzar estudiantes al siguiente grado
$query_avanzar = "
    UPDATE inscripcion AS i
    JOIN evaluaciones AS e ON i.id_estudiante = e.id_estudiante
    JOIN curso AS c ON i.id_curso = c.id
    SET i.id_curso = (
        SELECT id
        FROM curso
        WHERE id_grado = c.id_grado + 1
        AND id_seccion = c.id_seccion
        AND anho = ?
        LIMIT 1
    )
    WHERE e.calificacion IN ('A', 'B', 'C') AND c.anho = ?";
$stmt = $mysqli->prepare($query_avanzar);
$stmt->bind_param("ss", $anho_nuevo, $anho_anterior);
$stmt->execute();
$stmt->close();
?>
