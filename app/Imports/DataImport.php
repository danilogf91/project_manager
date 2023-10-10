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
            'global_price' => $row['global_price'],
            'stage' => $row['stage'],
            'real_value' => $row['real'],
            'committed' => $row['committed'],
            'percentage' => ((float)($row['percentage'])),
            'executed_dollars' => ((float)($row['executed_dollars'])),
            'executed_euros' => ((float)($row['percentage'])),
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
