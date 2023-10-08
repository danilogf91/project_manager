<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Rule;

class EditProjects extends Component
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

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->name = $project->name;
        $this->pda_code = $project->pda_code;
        $this->rate = $project->rate;
        $this->state = $project->state;
        $this->investments = $project->investments;
        $this->justification = $project->justification;
        $this->start_date = date("Y-m-d", strtotime($project->start_date));
        $this->finish_date = date("Y-m-d", strtotime($project->finish_date));
    }

    public function update(Project $project)
    {
        $this->validate();

        $project = $this->project;
        $project->name = $this->name;
        $project->pda_code = $this->pda_code;
        $project->rate = $this->rate;
        $project->state = $this->state;
        $project->investments = $this->investments;
        $project->justification = $this->justification;
        $project->start_date = $this->start_date;
        $project->finish_date = $this->finish_date;
        $project->save();
        $this->openModal = false;
        $this->dispatch('edit-projects');
    }

    public function render()
    {
        return view('livewire.edit-projects');
    }
}
