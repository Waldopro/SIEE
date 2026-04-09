<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula_e'];
    $apellidos = $_POST['apellidos_e'];
    $nombres = $_POST['nombres_e'];
    // Agrega aquí los otros campos recibidos del formulario

    try {
        // Verificar el aula disponible
        $stmt = $conexion->prepare("SELECT id FROM aulas WHERE (SELECT COUNT(*) FROM estudiante WHERE aula_id = aulas.id) < 25 LIMIT 1");
        $stmt->execute();
        $aula = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aula) {
            $aula_id = $aula['id'];
        } else {
            // Crear una nueva aula
            $stmt = $conexion->prepare("INSERT INTO aulas (aula, grado_id, seccion_id) VALUES ('A' + (SELECT COUNT(*) FROM aulas) + 1, 1, 1)");
            $stmt->execute();
            $aula_id = $conexion->lastInsertId();
        }

        // Insertar el nuevo estudiante
        $stmt = $conexion->prepare("INSERT INTO estudiante (cedula_e, apellidos_e, nombres_e, aula_id) VALUES (:cedula, :apellidos, :nombres, :aula_id)");
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':aula_id', $aula_id);
        $stmt->execute();

        // Verificar si el aula se llenó
        $stmt = $conexion->prepare("SELECT COUNT(*) AS count FROM estudiante WHERE aula_id = :aula_id");
        $stmt->bindParam(':aula_id', $aula_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] >= 25) {
            echo "<script>Swal.fire('Aula Llena', 'El aula $aula_id se ha llenado y se ha creado una nueva aula.', 'info');</script>";
        } else {
            echo "<script>Swal.fire('Registro Exitoso', 'El estudiante ha sido registrado correctamente.', 'success');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
