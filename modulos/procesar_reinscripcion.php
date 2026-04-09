<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];

    // Verificar si el estudiante existe y es apto
    $sql = "SELECT * FROM estudiante WHERE cedula = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $cedula);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $estudiante = $resultado->fetch_assoc();
        // Mostrar cursos disponibles y permitir reinscripción
    } else {
        echo "<p>Estudiante no encontrado o no apto para reinscripción.</p>";
    }
}
?>
