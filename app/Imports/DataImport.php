<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImport implements WithHeadingRow, ToModel
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        return new Data([
            'area' => $row['area'],
            'project_id' => $this->id,
            'group_1' => $row['group_1'],
            'group_2' => $row['group_2'],
            'description' => $row['description'],
            'general_classification' => $row['general_classification'],
            'item_type' => $row['item_type'],
            'unit' => $row['unit'],
            'qty' => (float)($row['qty']),
            'unit_price' => $row['unit_price'],
            'global_price' => (((float)$row['unit_price']) * ((float)$row['qty'])),
            'stage' => $row['stage'],
            'real_value' => $row['real'],
            'committed' => $row['committed'],
            'percentage' => 100 * ((float)($row['percentage'])),
            'executed_dollars' => ((float)($row['percentage'])) * ((float)($row['committed'])),
            'executed_euros' => round((((float)($row['percentage'])) * ((float)($row['committed']))) / 1.07, 2),
            'supplier' => $row['supplier'],
            'code' => $row['code'],
            'order_no' => $row['order_no'],
            'input_num' => $row['input_num'],
            'observations' => $row['observations'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
