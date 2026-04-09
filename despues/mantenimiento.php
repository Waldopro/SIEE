<?php

include 'modulos/funcion-m.php'; // Asegúrate de incluir el archivo con las funciones necesarias

// Verificar autenticación del desarrollador
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['developer_code'])) {
    $developerCode = $_POST['developer_code'];
    
    if (authenticateDeveloper($developerCode)) {
        $_SESSION['developer_authenticated'] = true; // Marca al desarrollador como autenticado
        header('Location: login.php'); // Redirige al login después de la autenticación
        exit();
    } else {
        echo "<p style='color:red;'>Código incorrecto</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento</title>
    <style>
        .developer-access {
            display: none;
        }
        /* Estilo para revelar el formulario al hacer clic en un área específica */
        .reveal-access:hover + .developer-access {
            display: block;
        }
    </style>
</head>
<body>
    <div style='text-align: center; margin-top: 20%;'>
        <h1>El sistema está en mantenimiento</h1>
        <p>El sistema estará operativo de lunes a viernes, desde las 5:00AM hasta las 4:59PM.</p>

        <!-- Área para hacer clic y revelar el acceso del desarrollador -->
        <div class="reveal-access" style="cursor: pointer; color: blue; text-decoration: underline;">
            Área para desarrolladores
        </div>

        <!-- Formulario de acceso del desarrollador oculto -->
        <div class="developer-access">
            <h2>Acceso de Desarrollador</h2>
            <form method="post" action="mantenimiento.php">
                <label for="developer_code">Código de Desarrollador:</label>
                <input type="password" id="developer_code" name="developer_code" required><br><br>
                <button type="submit">Autenticar</button>
            </form>
            <!-- PHP para manejar la autenticación ya está arriba -->
        </div>
    </div>
</body>
</html>
