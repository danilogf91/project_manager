<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Attributes\On;
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
        // session()->flash('delete-project', 'The project was deleted successfully');
        $this->openModal = false;
        $this->dispatch('project-deleted');
        $this->dispatch('project-deleted-message');
    }

    public function render()
    {
        return view('livewire.delete-projects');
    }
}
