<?php

namespace App\Livewire;

use App\Models\Data;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class DeleteProjectsData extends Component
{
    public $openModal = false;
    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function delete()
    {
        Data::where('project_id', $this->project->id)->delete();
        $this->project->data_uploaded = 0;
        $this->project->save();
        $this->reset('openModal');
        $this->dispatch('update-projects-table');
        $this->dispatch('delete-data-message');
    }

    public function render()
    {
        return view('livewire.delete-projects-data');
    }
}
