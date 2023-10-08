<?php

namespace App\Livewire;

use App\Exports\DataExport;
use App\Models\Project;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public $is_admin_user = false;
    public $active = false;
    public $search;
    public $name;
    public $id;
    public $perPage = 5;

    public $sortBy = 'id';
    public $sortDir = 'DESC';

    public function mount($is_admin, $active, $name, $id)
    {
        $this->is_admin_user = $is_admin;
        $this->active = $active;
        $this->name = $name;
        $this->id = $id;
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
        return Excel::download(new DataExport($this->id), 'data.xlsx');
    }

    #[On('edit-data')]
    public function render()
    {
        $project = Project::find($this->id);
        $dataQuery = $project->data();

        if ($this->search) {
            $dataQuery->where(function ($query) {
                $query->where('area', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('group_1', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('group_2', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('general_classification', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('item_type', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('stage', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('supplier', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('code', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('order_no', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('observations', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('input_num', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('percentage', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $this->search . '%');
            });
        }

        $dataQuery->where('project_id', $project->id);

        $dataQuery->orderBy($this->sortBy, $this->sortDir);

        $data = $dataQuery->paginate($this->perPage);

        return view('livewire.data-table', [
            'data' => $data
        ]);
    }
}
