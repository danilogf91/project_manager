<?php

namespace App\Livewire;

use App\Exports\ProjectExport;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;

class ProjectsTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    #[Url()]
    public $search = '';

    public $sortBy = 'id';
    public $sortDir = 'DESC';

    public $is_admin_user = false;
    public $active = false;

    public function mount($is_admin, $active)
    {
        $this->is_admin_user = $is_admin;
        $this->active = $active;
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir === "ASC") ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    public function export()
    {
        return Excel::download(new ProjectExport, 'projects.xlsx');
    }

    #[On('update-projects-table')]
    public function render()
    {
        return view(
            'livewire.projects-table',
            [
                'projects' => Project::search($this->search)
                    ->orderBy($this->sortBy, $this->sortDir)
                    ->paginate($this->perPage)
            ]
        );
    }

    #[On('project-deleted-message')]
    public function sessionMessage()
    {
        session()->flash('delete-project', 'The project was deleted successfully');
    }

    #[On('clear-project-deleted-message')]
    public function clearProjectDeletedMessage()
    {
        session()->forget('delete-project');
    }

    #[On('project-created-messagge')]
    public function projectCreatedMssagge()
    {
        session()->flash('create-project', 'The project was created successfully');
    }

    #[On('project-created-messagge-deleted')]
    public function projectCreatedMessaggeDeleted()
    {
        session()->forget('create-project');
    }

    #[On('edit-projects-message')]
    public function editProjectsMessage()
    {
        session()->flash('edit-project', 'The project was updated successfully');
    }

    #[On('edit-projects-message-deleted')]
    public function editProjectsMessageDeleted()
    {
        session()->forget('edit-project');
    }

    #[On('delete-data-message')]
    public function deleteDataMessage()
    {
        session()->flash('delete-excel-data', 'Data was deleted successfully');
    }

    #[On('delete-data-message-deleted')]
    public function deleteDataMessageDeleted()
    {
        session()->forget('delete-excel-data');
    }

    #[On('upload-data-message')]
    public function uploadDataMessage()
    {
        session()->flash('load-excel-data', 'Load data successfully');
    }

    #[On('upload-data-message-deleted')]
    public function uploadDataMessageDeleted()
    {
        session()->forget('load-excel-data');
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-stone-200">
                    <div class="p-4 rounded">
                        <p class="text-3xl font-extrabold">Loading....</p>
                    </div>
                </div>
        HTML;
    }
}
