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
                'stage',
                'global_price',
                'real_value',
                'booked',
                'percentage',
                'executed_dollars',
                'executed_euros',
                // 'global_price_euros',
                // 'real_value_euros',
                // 'booked_euros',
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
            'booked',
            'percentage',
            'executed dollars',
            'executed euros',
            // 'global price euros',
            // 'real value euros',
            // 'booked euros',
            'supplier',
            'code',
            'order no',
            'input num',
            'observations'
        ];
    }
}
