<?php
require('fpdf/fpdf.php');
include_once $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/modulos/config.php';
include '../modulos/conexion/conndb.php';  // Se agregó el punto y coma faltante

// Obtener datos del boletín
$id_boletin = $_GET['id']; // ID del boletín pasado por URL
$sql = "SELECT e.nombres_e AS estudiante, e.apellidos_e, c.id_grado, c.id_seccion, 
               b.fecha_entrega, b.firmado, ev.id_lapso, ev.calificacion, ev.observaciones
        FROM boletines b
        INNER JOIN evaluaciones ev ON b.id_evaluacion = ev.id
        INNER JOIN estudiante e ON ev.id_estudiante = e.id_estudiante
        INNER JOIN curso c ON ev.id_curso = c.id
        WHERE b.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_boletin);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Boletín no encontrado.");
}

$data = $result->fetch_assoc();

// Crear el PDF
class PDF extends FPDF {
    function Header() {
        // Imagen del diseño del boletín (asegúrate de que la imagen exista en el directorio)
        $this->Image('boletin_plantilla.jpg', 0, 0, 210, 297); // A4 completo
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Datos del estudiante
$pdf->SetXY(30, 50); // Ajusta las coordenadas según el diseño
$pdf->Cell(0, 10, utf8_decode("Estudiante: " . $data['estudiante'] . " " . $data['apellidos_e']), 0, 1);
$pdf->SetXY(30, 60);
$pdf->Cell(0, 10, utf8_decode("Grado: " . $data['id_grado'] . " - Sección: " . $data['id_seccion']), 0, 1);
$pdf->SetXY(30, 70);
$pdf->Cell(0, 10, utf8_decode("Fecha de entrega: " . $data['fecha_entrega']), 0, 1);

// Evaluaciones
$pdf->SetXY(30, 90);
$pdf->Cell(0, 10, utf8_decode("Lapso: " . $data['id_lapso']), 0, 1);
$pdf->SetXY(30, 100);
$pdf->Cell(0, 10, utf8_decode("Calificación: " . $data['calificacion']), 0, 1);
$pdf->SetXY(30, 110);
$pdf->MultiCell(0, 10, utf8_decode("Observaciones: " . $data['observaciones']), 0);

// Estado de firma
$pdf->SetXY(30, 130);
$pdf->Cell(0, 10, utf8_decode("Firmado: " . ucfirst($data['firmado'])), 0, 1);

// Guardar o mostrar el PDF
$pdf->Output('I', 'Boletin_' . $data['estudiante'] . '.pdf'); // Mostrar en navegador
?>
