<?php

namespace App\Livewire;

use App\Exports\ProjectExport;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;

class ProjectsTable extends Component
{
    use WithPagination;

    public $perPage = 10;
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

    #[On('project-deleted')]
    #[On('project-created')]
    #[On('edit-projects')]
    #[On('upload-data')]
    #[On('delete-data')]
    public function render()
    {
        return view(
            'livewire.projects-table',
            [
                'projects' => Project::search($this->search)
                    // ->when($this->admin !== '', function ($query) {
                    //     $query->where('is_admin', $this->admin);
                    // })
                    ->orderBy($this->sortBy, $this->sortDir)
                    ->paginate($this->perPage)
            ]
        );
    }
}
