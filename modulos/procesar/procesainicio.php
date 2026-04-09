<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
configurar_sesion_segura();

include '../conexion/conndb.php';

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../login.php");
    exit();
}

// Verificar CSRF
if (!verificar_csrf()) {
    header("Location: ../../login.php?error=4");
    exit();
}

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Buscar usuario con prepared statement
$consulta = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $hash_almacenado = $fila['contrasena'];
    $id_cargo = $fila['id_cargo'];
    $activo = isset($fila['activo']) ? $fila['activo'] : 0;
    $tiempo_cierre_anterior = $fila['tiempo_cierre'];

    if ($activo && password_verify($contrasena, $hash_almacenado)) {
        // Regenerar ID de sesión para prevenir fixation
        session_regenerate_id(true);

        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['id_usuario'] = $fila['id'];
        $_SESSION['id_cargo'] = $id_cargo;

        // Si es docente, obtener id_docente
        if ($id_cargo == 3) {
            $id_usuariod = $fila['id'];
            $consulta_docente = "SELECT id FROM docentes WHERE id_usuariod = ?";
            $stmt_docente = $conexion->prepare($consulta_docente);
            $stmt_docente->bind_param("i", $id_usuariod);
            $stmt_docente->execute();
            $resultado_docente = $stmt_docente->get_result();

            if ($resultado_docente && $resultado_docente->num_rows > 0) {
                $fila_docente = $resultado_docente->fetch_assoc();
                $_SESSION['id_docente'] = $fila_docente['id'];
            } else {
                $_SESSION['id_docente'] = null;
            }
            $stmt_docente->close();
        }

        // Guardar última hora de conexión
        $_SESSION['tiempo_cierre'] = $tiempo_cierre_anterior;

        // Registrar historial de inicio de sesión con prepared statement
        date_default_timezone_set('America/Caracas');
        $fecha_hora_inicio = date("Y-m-d H:i:s");
        $usuario_id = $fila['id'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $sql_historial = "INSERT INTO historial_sesiones (usuario_id, fecha_hora_inicio, ip_address, user_agent) 
                          VALUES (?, ?, ?, ?)";
        $stmt_historial = $conexion->prepare($sql_historial);
        $stmt_historial->bind_param("isss", $usuario_id, $fecha_hora_inicio, $ip_address, $user_agent);
        $stmt_historial->execute();
        $stmt_historial->close();

        // Redirigir según el rol del usuario
        $_SESSION['nombre_usuario'] = $fila['nom_ape'];
        if ($id_cargo == 1) {
            header("Location: ../../inicio.php");
        } else if ($id_cargo == 2) {
            header("Location: ../../iniciousuario.php");
        } else if ($id_cargo == 3) {
            header("Location: ../../iniciodocente.php");
        }
        exit();
    } else {
        // Contraseña incorrecta o usuario inactivo
        header("Location: ../../login.php?error=3");
        exit();
    }
} else {
    // Usuario no encontrado
    header("Location: ../../login.php?error=1");
    exit();
}

$stmt->close();
$conexion->close();
?>
