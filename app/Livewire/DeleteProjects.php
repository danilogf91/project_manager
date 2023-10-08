<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class DeleteProjects extends Component
{
    public $openModal = false;
    public $project = false;

    public function mount($project)
    {
        $this->project = $project;
    }

    public function delete(Project $project)
    {
        $project->delete();
        $this->openModal = false;
        $this->dispatch('project-deleted');
    }

    public function render()
    {
        return view('livewire.delete-projects');
    }
}
