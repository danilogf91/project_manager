<?php

namespace App\Livewire;

use App\Models\Data;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Rule;

class EditData extends Component
{
    public $search = '';
    public $openModal = false;
    public $data;
    public $values;
    public $projectId;
    public $rateEuros = 1;

    #[Rule('required|string|max:255')]
    public $area;

    #[Rule('required|string|max:255')]
    public $group_1;

    #[Rule('required|string|max:255')]
    public $group_2;

    #[Rule('required|string|max:255')]
    public $general_classification;

    #[Rule('required|string|max:255')]
    public $item_type;

    #[Rule('required|numeric|min:0')]
    public $qty;

    #[Rule('required|numeric|min:0')]
    public $unit_price;

    public $global_price;

    #[Rule('required|string|max:255')]
    public $stage;

    #[Rule('required|numeric|min:0')]
    public $real_value;

    #[Rule('required|numeric|min:0')]
    public $committed;

    #[Rule('required|numeric|between:0,100')]
    public $percentage;

    #[Rule('nullable|string|max:500')]
    public $code;

    #[Rule('nullable|string|max:255')]
    public $order_no;

    #[Rule('nullable|string|max:255')]
    public $input_num;

    #[Rule('nullable|string|max:2000')]
    public $observations;

    #[Rule('nullable|string|max:2000')]
    public $description;

    public function mount(Data $data)
    {
        $this->values = $data;
        $this->projectId = $data->id;
        $this->area = $data->area;
        $this->group_1 = $data->group_1;
        $this->group_2 = $data->group_2;
        $this->general_classification = $data->general_classification;
        $this->item_type = $data->item_type;
        $this->qty = $data->qty;
        $this->unit_price = $data->unit_price;
        $this->global_price = $data->global_price;
        $this->stage = $data->stage;
        $this->real_value = $data->real_value;
        $this->committed = $data->committed;
        $this->percentage = $data->percentage;
        $this->code = $data->code;
        $this->order_no = $data->order_no;
        $this->input_num = $data->input_num;
        $this->description = $data->description;
        $this->observations = $data->observations;
        $id = $this->projectId;

        $this->rateEuros = Project::whereHas('data', function ($query) use ($id) {
            $query->where('id', $id);
        })->value('rate');
    }

    public function update(Data $projectData)
    {
        $this->validate();
        $projectData->area = $this->area;
        $projectData->group_1 = $this->group_1;
        $projectData->group_2 = $this->group_2;
        $projectData->general_classification = $this->general_classification;
        $projectData->item_type = $this->item_type;
        $projectData->qty = $this->qty;
        $projectData->unit_price = $this->unit_price;
        $projectData->global_price = ($this->qty * $this->unit_price);
        $projectData->stage = $this->stage;
        $projectData->real_value = $this->real_value;
        $projectData->committed = $this->committed;
        $projectData->percentage = $this->percentage;
        $projectData->executed_dollars = ($this->percentage * ($this->qty * $this->unit_price) / 100);
        $projectData->executed_euros = ($this->percentage * ($this->qty * $this->unit_price * $this->rateEuros) / 100);
        $projectData->code = $this->code;
        $projectData->order_no = $this->order_no;
        $projectData->input_num = $this->input_num;
        $projectData->description = $this->description;
        $projectData->observations = $this->observations;
        $projectData->save();
        $this->openModal = false;
        $this->dispatch('edit-data');
    }

    public function render()
    {
        return view('livewire.edit-data');
    }
}
