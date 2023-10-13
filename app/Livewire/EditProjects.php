<?php

namespace App\Livewire;

use App\Models\Project;
use App\Validation\ProjectValidation;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Rule;

class EditProjects extends Component
{
    public $openModal = false;
    public $project;
    // #[Rule('required|string|max:255')]
    public $name;

    // #[Rule('required|string|max:255')]
    public $pda_code;

    // #[Rule('required|numeric|min:0|max:1000')]
    public $rate;

    // #[Rule('required|in:Planification,Execution,Finished|string|max:255')]
    public $state = 'Planification';

    // #[Rule('required|in:Innovation,Efficiency & Saving,Replacement & Restructuring,Quality & Hygiene,Health & Safety,Environment,Maintenance,Capacity Increase|string|max:255')]
    public $investments = 'Innovation';

    // #[Rule('required|in:Buildings,Furniture,General Install,Land,Machines & Equipm,Office Hardware Software,Other,Vehicles,Vessel & Fishing Equipment,Warenhouse & Distrib|string|max:255')]
    public $classification_of_investments = 'Buildings';

    // #[Rule('required|in:Normal Capex,Special Project|string|max:255')]
    public $justification = 'Normal Capex';

    // #[Rule('required|date|before:finish_date')]
    public $start_date;

    // #[Rule('required|date|after:start_date')]
    public $finish_date;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->pda_code = $project->pda_code;
        $this->rate = $project->rate;
        $this->state = $project->state;
        $this->investments = $project->investments;
        $this->classification_of_investments = $project->classification_of_investments;
        $this->justification = $project->justification;
        $this->start_date = date("Y-m-d", strtotime($project->start_date));
        $this->finish_date = date("Y-m-d", strtotime($project->finish_date));
    }

    public function update(Project $project)
    {
        // $this->validate();
        $this->validate(ProjectValidation::validation());
        $project = $this->project;
        $project->name = $this->name;
        $project->pda_code = $this->pda_code;
        $project->rate = $this->rate;
        $project->state = $this->state;
        $project->investments = $this->investments;
        $project->classification_of_investments = $this->classification_of_investments;
        $project->justification = $this->justification;
        $project->start_date = $this->start_date;
        $project->finish_date = $this->finish_date;
        $project->save();
        $this->openModal = false;
        $this->dispatch('edit-projects');
        $this->dispatch('edit-projects-message');
    }

    public function render()
    {
        return view('livewire.edit-projects');
    }
}
