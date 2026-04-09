<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include 'conexion/conndb.php';
include 'verificar_session.php';
include 'rol1.php';

$usuario_id = $_SESSION['id_usuario'];

// Recoger los filtros de fecha y rol
$fecha_filtro = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$rol_filtro = isset($_GET['rol']) ? $_GET['rol'] : '';

// Establecer la zona horaria
date_default_timezone_set('America/Caracas');

// Construir consulta SQL con filtros usando prepared statements
$sql = "SELECT 
            hs.usuario_id,
            DATE_FORMAT(hs.fecha_hora_inicio, '%Y-%m-%d %r') AS fecha_hora_inicio, 
            hs.ip_address, 
            hs.user_agent,  
            u.usuario,
            u.id_cargo
        FROM historial_sesiones hs
        JOIN usuarios u ON hs.usuario_id = u.id";

$params = [];
$types = "";
$whereClauses = [];

if (!empty($fecha_filtro)) {
    $whereClauses[] = "DATE(hs.fecha_hora_inicio) = ?";
    $params[] = $fecha_filtro;
    $types .= "s";
}

if (!empty($rol_filtro)) {
    $whereClauses[] = "u.id_cargo = ?";
    $params[] = $rol_filtro;
    $types .= "s";
}

if (count($whereClauses) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

$sql .= " ORDER BY hs.fecha_hora_inicio DESC";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Inicio de Sesión</title>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesarr.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../css/footer_e.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../lib/datatable/dataTables.bootstrap.min.css">
    <script src="../lib/datatable/datatables.min.js"></script>
    <script src="../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../lib/datatable/jquery.dataTables.min.js"></script>
</head>
<body oncontextmenu="return false;">   
    <?php include('menu.php'); ?>
    <div class="content">
        <center><h2>Historial de Inicio de Sesión</h2></center>
        <div class="filters">
            <form method="get" action="">
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" value="<?php echo esc($fecha_filtro); ?>">

                <label for="rol">Rol:</label>
                <select name="rol" id="rol">
                    <option value="">Seleccione Rol</option>
                    <option value="1" <?php echo ($rol_filtro == '1') ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo ($rol_filtro == '2') ? 'selected' : ''; ?>>Usuario</option>
                    <option value="3" <?php echo ($rol_filtro == '3') ? 'selected' : ''; ?>>Docente</option>
                </select>

                <button type="submit">Filtrar</button>
            </form>
        </div>
        <table id="tabla" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>ID de Usuario</th>
                    <th>Nombre de Usuario</th>
                    <th>Rol</th>
                    <th>Fecha y Hora</th>
                    <th>Dirección IP</th>
                    <th>Navegador/Dispositivo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        $rol = $fila['id_cargo'] == 1 ? 'Administrador' : ($fila['id_cargo'] == 2 ? 'Usuario' : 'Docente');
                        echo "<tr>
                        <td>" . esc($fila['usuario_id']) . "</td>
                        <td>" . esc($fila['usuario']) . "</td>
                        <td>" . esc($rol) . "</td>
                        <td>" . esc($fila['fecha_hora_inicio']) . "</td>
                        <td>" . esc($fila['ip_address']) . "</td>
                        <td>" . esc($fila['user_agent']) . "</td>
                      </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay registros de inicio de sesión.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <?php include 'footer.php'; ?>
    </div>
    <script>
    $(document).ready(function() {
        $('#tabla').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "lengthChange": false,
            "pageLength": 7,
            "language": {
                "url": "../lib/Spanish.json"
            }
        });
    });
    </script>
</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
