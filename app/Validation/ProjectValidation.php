<?php

namespace App\Validation;

class ProjectValidation
{
    public static function validation()
    {
        return [
            'name' => 'required|string|max:255',
            'pda_code' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
            'state' => 'required|in:Planification,Execution,Finished|string|max:255',
            'investments' => 'required|in:Innovation,Efficiency & Saving,Replacement & Restructuring,Quality & Hygiene,Health & Safety,Environment,Maintenance,Capacity Increase|string|max:255',
            'classification_of_investments' => 'required|in:Buildings,Furniture,General Install,Land,Machines & Equipm,Office Hardware Software,Other,Vehicles,Vessel & Fishing Equipment,Warenhouse & Distrib|string|max:255',
            'justification' => 'required|in:Normal Capex,Special Project|string|max:255',
            'start_date' => 'required|date|before:finish_date',
            'finish_date' => 'required|date|after:start_date',
        ];
    }

    // public static function updateRules()
    // {
    //     $rules = self::createRules();
    //     // Puedes agregar reglas adicionales o personalizadas para la actualización si es necesario
    //     return $rules;
    // }
}
