<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Exception;

class SavePdf extends Component
{
    use WithFileUploads;
    public $project;
    public $viewModal;
    public $id;
    public $rate;
    public $buttonState = true;
    public $imageKey;
    public $pdaPath = "PDA_files";

    #[Rule('required|mimes:pdf')]
    public $pda_file;

    public function render()
    {
        return view('livewire.save-pdf');
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->id = $project->id;
    }

    public function updated($property, $value)
    {
        if ($property === 'pda_file') {
            $this->validate();
            $this->buttonState = false;
        }

        if ($property === "viewModal" && !$value) {
            $this->pda_file = null;
            $this->imageKey = rand();
        }

        if ($property === "viewModal") {
            $this->resetValidation('pda_file');
        }
    }

    public function saveData(Project $project)
    {
        $this->buttonState = false;
        $this->validate();
        $this->buttonState = true;
        $projectData = Project::find($this->project->id);

        try {
            if ($this->pda_file) {
                $fileName = time() . '.' . $this->pda_file->extension();
                $this->pda_file->storeAs($this->pdaPath, $fileName);
                $projectData->upload_pda = 1;
                $projectData->file_name = $fileName;
                $projectData->save();
                $this->dispatch('update-projects-table');
                $this->dispatch('edit-projects-message');
            }
            $this->closeModal();
        } catch (Exception $e) {
            $this->addError('pda_file', $e->getMessage());
        }
    }

    public function dataEvent()
    {
        $this->pda_file = null;
        $this->resetValidation('pda_file');
        $this->buttonState = true;
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
