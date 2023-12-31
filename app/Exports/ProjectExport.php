<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Project::all();

        return Project::select(
            'id',
            'name',
            'pda_code',
            'rate',
            'state',
            'upload_pda',
            'investments',
            'justification',
            'classification_of_investments'
        )->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'pda_code',
            'rate',
            'state',
            'upload_pda',
            'investments',
            'justification',
            'classification_of_investments'
        ];
    }
}
