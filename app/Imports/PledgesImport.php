<?php

namespace App\Imports;

use App\Models\Event\EventAttendee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PledgesImport implements ToModel, WithStartRow
{

    private $event_id;
    private $event_attendees_category_id;

    public function __construct(int $event_id, ?int $event_attendees_category_id = null)
    {
        $this->event_id = $event_id;
        $this->event_attendees_category_id = $event_attendees_category_id;
    }
    public function model(array $row)
    {
        return new EventAttendee([
            'event_id'     => $this->event_id,
            'event_attendees_category_id' => $this->event_attendees_category_id,
            'full_name'     => $row[0],
            'phone'    => $row[1],
            'amount'    => $row[2],
            'paid'    => $row[3] == null ? 0 : $row[3],
            'table_number'    => $row[4] == null ? '' : $row[4],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
