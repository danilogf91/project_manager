<?php

namespace App\Livewire;

use App\Imports\DataImport;
use App\Models\Project;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\Rule;

class SaveProjectsData extends Component
{
    use WithFileUploads;
    public $project;
    public $viewModal;
    public $id;
    public $rate;
    public $buttonState = true;
    public $imageKey;

    #[Rule('required|mimes:csv,xlsx')]
    public $excel_file;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->id = $project->id;
        $this->rate = $project->rate;
    }

    public function updated($property, $value)
    {
        if ($property === 'excel_file') {
            $this->validate();
            $this->buttonState = false;
        }

        if ($property === "viewModal" && !$value) {
            $this->excel_file = null;
            $this->imageKey = rand();
        }

        if ($property === "viewModal") {
            $this->resetValidation('excel_file');
        }
    }

    public function dataEvent()
    {
        $this->excel_file = null;
        $this->resetValidation('excel_file');
        $this->buttonState = true;
    }

    public function saveData()
    {
        $this->buttonState = false;
        $this->validate();
        $this->buttonState = true;

        try {
            Excel::import(new DataImport($this->project->id, $this->rate), $this->excel_file);
            $this->project->data_uploaded = 1;
            $this->project->save();
            $this->dispatch('upload-data-message');
            session()->flash('load-excel-data', 'Load data successfully');
            $this->closeModal();
        } catch (Exception $e) {
            $this->addError('excel_file', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.save-projects-data');
    }

    public function openModal()
    {
        $this->viewModal = true;
    }

    public function closeModal()
    {
        $this->viewModal = false;
        $this->reset();
        $this->imageKey = rand();
    }
}
