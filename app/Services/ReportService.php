<?php

namespace App\Services;

use App\Exports\ReportsExport;
use App\Models\Report;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use \Barryvdh\DomPDF\PDF as PDFResponse;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use \Symfony\Component\HttpFoundation\BinaryFileResponse;
class ReportService
{
    public function generate(Report $report, string $format = "pdf"): Response|BinaryFileResponse
    {
        $slug = Str::slug("report {$report->title}", separator: "_");
        if ($format === "pdf") {
            return $this->generatePdf($report)->download("{$slug}.pdf");
        } elseif ($format === "csv") {
            return $this->generateCsv($report);
        } else{
            return response("Unsupported format", ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    public function export(string $format): BinaryFileResponse
    {
        return Excel::download(new ReportsExport, "reports.csv");
    }

    private function generatePdf(Report $report): PDFResponse
    {
        return Pdf::loadView('reports.pdf', compact('report'));
    }

    private function generateCsv(Report $report): BinaryFileResponse
    {
        return Excel::download(new ReportsExport($report), "reports.csv");
    }
}
