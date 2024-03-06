<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelDataImport implements ToCollection
{
    public function collection(Collection $rows): Collection
    {
        return $rows;
    }
}