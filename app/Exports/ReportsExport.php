<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use \Illuminate\Support\Collection;
class ReportsExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Report::with(["tags", "attachments"])->get();
    }
}
