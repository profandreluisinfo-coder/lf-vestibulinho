<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SimpleImport implements ToCollection
{
    public $rows;

    public function collection(Collection $collection)
    {
        $this->rows = $collection;
    }
}
