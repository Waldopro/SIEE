<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinscripción</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Reinscripción de Estudiantes</h1>
    <form action="procesar_reinscripcion.php" method="POST">
        <label for="estudiante">Cédula del Estudiante:</label>
        <input type="text" name="cedula" id="estudiante" required>

        <button type="submit">Buscar Estudiante</button>
    </form>

    <div id="resultado">
        <!-- Datos del estudiante y formulario para reinscripción -->
    </div>
</body>
</html>
