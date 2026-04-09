<?php
// Generar cursos para el nuevo año escolar (2025-2026)
$anho_anterior = '2024-2025';
$anho_nuevo = '2025-2026';

$query = "
    INSERT INTO curso (id_aula, id_grado, id_seccion, id_docente, anho)
    SELECT id_aula, id_grado, id_seccion, id_docente, ?
    FROM curso
    WHERE anho = ?
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ss", $anho_nuevo, $anho_anterior);
$stmt->execute();
$stmt->close();
?>
