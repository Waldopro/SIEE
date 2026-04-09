<?php
// Iniciar sesión
include '../verificar_session.php';
require_once('../../lib/fpdf/fpdf.php');

class CustomPDF extends FPDF {
    // Ruta y nombre de la imagen encima del encabezado
    private $imagen_encabezado = '../../img/cintillo.jpg';

    // Ancho y alto de la imagen encima del encabezado
    private $imagen_width = 240;
    private $imagen_height = 8;

    function Header() {
        // Margen izquierdo y derecho
        $margen_lateral = 1.10; // 1.45 mm

        // Posiciona la imagen encima del encabezado centrada horizontalmente
        $x_imagen = $this->GetX() - 0.5; // Mueve la imagen 2 cm (aproximadamente 20 unidades)
        $y_imagen = $this->GetY();
        $this->Image($this->imagen_encabezado, $x_imagen, $y_imagen, $this->imagen_width, $this->imagen_height);

        // Ajusta la posición Y después de insertar la imagen
        $this->SetY($y_imagen + $this->imagen_height);

        // Configura la fuente y tamaño del texto
        $this->SetFont('Arial', 'B', 12);

        // Primera línea de texto
        $this->Cell(0, 9, 'Republica Bolivariana de Venezuela', 0, 1, 'C');
        // Segunda línea de texto
        $this->Cell(0, 4, 'Ministerio del Poder Popular para la Educacion', 0, 1, 'C');
        // Tercera línea de texto
        $this->Cell(0, 7, 'U.E "Eneas Morantes"', 0, 1, 'C');
        // Cuarta línea de texto
        $this->Cell(0, 6, 'Yaguaraparo Municipio Cajigal Estado Sucre', 0, 1, 'C');

        // Agrega una línea después del encabezado
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 297 - $margen_lateral * 2, $this->GetY());
        $this->Ln(5);
    }

    function Footer() {
        // Pie de página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
        // Otras líneas de código para personalizar el pie de página
    }
}

$pdf = new CustomPDF();

// Establecer márgenes iguales en ambos lados
$pdf->SetLeftMargin(25.4); // 25.4 mm = 1 pulgada
// Establecer margen derecho
$pdf->SetRightMargin(25.4); // 25.4 mm = 1 pulgada

// Configurar fuentes y codificación
$pdf->SetFont('Arial', '', 12, '', true, 'UTF-8');

// Agregar una página
$pdf->AddPage('L');

// Configurar metadatos del documento
$pdf->SetTitle('Matricula de Estudiantes');
$pdf->SetAuthor('Autor del PDF');
$pdf->SetSubject('Informe PDF');
$pdf->SetKeywords('PDF, Estudiantes, Informe');

// Obtener los datos de la base de datos
include '../conexion/conndb.php';

$pdf->Cell(0, 9, 'Matricula General', 0, 1, 'C');
$pdf->Ln(5);

// Consulta para recuperar los registros
$sql = "SELECT g.nombre AS nombre_grado, 
               s.nombre AS nombre_seccion, 
               c.anho AS anho_escolar, 
               e.nombres_e AS nombre_estudiante, 
               e.apellidos_e AS apellido_estudiante, 
               e.cedula_e AS cedula_estudiante, 
               e.fecha_nac AS fecha_nac_estudiante,
               e.sexo AS sexo_estudiante  
        FROM curso c
        LEFT JOIN grados g ON c.id_grado = g.id
        LEFT JOIN secciones s ON c.id_seccion = s.id
        LEFT JOIN inscripcion i ON c.id = i.id_curso
        LEFT JOIN estudiante e ON i.id_estudiante = e.id_estudiante
        WHERE i.id_estudiante IS NOT NULL
        ORDER BY g.orden, s.nombre, e.apellidos_e, e.nombres_e";

$result = $conexion->query($sql);

$current_grado = "";
$current_seccion = "";

while ($row = $result->fetch_assoc()) {
    if ($row['nombre_grado'] != $current_grado || $row['nombre_seccion'] != $current_seccion) {
        // Si es un nuevo grado o sección, agregamos una nueva página
        if ($current_grado != "") {
            $pdf->AddPage('L');
        }
        $current_grado = $row['nombre_grado'];
        $current_seccion = $row['nombre_seccion'];

        // Encabezado de la nueva página
        $pdf->Cell(0, 9, "Grado: {$current_grado} - Seccion: {$current_seccion}", 0, 1, 'C');
        $pdf->Ln(5);

        // Encabezados de las columnas con celdas más anchas
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(18, 7, 'Grado', 1, 0, 'C');
        $pdf->Cell(25, 7, 'Seccion', 1, 0, 'C');
        $pdf->Cell(45, 7, 'Cedula', 1, 0, 'C');
        $pdf->Cell(60, 7, 'Apellidos', 1, 0, 'C');
        $pdf->Cell(60, 7, 'Nombres', 1, 0, 'C');
        $pdf->Cell(25, 7, 'Edad', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Sexo', 1, 1, 'C');  // Nueva columna para el sexo

        $pdf->SetFont('Arial', '', 10);
    }

    // Calcular la edad
    $fecha_nac = new DateTime($row['fecha_nac_estudiante']);
    $hoy = new DateTime();
    $edad = $hoy->diff($fecha_nac)->y;

    // Mostrar el sexo del estudiante (Masculino o Femenino)
    $sexo_estudiante = $row['sexo_estudiante']; // Ya está correctamente como "Masculino" o "Femenino"

    // Contenido de la tabla
    $pdf->Cell(18, 7, utf8_decode($row['nombre_grado']), 1, 0, 'C');
    $pdf->Cell(25, 7, utf8_decode($row['nombre_seccion']), 1, 0, 'C');
    $pdf->Cell(45, 7, utf8_decode($row['cedula_estudiante']), 1, 0, 'C');
    $pdf->Cell(60, 7, utf8_decode($row['apellido_estudiante']), 1, 0, 'C');
    $pdf->Cell(60, 7, utf8_decode($row['nombre_estudiante']), 1, 0, 'C');
    $pdf->Cell(25, 7, utf8_decode($edad), 1, 0, 'C');
    $pdf->Cell(30, 7, utf8_decode($sexo_estudiante), 1, 1, 'C');  // Mostrar sexo correctamente
}

// Cerrar la conexión a la base de datos u otros recursos
$conexion->close();

// Enviar el PDF al navegador para imprimirlo
$pdf->Output('I', 'Matricula_Estudiantes.pdf');
exit();
?>
