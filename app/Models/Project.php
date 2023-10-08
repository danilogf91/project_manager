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
        'data_uploaded',
        'start_date',
        'finish_date',
    ];

    protected $casts = [
        'rate' => 'float',
    ];

    protected $enumFields = [
        'state' => ['planification', 'execution', 'finished'],
        'investments' => [
            'innovation',
            'efficiency_&_saving',
            'replacement_&_restructuring',
            'quality_&_hygiene',
            'health_&_safety',
            'environment',
            'maintenance',
            'capacity_increase'
        ],
        'justification' => ['normal_capex', 'special_project'],
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
