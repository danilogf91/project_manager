<?php

namespace App\Exports;

use App\Models\Data;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataExport implements FromCollection, WithHeadings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Data::where('project_id', $this->id)
            ->select(
                'id',
                'area',
                'group_1',
                'group_2',
                'description',
                'general_classification',
                'item_type',
                'unit',
                'qty',
                'unit_price',
                'global_price',
                'stage',
                'real_value',
                'committed',
                'percentage',
                'executed_dollars',
                'executed_euros',
                'supplier',
                'code',
                'order_no',
                'input_num',
                'observations'
            )->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'area',
            'group 1',
            'group 2',
            'description',
            'general classification',
            'item type',
            'unit',
            'qty',
            'unit price',
            'global price',
            'stage',
            'real',
            'committed',
            'percentage',
            'executed dollars',
            'executed euros',
            'supplier',
            'code',
            'order no',
            'input num',
            'observations'
        ];
    }
}
