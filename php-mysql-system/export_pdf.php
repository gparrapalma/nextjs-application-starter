<?php
require_once 'db.php';
require_once 'vendor/autoload.php'; // Assuming TCPDF or FPDF is installed via composer

use \setasign\Fpdi\Fpdi; // Example if using FPDI, adjust if using TCPDF or FPDF

if (!isset($_GET['id'])) {
    die("ID de solicitud no proporcionado.");
}

$id = intval($_GET['id']);

// Fetch replacement request and employee info
$stmt = $conn->prepare("SELECT r.fecha_ausencia, r.fecha_inicio_reemplazo, r.fecha_termino_reemplazo, e.rut, e.nombre, e.planta, e.turno, e.grado FROM replacement_requests r JOIN employees e ON r.employee_id = e.id WHERE r.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Solicitud no encontrada.");
}

$data = $result->fetch_assoc();

// Generate PDF using FPDF
require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'Solicitud de Reemplazo Funcionario',0,1,'C');
        $this->Ln(10);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$pdf->Cell(50,10,'RUT:',0,0);
$pdf->Cell(0,10,$data['rut'],0,1);

$pdf->Cell(50,10,'Nombre:',0,0);
$pdf->Cell(0,10,$data['nombre'],0,1);

$pdf->Cell(50,10,'Planta:',0,0);
$pdf->Cell(0,10,$data['planta'],0,1);

$pdf->Cell(50,10,'Turno:',0,0);
$pdf->Cell(0,10,$data['turno'],0,1);

$pdf->Cell(50,10,'Grado:',0,0);
$pdf->Cell(0,10,$data['grado'],0,1);

$pdf->Cell(50,10,'Fecha de Ausencia:',0,0);
$pdf->Cell(0,10,$data['fecha_ausencia'],0,1);

$pdf->Cell(50,10,'Fecha Inicio Reemplazo:',0,0);
$pdf->Cell(0,10,$data['fecha_inicio_reemplazo'],0,1);

$pdf->Cell(50,10,'Fecha Termino Reemplazo:',0,0);
$pdf->Cell(0,10,$data['fecha_termino_reemplazo'],0,1);

$pdf->Output('D', 'solicitud_reemplazo_' . $data['rut'] . '.pdf');
?>
