<?php
require('./fpdf.php');
require_once '../MySQLConn.php';

class PDF extends FPDF {
    function Header() {
        // Centrar la imagen
        $this->Image('Toffy’s-Books.png', ($this->GetPageWidth() - 20) / 2, 10, 20);
        $this->SetFont('Arial', 'B', 19);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(25);

        // Centrar el título "Toffy's Books"
        $this->Cell(0, 10, utf8_decode("Toffy's Books"), 0, 1, 'C');
        $this->Ln(3);

        // Centrar el subtítulo "REPORTE DE PERSONAL"
        $this->SetTextColor(228, 100, 0);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode("REPORTE DE PERSONAL"), 0, 1, 'C');
        $this->Ln(10);

        // Encabezado de la tabla
        $this->SetFillColor(228, 100, 0);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(18, 10, utf8_decode('ID'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('IDENTIDAD'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('NOMBRES'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('APELLIDOS'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('TELEFONO'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('CARGO'), 1, 1, 'C', 1);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $hoy = date('d/m/Y');
        $this->Cell(-355, 10, utf8_decode($hoy), 0, 0, 'C');
    }
}

$conn = new MySQLConn("localhost", "root", "", "toffysbooks");
$db = $conn->Conectar();
$pdf = new PDF();
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

$query = "SELECT id, identidad, nombres, apellidos, telefono, cargo FROM personal_hp";
$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $pdf->Cell(18, 10, $row['id'], 1, 0, 'C');
    $pdf->Cell(40, 10, utf8_decode($row['identidad']), 1, 0, 'C');
    $pdf->Cell(40, 10, utf8_decode($row['nombres']), 1, 0, 'C');
    $pdf->Cell(40, 10, utf8_decode($row['apellidos']), 1, 0, 'C');
    $pdf->Cell(25, 10, utf8_decode($row['telefono']), 1, 0, 'C');
    $pdf->Cell(25, 10, utf8_decode($row['cargo']), 1, 1, 'C');
}

$pdf->Output('Reporte_Personal.pdf', 'I');
?>