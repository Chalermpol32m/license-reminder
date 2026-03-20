<?php

namespace App\Imports;

use App\Models\DeliveryJob;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JobsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // ข้าม header
            if ($index == 0) continue;

            DeliveryJob::create([
                'customer' => $row[0],
                'destination' => $row[1],
                'delivery_date' => $row[2],
                'status' => 'pending',
            ]);
        }
    }
}