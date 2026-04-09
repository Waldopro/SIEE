<?php
// Iniciar sesión
include '../verificar_session.php';
// procesar_busqueda.php
include '../conexion/conndb.php';
require('../../lib/fpdf/fpdf.php'); // Incluye la librería FPDF

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$seccion = $_GET['seccion_e'];
$grado = $_GET['grado_e'];

$consulta = "SELECT * FROM estudiantes WHERE seccion_e = '$seccion' AND grado_e = '$grado'";
$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Función para generar el PDF
class PDF extends FPDF
{
    function Header()
    {
        // Encabezado del PDF
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Lista de Estudiantes', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        // Pie de página del PDF
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Construye el contenido HTML con los resultados de la búsqueda
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Nombre', 1);
$pdf->Cell(40, 10, 'Sección', 1);
$pdf->Cell(40, 10, 'Grado', 1);
$pdf->Ln();
while ($fila = mysqli_fetch_assoc($resultado)) {
    $pdf->Cell(40, 10, $fila['nombre'], 1);
    $pdf->Cell(40, 10, $fila['seccion_e'], 1);
    $pdf->Cell(40, 10, $fila['grado_e'], 1);
    $pdf->Ln();
}
$pdf->Output('lista_estudiantes.pdf', 'D'); // Descarga el archivo PDF con el nombre "lista_estudiantes.pdf"

mysqli_close($conexion);
