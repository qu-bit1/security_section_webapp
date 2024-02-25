<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use \Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    private ?Report $report;

    public function __construct(?Report $report = null)
    {
        $this->report = $report;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        $data = collect();
        if ($this->report instanceof Report && $this->report->exists) {
            // If a specific report is passed, and it exists, return its data
            $data->push($this->formatData($this->report));
            return $data;
        }

        // Otherwise, return all reports
        $reports = Report::with(["tags:title", "attachments:path"])->get();
        foreach ($reports as $report) {
            $data->push($this->formatData($report));
        }
        return $data;
    }

    /**
     * Format the report data
     *
     * @param Report $report
     * @return array
     */
    public function formatData(Report $report): array
    {
        return [
            "id" => $report->id,
            'serial_number' => $report->serial_number,
            'shift' => $report->shift,
            "description" => $report->description,
            "venue" => $report->venue,
            "reporter" => $report->reporter,
            "status" => $report->status,
            "created_at" => $report->created_at,
            "updated_at" => $report->updated_at,
            "tags" => $report->tags->pluck("title")->join(", "),
            "attachments" => $report->attachments->pluck("path")->join(", "),
        ];
    }

    public function headings(): array
    {
        return [
            "ID",
            "Serial Number",
            "Shift",
            "Description",
            "Venue",
            "Reporter",
            "Status",
            "Created At",
            "Updated At",
            "Tags",
            "Attachments",
        ];
    }
}
