<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::select('id', 'name', 'email', 'active', 'created_at', 'updated_at')->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'active',
            'created_at',
            'updated_at',
            'avatar',
        ];
    }
}
