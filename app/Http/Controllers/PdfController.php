<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Services\PdfService;

class PdfController extends Controller
{
    protected $PDFService;

    public function __construct(PdfService $PDFService)
    {
        $this->PDFService = $PDFService;
    }

    public function downloadResult($id)
    {
        $exam = Exam::findOrFail($id);
        $userAttempts = $exam->userAttempts()->orderBy('obtained_marks', 'desc')->get();

        return $this->PDFService->generateExamResultPdf($exam, $userAttempts);
    }
}
