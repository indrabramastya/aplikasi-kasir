<?php

require('../config/config.php');
require('../config/functions.php');
require('../asset/fpdf/vendor/autoload.php');


$stokBrg = getData("SELECT * FROM file_barang");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Laporan Stok Barang', 0, 1, 'C'); //ukuran (lebar,tinggi, 'text',border,tempat text berikutnya (kanan 0, bawah 1), C = center) dlm milimeter

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 2, '', 'B', 1); //B = Bottom
$pdf->Cell(10, 10, 'No.', 0, 0, 'C');
$pdf->Cell(30, 10, 'Kode Barang', 0, 0);
$pdf->Cell(90, 10, 'Nama Barang', 0, 0);
$pdf->Cell(30, 10, 'Jumlah Stok', 0, 0);
$pdf->Cell(30, 10, 'Satuan', 0, 1);
$pdf->Cell(190, 2, '', 'T', 1); //T = TOP

$pdf->SetFont('Arial', '', 12);
$no = 1;
foreach ($stokBrg as $stok) {
    $pdf->Cell(10, 8, $no++, 0, 0, 'C');
    $pdf->Cell(30, 8, $stok['id_barang'], 0, 0,);
    $pdf->Cell(90, 8, $stok['nama_barang'], 0, 0,);
    $pdf->Cell(30, 8, $stok['stok'], 0, 0, 'C');
    $pdf->Cell(30, 8, $stok['satuan'], 0, 1);
}
$pdf->Cell(190, 2, '', 'T', 1); //T = TOP
$pdf->Output();
