<?php

namespace App\Livewire;

use App\Imports\DataImport;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\Rule;

class SaveProjectsData extends Component
{
    use WithFileUploads;

    public $project;
    public $openModal;

    #[Rule('required|mimes:csv,xlsx')]
    public $excel_file;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function saveData()
    {
        $this->validate();
        // dd($this->project->id);

        Excel::import(new DataImport($this->project->id), $this->excel_file);
        $this->project->data_uploaded = 1;
        $this->project->save();
        $this->reset('excel_file', 'project', 'openModal');
        $this->dispatch('upload-data');
    }

    public function render()
    {
        return view('livewire.save-projects-data');
    }
}
