<?php

namespace App\Livewire;

use App\Models\Project;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteOrEditPdf extends Component
{
    public $openModal = false;
    public $project;
    public $id;
    public $pdaPath = "PDA_files";

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->id = $project->id;
    }

    public function delete()
    {
        // Asegúrate de que el proyecto existe
        $projectData = Project::find($this->id);

        if ($projectData) {
            try {
                // Obtén el nombre del archivo PDF del proyecto
                $fileName = $projectData->file_name;

                if ($fileName) {
                    // Elimina el archivo PDF del almacenamiento
                    $filePath = $this->pdaPath . '/' . $projectData->file_name;
                    Storage::delete($filePath);

                    // Actualiza la base de datos para indicar que el archivo se ha eliminado
                    $projectData->upload_pda = 0;
                    $projectData->file_name = null;
                    $projectData->save();

                    // Despacha un evento para actualizar la tabla de proyectos, si es necesario
                    $this->dispatch('update-projects-table');
                    $this->dispatch('edit-projects-message');

                    // Opcionalmente, puedes mostrar un mensaje de éxito o realizar otras acciones después de eliminar el archivo.
                    session()->flash('success', 'El archivo PDF se ha eliminado con éxito.');
                }
            } catch (Exception $e) {
                // En caso de error al eliminar el archivo, puedes manejar la excepción aquí.
                session()->flash('error', 'Hubo un error al eliminar el archivo PDF.');
            }
        } else {
            session()->flash('error', 'No se encontró el proyecto.');
        }

        // Cierra el modal o realiza otras acciones después de eliminar el archivo
        $this->openModal =  false;
    }
    public function render()
    {
        return view('livewire.delete-or-edit-pdf');
    }

    public function downloadPDA()
    {
        $projectData = Project::find($this->id);

        $filePath = $this->pdaPath . '/' . $projectData->file_name;

        if (Storage::disk('local')->exists($filePath)) {
            return Storage::download($this->pdaPath . '/' . $projectData->file_name, "PDA_file.pdf");
        }
    }
}
