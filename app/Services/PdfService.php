<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class PdfService
{
public function generateExamResultPdf($exam, $userAttempts)
{
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf([
        'fontDir' => array_merge($fontDirs, [public_path('fonts')]),
        'fontdata' => $fontData + [
            'kalpurush' => [
                'R' => 'kalpurush.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ],
        'default_font' => 'kalpurush'
    ]);

    // Set watermark
    $mpdf->SetWatermarkText("Benzir's Job Aid");
    $mpdf->showWatermarkText = true; // Important: enable watermark display
    $mpdf->watermark_font = 'kalpurush'; // optional, use your font
    $mpdf->watermarkTextAlpha = 0.1; // transparency, 0-1

    $html = view('teacher.pdf.exam_result_pdf', compact('exam', 'userAttempts'))->render();

    $mpdf->WriteHTML($html);

    // Output PDF to browser
    return $mpdf->Output($exam->title . '_result.pdf', 'I');
}
}
