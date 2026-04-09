<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include('verificar_session.php');

// Conexión a la base de datos
include "conexion/conndb.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar CSRF
    exigir_csrf();

    // Validar y recibir datos del formulario
    $id_estudiante = $_POST['id_estudiante'] ?? null;
    $id_curso = $_POST['id_curso'] ?? null;
    $id_representante = $_POST['id_representante'] ?? null;
    $id_tipo_ins = $_POST['id_tipo_ins'] ?? null;

    // Obtener el representante asociado al estudiante
    $query_representante = "SELECT id_representante FROM familia WHERE id_estudiante = ?";
    $stmt_representante = $conexion->prepare($query_representante);
    $stmt_representante->bind_param('i', $id_estudiante);
    $stmt_representante->execute();
    $result_representante = $stmt_representante->get_result()->fetch_assoc();
    $id_representante = $result_representante['id_representante'];
    $stmt_representante->close();

    // Validar que los campos no estén vacíos
    if ($id_estudiante && $id_curso && $id_representante && $id_tipo_ins) {
        // Verificar si el estudiante ya está inscrito en el curso
        $verificar_sql = "SELECT COUNT(*) AS count FROM inscripcion WHERE id_estudiante = ? AND id_curso = ?";
        $stmt_verificar = $conexion->prepare($verificar_sql);
        $stmt_verificar->bind_param('ii', $id_estudiante, $id_curso);
        $stmt_verificar->execute();
        $resultado_verificar = $stmt_verificar->get_result()->fetch_assoc();

        if ($resultado_verificar['count'] > 0) {
            echo "<p>El estudiante ya está inscrito en este curso.</p>";
        } else {
            // Insertar la inscripción
            $sql = "INSERT INTO inscripcion (id_curso, id_estudiante, id_representante, id_tipo_ins)
                    VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('iiii', $id_curso, $id_estudiante, $id_representante, $id_tipo_ins);

            if ($stmt->execute()) {
                header("Location: inscritos.php?status=success");
                exit();
            } else {
                echo "<p>Error al inscribir: " . esc($stmt->error) . "</p>";
            }

            $stmt->close();
        }

        $stmt_verificar->close();
    } else {
        echo "<p>Por favor, completa todos los campos.</p>";
    }
}

// Cargar datos para el formulario
$tiposInsQuery = "SELECT id, tipo FROM tipo_ins";
$tiposIns = $conexion->query($tiposInsQuery);

$csrf_token = generar_token_csrf();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Inscripción</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="../css/procesare.css">
    <link rel="stylesheet" type="text/css" href="../css/inicio.css">
    <link rel="stylesheet" type="text/css" href="../css/registro_e.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../lib/select2.min.js"></script>
    <link rel="stylesheet" href="../lib/select2.min.css">
</head>
<body oncontextmenu="return false;">

    <?php include('menu.php') ?>
    
   <center> <h1 class="h1">Inscripción de Estudiantes</h1></center>
    <form action="inscribir.php" id="form" method="post">
        <?php echo campo_csrf(); ?>
        <div class="contenedor-formularios">
            <div class="formulario">
               
                <label for="id_estudiante">Buscar y Seleccionar Estudiante:</label>
                <select name="id_estudiante" id="id_estudiante" required>
                    <option value="">Buscar estudiante por cédula o nombre</option>
                    <?php
                    $query = "SELECT e.id_estudiante, e.cedula_e, CONCAT(e.nombres_e, ' ', e.apellidos_e) AS nombre_completo, 
                                     CONCAT(r.nombres, ' ', r.apellidos) AS nombre_representante
                              FROM estudiante e 
                              INNER JOIN familia f ON e.id_estudiante = f.id_estudiante 
                              INNER JOIN representante r ON f.id_representante = r.id_representante
                              WHERE e.id_estudiante NOT IN (SELECT id_estudiante FROM inscripcion)";
                    $result = $conexion->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . intval($row['id_estudiante']) . "' data-cedula='" . esc($row['cedula_e']) . "'>
                              " . esc($row['cedula_e']) . " - " . esc($row['nombre_completo']) . " (Representante: " . esc($row['nombre_representante']) . ")
                              </option>";
                    }
                    ?>
                </select>

                <label for="id_curso">Curso:</label>
                <select name="id_curso" id="id_curso" required>
                    <option value="">Seleccionar curso</option>
                    <?php
                    $query = "SELECT c.id, CONCAT(g.nombre, ' ', s.nombre) AS curso, 
                                     (SELECT COUNT(*) FROM inscripcion i WHERE i.id_curso = c.id) AS inscritos
                              FROM curso c
                              INNER JOIN grados g ON c.id_grado = g.id
                              INNER JOIN secciones s ON c.id_seccion = s.id
                              HAVING inscritos < 25";
                    $result = $conexion->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . intval($row['id']) . "'>" . esc($row['curso']) . " - Inscritos: " . esc($row['inscritos']) . "</option>";
                    }
                    ?>
                </select>

                <label for="id_tipo_ins">Tipo de inscripción:</label>
                <select name="id_tipo_ins" id="id_tipo_ins" required>
                    <option value="">Seleccionar tipo</option>
                    <?php while ($row = $tiposIns->fetch_assoc()): ?>
                        <option value="<?= intval($row['id']) ?>"><?= esc($row['tipo']) ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="btn-primary" value="Guardar"><img src="../img/inscribir.png" alt="Inscribir" style="width: 40px; height: 50px;"> Inscribir</button>
            <button id="boton1" style="background-color: blue;color: #f2f2f2;padding: 10px 20px;text-decoration: none; border: none; border-radius: 20px;  cursor: pointer;" onclick="history.back()"> <img src="../img/volver1.png" alt="Volver" style="width: 40px; height: 50px;">Volver</button>  
                    </div>
        </div>
    </form>
    <div class="footer">
    <p>Proyecto <?php echo date('Y'); ?> UPTP CAJIGAL &copy; Todos los derechos reservados</p>
</div>
<script>
    $(document).ready(function() {
        $('#id_estudiante').select2({
            placeholder: "Buscar estudiante por cédula o nombre",
            allowClear: true
        });
    });
</script>
</body>
</html>