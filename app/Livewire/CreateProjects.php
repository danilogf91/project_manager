<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Rule;

class CreateProjects extends Component
{
    public $openModal = false;
    public $project;

    #[Rule('required|string|max:255')]
    public $name;

    #[Rule('required|string|max:255')]
    public $pda_code;

    #[Rule('required|numeric|min:0|max:1000')]
    public $rate;

    #[Rule('required|in:Planification,Execution,Finished|string|max:255')]
    public $state = 'Planification';

    #[Rule('required|in:Innovation,Efficiency & Saving,Replacement & Restructuring,Quality & Hygiene,Health & Safety,Environment,Maintenance,Capacity Increase|string|max:255')]
    public $investments = 'Innovation';

    #[Rule('required|in:Buildings,Furniture,General Install,Land,Machines & Equipm,Office Hardware Software,Other,Vehicles,Vessel & Fishing Equipment,Warenhouse & Distrib|string|max:255')]
    public $classification_of_investments = "Buildings";

    #[Rule('required|in:Normal Capex,Special Project|string|max:255')]
    public $justification = 'Normal Capex';

    #[Rule('required|date|before:finish_date')]
    public $start_date;

    #[Rule('required|date')]
    public $finish_date;

    public function resetForm()
    {
        $this->reset('name', 'pda_code', 'rate', 'state', 'investments', 'justification', 'start_date', 'finish_date', 'classification_of_investments');
    }

    public function createProject()
    {
        $this->validate();
        Project::create([
            'name' => $this->name,
            'pda_code' => $this->pda_code,
            'rate' => $this->rate,
            'state' => $this->state,
            'investments' => $this->investments,
            'classification_of_investments' => $this->classification_of_investments,
            'justification' => $this->justification,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
        ]);

        $this->openModal = false;
        $this->resetForm();
        $this->dispatch('project-created');
    }

    public function render()
    {
        return view('livewire.create-projects');
    }
}
