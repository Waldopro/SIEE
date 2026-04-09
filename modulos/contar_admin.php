<?php
include 'verificar_session.php';
include 'rol1.php';
include 'conexion/conndb.php';

$consulta = "SELECT COUNT(*) AS total FROM usuarios WHERE id_cargo = 1 AND activo = 1";
$stmt = $conexion->prepare($consulta);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado) {
    $fila = $resultado->fetch_assoc();
    echo intval($fila['total']);
} else {
    echo "0";
}

$stmt->close();
$conexion->close();
?>