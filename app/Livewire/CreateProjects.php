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

    #[Rule('required|in:planification,execution,finished|string|max:255')]
    public $state = 'planification';

    #[Rule('required|in:innovation,efficiency_&_saving,replacement_&_restructuring,quality_&_hygiene,health_&_safety,environment,maintenance,capacity_increase|string|max:255')]
    public $investments = 'innovation';

    #[Rule('required|in:normal_capex,special_project|string|max:255')]
    public $justification = 'normal_capex';

    #[Rule('required|date|before:finish_date')]
    public $start_date;

    #[Rule('required|date')]
    public $finish_date;

    public function resetForm()
    {
        $this->reset('name', 'pda_code', 'rate', 'state', 'investments', 'justification', 'start_date', 'finish_date');
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
