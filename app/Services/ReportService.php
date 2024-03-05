<?php

namespace App\Services;

use App\Models\Report;

use Barryvdh\DomPDF\Facade\Pdf;
use \Barryvdh\DomPDF\PDF as PDFResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use STS\ZipStream\ZipStream;
use Zip;

class ReportService
{
    public function export(Collection $reports): ZipStream
    {
        $mainFolder = config('app.name')."_".sha1(time());
        $zip = Zip::create("{$mainFolder}.zip");
        foreach ($reports as $report) {
            $folder = Str::slug("report {$report->serial_number}", separator: "_");
            foreach ($report->attachments as $attachment) {
                $zip->add(Storage::get($attachment->path), "{$folder}/attachments/{$attachment->name}");
            }
            $pdf = $this->generatePdf($report);
            $zip->add($pdf->output(), "{$folder}/report.pdf");
        }
        return $zip;
    }

    private function generatePdf(Report $report): PDFResponse
    {
        return Pdf::loadView('reports.pdf', compact('report'));
    }
}
