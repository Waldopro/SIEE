<?php
require '../modulos/conexion/conndb.php';

// Iniciar proceso de actualización del año escolar
$anho_actual = date('Y');
$anho_anterior = ($anho_actual - 1) . '-' . $anho_actual;  // Año escolar anterior
$anho_nuevo = $anho_actual . '-' . ($anho_actual + 1);  // Año escolar nuevo

// Generar cursos para el nuevo año escolar (evitar duplicados)
$query = "
    INSERT INTO curso (id_aula, id_grado, id_seccion, id_docente, anho, capacidad)
    SELECT id_aula, id_grado, id_seccion, id_docente, ?, capacidad
    FROM curso c1
    WHERE anho = ?
    AND NOT EXISTS (
        SELECT 1
        FROM curso c2
        WHERE c2.id_aula = c1.id_aula
        AND c2.id_grado = c1.id_grado
        AND c2.id_seccion = c1.id_seccion
        AND c2.id_docente = c1.id_docente
        AND c2.anho = ?
    )
";
$stmt = $conexion->prepare($query);
$stmt->bind_param("sss", $anho_nuevo, $anho_anterior, $anho_nuevo);
$stmt->execute();
$stmt->close();

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
    WHERE e.calificacion IN ('A', 'B', 'C') AND c.anho = ? AND EXISTS (
        SELECT 1
        FROM curso
        WHERE id_grado = c.id_grado + 1
        AND id_seccion = c.id_seccion
        AND anho = ?
        LIMIT 1
    )
";
$stmt = $conexion->prepare($query_avanzar);
$stmt->bind_param("sss", $anho_nuevo, $anho_anterior, $anho_nuevo);
$stmt->execute();
$stmt->close();

// Mantener estudiantes en el mismo grado si reprueban
$query_repetir = "
    UPDATE inscripcion AS i
    JOIN evaluaciones AS e ON i.id_estudiante = e.id_estudiante
    JOIN curso AS c ON i.id_curso = c.id
    SET i.id_curso = (
        SELECT id
        FROM curso
        WHERE id_grado = c.id_grado
        AND id_seccion = c.id_seccion
        AND anho = ?
        LIMIT 1
    )
    WHERE e.calificacion IN ('D', 'E') AND c.anho = ? AND EXISTS (
        SELECT 1
        FROM curso
        WHERE id_grado = c.id_grado
        AND id_seccion = c.id_seccion
        AND anho = ?
        LIMIT 1
    )
";
$stmt = $conexion->prepare($query_repetir);
$stmt->bind_param("sss", $anho_nuevo, $anho_anterior, $anho_nuevo);
$stmt->execute();
$stmt->close();
?>
