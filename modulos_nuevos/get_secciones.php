

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../modulos/conexion/conndb.php';
$grado_id = $_POST['grado'];

if ($grado_id == 1) {
    // Preescolar tiene solo las secciones 1, 2, y 3
    $sql = "SELECT s.id, s.nombre 
            FROM secciones s 
            WHERE s.id IN (1, 2, 3)
            AND (SELECT COUNT(*) FROM aulas WHERE seccion_id = s.id AND grado_id = ?) < 25";
} else {
    // Los demás grados tienen las secciones A, B, C (4, 5, 6)
    $sql = "SELECT s.id, s.nombre 
            FROM secciones s 
            WHERE s.id IN (4, 5, 6)
            AND (SELECT COUNT(*) FROM aulas WHERE seccion_id = s.id AND grado_id = ?) < 25";
}

$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $grado_id);
$stmt->execute();
$result = $stmt->get_result();

$secciones = [];
while ($row = $result->fetch_assoc()) {
    $secciones[] = $row;
}

echo json_encode($secciones);
?>
