<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Data;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pda_code',
        'rate',
        'state',
        'investments',
        'justification',
        'classification_of_investments',
        'data_uploaded',
        'start_date',
        'finish_date',
        'file_name',
        'upload_pda'
    ];

    protected $casts = [
        'rate' => 'float',
    ];

    protected $enumFields = [
        'state' => ['Planification', 'Execution', 'Finished'],
        'investments' => [
            'Innovation',
            'Efficiency & Saving',
            'Replacement & Restructuring',
            'Quality & Hygiene',
            'Health & Safety',
            'Environment',
            'Maintenance',
            'Capacity Increase'
        ],
        'classification_of_investments' => [
            'Buildings',
            'Furniture',
            'General Install',
            'Land',
            'Machines & Equipm',
            'Office Hardware Software',
            'Other',
            'Vehicles',
            'Vessel & Fishing Equipment',
            'Warenhouse & Distrib'
        ],
        'justification' => ['Normal Capex', 'Special Project'],
    ];

    public function getEnumOptions($field)
    {
        return $this->enumFields[$field];
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }

    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%')
                    ->orWhere('pda_code', 'like', '%' . $term . '%')
                    ->orWhere('state', 'like', '%' . $term . '%')
                    ->orWhere('investments', 'like', '%' . $term . '%')
                    ->orWhere('justification', 'like', '%' . $term . '%');
            });
        }

        return $query;
    }
}
