<?php
// src/Controller/PdfGeneratorService.php
namespace App\Entity;

use TCPDF;
class PdfGeneratorService
{
    public function generatePdf($html)
    {
        $pdf = new TCPDF();
        $pdf->AddPage();
        // Add logo to the PDF

        $pdf->writeHTML($html, true, false, true, false, '');
        return $pdf->Output('document.pdf', 'S');
    }
}
?>