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

    #[Rule('required|mimes:csv,xlsx')]
    public $excel_file;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->id = $project->id;
        $this->rate = $project->rate;
    }

    public function saveData()
    {
        $this->validate();

        try {
            Excel::import(new DataImport($this->project->id, $this->rate), $this->excel_file);
            $this->project->data_uploaded = 1;
            $this->project->save();
            $this->dispatch('upload-data');
            $this->closeModal();
            // Resto del código si la importación fue exitosa
        } catch (Exception $e) {
            // Manejar el error y notificar al usuario
            $this->addError('excel_file', $e->getMessage());
        }
    }
    // public function saveData()
    // {
    //     $this->validate();

    //     $importErrors = [];

    //     try {
    //         Excel::import(new DataImport($this->project->id, $this->rate), $this->excel_file);
    //         $this->project->data_uploaded = 1;
    //         $this->project->save();
    //         $this->dispatch('upload-data');
    //         $this->closeModal();
    //         // Resto del código si la importación fue exitosa
    //     } catch (Exception $e) {
    //         // Almacena cada error en un array
    //         $importErrors[] = $e->getMessage();
    //     }

    //     // Si se capturaron errores, agrega todos los errores al componente
    //     if (!empty($importErrors)) {
    //         $this->addError('import_errors', $importErrors);
    //     }
    // }


    public function render()
    {

        if (!$this->viewModal) {
            $this->closeModal();
        }

        return view('livewire.save-projects-data');
    }

    public function openModal()
    {
        $this->viewModal = true;
    }

    public function closeModal()
    {
        $this->viewModal = false;
        $this->excel_file = null;
        $this->dispatch('closeModal');
    }
}
