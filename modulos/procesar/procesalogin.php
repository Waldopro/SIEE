<?php
// Verificar si el usuario ha iniciado sesión y si su rol es de "administrador"
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('../verificar_session.php');
include '../rol1.php';
// Conectar a la base de datos
include '../conexion/conndb.php';

// Verificar si se ha enviado el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom_ape'])) {
    // Verificar CSRF
    exigir_csrf();

    // Obtener los valores del formulario
    $nom_ape = $_POST['nom_ape'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $id_cargo = $_POST['id_cargo'];
    $gdo_ins = $_POST['gdo_ins'];

    // Hashear la contraseña
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Verificar si el usuario ya existe con prepared statement
    $consulta_existencia = "SELECT id FROM usuarios WHERE usuario = ?";
    $stmt_check = $conexion->prepare($consulta_existencia);
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $resultado_existencia = $stmt_check->get_result();

    if ($resultado_existencia->num_rows > 0) {
        $stmt_check->close();
        echo '<script>alert("El usuario ya existe. Por favor, intente con otro.");</script>';
    } else {
        $stmt_check->close();
        date_default_timezone_set('America/Caracas');
        $tiempo_inicio = date("Y-m-d H:i:s");
        $tiempo_cierre = date("Y-m-d H:i:s");

        // Insertar con prepared statement
        $consulta = "INSERT INTO usuarios (nom_ape, usuario, gdo_ins, contrasena, id_cargo, tiempo_inicio, tiempo_cierre) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($consulta);
        $stmt_insert->bind_param("sssssss", $nom_ape, $usuario, $gdo_ins, $hash, $id_cargo, $tiempo_inicio, $tiempo_cierre);

        if ($stmt_insert->execute()) {
            $stmt_insert->close();
            header('Location: procesalogin.php?registro_exitoso=true');
            exit;
        } else {
            error_log("Error al registrar usuario: " . $stmt_insert->error);
            $stmt_insert->close();
            echo '<script>alert("Error al registrar el usuario.");</script>';
        }
    }
}

// Realizar la consulta para obtener los usuarios registrados
$consulta = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Generar token CSRF
$csrf_token = generar_token_csrf();
?>


<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../../lib/datatable/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../../lib/datatable/datatables.min.js"></script>
    <script src="../../lib/datatable/dataTables.bootstrap.min.js"></script>
    <script src="../../lib/datatable/jquery.dataTables.min.js"></script>

    <title>Lista de Usuarios</title>

</head>

<body oncontextmenu="return false;">

    <?php include('../menu.php') ?>

    <div class="content">
        <center>
            <h2>Lista de Usuarios</h2>
        </center>

        <button style="display: inline-block; background-color: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;" onclick="history.back()"><img src="../../img/volver1.png" alt="Volver" style="width: 30px; height: 40px;">Volver</button>
        <table id="tabla" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre y Apellido</th>
                    <th>Nombre de Usuario</th>
                    <th>Grado de instrucción</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>fecha de conexión</th>
                    <th>ultima conexión</th>
                    <th>Actv/Des</th>
                    <th class="opciones">Opciones Root</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <td><?php echo esc($fila['id']); ?></td>
                        <td><?php echo esc($fila['nom_ape']); ?></td>
                        <td><?php echo esc($fila['usuario']); ?></td>
                        <td><?php echo esc($fila['gdo_ins']); ?></td>
                        <td><?= $fila['id_cargo'] == 1 ? 'Administrador' : ($fila['id_cargo'] == 2 ? 'Usuario' : 'Docente') ?></td>

                        <td><?php echo (isset($fila['activo']) && $fila['activo']) ? '<span style="color: green;">Activo</span>' : '<span style="color: red;">Inactivo</span>'; ?></td>

                        <td><?php echo esc(date('Y-m-d H:i:s', strtotime($fila['tiempo_inicio']))); ?></td>
                        <td><?php echo esc(date('Y-m-d H:i:s', strtotime($fila['tiempo_cierre']))); ?></td>

                        <td>
    <?php if (isset($fila['activo']) && $fila['activo']) { ?>
        <button class="btn btn-danger a btn-eliminar" onclick="desactivarUsuario(<?php echo intval($fila['id']); ?>, <?php echo intval($fila['id_cargo']); ?>)"><img src="../../img/desactivar2.png" alt="Desactivar" style="width: 40px; height: 50px;">Deshabilitar</button>
        <?php } else { ?>
    <button class="btn btn-primary a" onclick="activarUsuario(<?php echo intval($fila['id']); ?>);">
        <img src="../../img/habilitar3.png" alt="Activar" style="width: 50px; height: 60px;"> habilitar
    </button>
<?php } ?>
</td>
                        <td>
                        <center>
                        <a class='btn btn-primary a' href='../modificar/edi_usuario.php?id=<?php echo intval($fila['id']); ?>'><img src='../../img/modificar.png' title='Modificar' alt='Modificar' style='width: 40px; height: 50px;'></a>  
                        <button class='btn btn-danger a btn-eliminar' onclick='eliminarUsuario(<?php echo intval($fila['id']); ?>, <?php echo intval($fila['id_cargo']); ?>)'><img src='../../img/eliminar1.png' alt='eliminar' style='width: 40px; height: 50px;'>Eliminar</button>
                        </center>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div>
<script src="../../js/sweetalert.min.js"></script>
<script src="../../js/usuarios.js"></script>

<!-- Token CSRF para operaciones JS -->
<script>
    var csrfToken = '<?php echo esc($csrf_token); ?>';
</script>
</body>

</html>