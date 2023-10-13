<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImport implements WithHeadingRow, ToModel
{
    protected $id;
    protected $rate;

    public function __construct($id, $rate)
    {
        $this->id = $id;
        $this->rate = $rate;
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
            'qty' => $this->toFloat($row['qty']),
            'unit_price' => $this->toFloat($row['unit_price']),
            'global_price' => $this->toFloat($row['global_price']),
            'stage' => $row['stage'],
            'real_value' => $this->toFloat($row['real']),
            'booked' => $this->toFloat($row['booked']),
            'percentage' => $this->toFloat($row['percentage']) * 1,
            'executed_dollars' => $this->toFloat($row['executed_dollars']),
            'executed_euros' => $this->toFloat($row['executed_dollars']) * $this->rate,
            'global_price_euros' => $this->toFloat($row['global_price']) * $this->rate,
            'real_value_euros' => $this->toFloat($row['real']) * $this->rate,
            'booked_euros' => $this->toFloat($row['booked']) * $this->rate,
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

    protected function toFloat($value)
    {
        return is_numeric($value) ? (float) $value : null;
    }
}
