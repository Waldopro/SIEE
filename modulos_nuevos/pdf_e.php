<?php
require '../vendor/autoload.php'; // Asegúrate de que Dompdf esté instalado
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curso_id = $_POST['curso_id'];
    $tablaHTML = $_POST['tablaHTML'];
    $graficoBarras = $_POST['graficoBarras'];
    $graficoTorta = $_POST['graficoTorta'];

    // Ruta absoluta para el cintillo
    $cintillo = "http://localhost/sistema-UEBEM/img/cintillo.jpg"; 

    include_once '../modulos/conexion/conndb.php';
   // Verificar si el usuario seleccionó "Todos los cursos"
if ($curso_id === "todos") {
    $cursoSeleccionado = "Todos los cursos";
} else {
    // Consultar el nombre del grado y la sección
    $sql = "SELECT grados.nombre AS grado, secciones.nombre AS seccion 
            FROM curso
            INNER JOIN grados ON curso.id_grado = grados.id
            INNER JOIN secciones ON curso.id_seccion = secciones.id
            WHERE curso.id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $cursoSeleccionado = $fila['grado'] . " - Sección " . $fila['seccion'];
    } else {
        $cursoSeleccionado = "Curso no encontrado";
    }

    // Cerrar la declaración si se inicializó correctamente
    if ($stmt) {
        $stmt->close();
    }
}

    $conexion->close();

    // Configuración de Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Estilos para mejorar la presentación
    $styles = "
        <style>
            body { font-family: Arial, sans-serif; text-align: center; }
            h1 { color: #333; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
            th { background-color: #f2f2f2; }
            img { max-width: 100%; }
            .page-break { page-break-before: always; }
        </style>
    ";

    // Estructura del PDF
    $html = "
    <img class='cintillo' src='$cintillo'>
    <h3 style='text-align:center;'>República Bolivariana de Venezuela</h3>
    <h3 style='text-align:center;'>Ministerio del Poder Popular para la Educación</h3>
    <h3 style='text-align:center;'>U.E Bolivariana 'Eneas Morantes'</h3>
    <h3 style='text-align:center;'>Yaguaraparo Municipio Cajigal Estado Sucre</h3>
    <h1 style='text-align:center;'>Estadísticas de Inscripción de Estudiantes</h1>
    <h3>Curso Seleccionado: $cursoSeleccionado</h3>
    <div>$tablaHTML</div>

    <!-- Salto de página para los gráficos -->
    <div class='page-break'></div>

    <!-- Segunda página con los gráficos -->
    <h2>Gráficos</h2>
    <img src='$graficoBarras'>
    <img src='$graficoTorta'>
    ";

    // Generar PDF
    $dompdf->loadHtml($styles . $html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Visualizar el archivo PDF en el navegador
    header("Content-type: application/pdf");
    header("Content-Disposition: inline; filename=estadisticas.pdf");
    echo $dompdf->output();
}
?>
