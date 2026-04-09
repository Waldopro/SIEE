<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('../modulos/verificar_session.php');
include('../modulos/rol1.php');

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registrar_d.php");
    exit();
}

// Verificar CSRF
exigir_csrf();

// Incluir conexión
include '../modulos/conexion/conndb.php';

// Obtener y sanitizar datos
$cedula = $_POST["cedula"];
$nombres = $_POST["nombres"];
$apellidos = $_POST["apellidos"];
$gdo_ins = $_POST["gdo_ins"];

// Validar cédula duplicada con prepared statement
$consulta = "SELECT COUNT(*) AS total FROM docentes WHERE cedula = ?";
$stmt_check = $conexion->prepare($consulta);
$stmt_check->bind_param("s", $cedula);
$stmt_check->execute();
$resultado = $stmt_check->get_result();
$fila = $resultado->fetch_assoc();
$stmt_check->close();

if ($fila["total"] > 0) {
    header("Location: registrar_d.php?error=cedula_duplicada");
    exit();
}

// Insertar con prepared statement
$stmt = $conexion->prepare("INSERT INTO docentes (cedula, nombres, apellidos, gdo_ins, habilitado) 
                            VALUES (?, ?, ?, ?, ?)");
if ($stmt) {
    $habilitado = 1;
    $stmt->bind_param("sssii", $cedula, $nombres, $apellidos, $gdo_ins, $habilitado);

    if ($stmt->execute()) {
        $stmt->close();
        $conexion->close();
        header("Location: gestion_docentes.php?registro_exitoso=true");
        exit();
    } else {
        error_log("Error al registrar docente: " . $stmt->error);
        $stmt->close();
        $conexion->close();
        header("Location: registrar_d.php?error=registro");
        exit();
    }
} else {
    error_log("Error al preparar consulta docente: " . $conexion->error);
    $conexion->close();
    header("Location: registrar_d.php?error=sistema");
    exit();
}
?>
