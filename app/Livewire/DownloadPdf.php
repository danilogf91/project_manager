<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DownloadPdf extends Component
{
    public $pdaPath = "PDA_files";
    public $fileName;
    public $id;

    public function mount(Project $project)
    {
        $this->id = $project->id;
        $this->fileName = $project->file_name;
    }

    public function render()
    {
        return view('livewire.download-pdf');
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
