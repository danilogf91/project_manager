<?php

namespace App\Livewire;

use App\Exports\DataExport;
use App\Models\Project;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public $is_admin_user = false;
    public $active = false;

    #[Url()]
    public $search = '';
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

    #[On('edit-data-message')]
    public function editDataMessage()
    {
        session()->flash('edit-data', 'Data was updated successfully');
    }

    #[On('edit-data-message-deleted')]
    public function editDataMessageDeleted()
    {
        session()->forget('edit-data');
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
