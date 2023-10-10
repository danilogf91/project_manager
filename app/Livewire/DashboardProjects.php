<?php

namespace App\Livewire;

use App\Models\Data;
use App\Models\Project;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardProjects extends Component
{
    use WithPagination;

    public $project;
    public $firstRun = true;
    public $total = 0;
    public $columnNames;
    public $searchData = 'area';
    public $investments = 'global_price';
    public $percentage = 0;
    public $committed = 0;
    public $real_value = 0;
    public $executed_dollars = 0;
    public $global_price = 0;

    public $budgeted = 0;
    public $booked = 0;
    public $executed = 0;

    public $testValueErase = [];

    public function generateColor()
    {
        // Genera tres valores hexadecimales aleatorios para los componentes rojo, verde y azul
        $rojo = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
        $verde = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
        $azul = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);

        // Combina los valores para obtener el color completo en formato hexadecimal
        $colorHexadecimal = "#$rojo$verde$azul";

        return $colorHexadecimal;
    }

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->total = round(Data::where('project_id', $this->project->id)->sum('global_price'), 2);
        $this->real_value = round(Data::where('project_id', $this->project->id)->sum('real_value'), 2);
        $this->committed = round(Data::where('project_id', $this->project->id)->sum('committed'), 2);
        $this->global_price = round(Data::where('project_id', $this->project->id)->sum('global_price'), 2);

        $this->budgeted = round(Data::where('project_id', $this->project->id)->sum('global_price'), 2);
        $this->booked = round(Data::where('project_id', $this->project->id)->sum('committed'), 2);
        $this->executed = round(Data::where('project_id', $this->project->id)->sum('executed_dollars'), 2);
        $this->executed_dollars = $this->executed;

        if ($this->global_price != 0) {
            $this->percentage = round($this->executed / $this->global_price * 100, 2);
        } else {
            $this->percentage = 0;
        }

        $dataModel = new Data();
        $this->columnNames = $dataModel->getColumnNames();
    }

    public function render()
    {
        $data = Project::find($this->project->id);
        $dataQuery = $data->data();
        $dataQuery->where('project_id', $this->project->id);

        $area = $dataQuery->select($this->searchData, DB::raw("SUM($this->investments) as total"))
            ->groupBy($this->searchData)
            ->get();

        $searchValue = $this->searchData;

        $area = $area->map(function ($item) use ($searchValue) {
            return [
                $searchValue => $item->$searchValue,
                'total' => floatval($item->total),
            ];
        });

        $area = $area->sortByDesc('total');

        $this->testValueErase = $area;

        $radarChartModel = LivewireCharts::radarChartModel()
            ->setTitle($this->searchData . " " . $this->investments)
            ->setAnimated($this->firstRun)
            ->withOnPointClickEvent('onPointClick')
            ->withGrid()
            // ->withDataLabels()
            ->withLegend()
            ->legendPositionTop();

        foreach ($this->testValueErase as $element) {
            if (is_numeric($element['total']) && !is_nan($element['total']) && $element['total'] > 0 && $element[$this->searchData] != null) {
                $radarChartModel->addSeries("Investment", $element[$this->searchData], $element['total']);
            }
        }

        $columnChartModel =
            (new ColumnChartModel())
            // ->addColumn('Total', $this->total, $this->generateColor())
            ->withOnColumnClickEventName('onColumnClick')
            ->setTitle($this->searchData . " " . $this->investments)
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->legendPositionTop()
            ->setDataLabelsEnabled(true);

        foreach ($area as $element) {
            if (is_numeric($element['total']) && !is_nan($element['total']) && $element['total'] > 0 && $element[$this->searchData] != null) {
                $columnChartModel->addColumn($element[$this->searchData], $element['total'], $this->generateColor());
            }
        }

        $pieChartModel =
            (new PieChartModel())
            ->setTitle($this->searchData . " " . $this->investments)
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withOnSliceClickEvent('onSliceClick')
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setType('donut');


        foreach ($this->testValueErase as $element) {
            if (is_numeric($element['total']) && !is_nan($element['total']) && $element['total'] > 0 && $element[$this->searchData] != null) {
                $pieChartModel->addSlice($element[$this->searchData], $element['total'], $this->generateColor());
            }
        }


        $values = $dataQuery->select(
            $this->searchData,
            DB::raw('SUM(executed_dollars) as total1'),
            DB::raw('SUM(global_price) as total2'),
            DB::raw('SUM(committed) as total3'),
            DB::raw('SUM(real_value) as total4')
        )
            ->groupBy($this->searchData)
            ->get();

        $multiColumnChartModel = $values
            ->reduce(

                function ($multiColumnChartModel, $data) {
                    $type = $data->{$this->searchData};

                    // Verifica si $type es null y omite la iteración si es el caso
                    if ($type === null) {
                        return $multiColumnChartModel;
                    }

                    $value1 = $data->total1;
                    $value2 = $data->total2;
                    $value3 = $data->total3;
                    $value4 = $data->total4;

                    return $multiColumnChartModel
                        ->addSeriesColumn($type, 'Budgeted', $value2)
                        ->addSeriesColumn($type, 'Executed', $value1)
                        ->addSeriesColumn($type, 'Committed', $value3)
                        ->addSeriesColumn($type, 'Real', $value4);
                },
                LivewireCharts::multiColumnChartModel()
                    ->setAnimated($this->firstRun)
                    ->withOnColumnClickEventName('onColumnClick')
                    ->setTitle('Comparison')
                    ->stacked()
                    ->withGrid()
                    ->withDataLabels()
                    ->withLegend()
                    ->legendPositionTop()
            );

        return view('livewire.dashboard-projects')
            ->with([
                'radarChartModel' => $radarChartModel,
                'columnChartModel' => $columnChartModel,
                'pieChartModel' => $pieChartModel,
                'multiColumnChartModel' => $multiColumnChartModel,
            ]);
    }

    public function formatText($texto)
    {
        // Elimina guiones bajos y convierte el texto en un array de palabras
        $palabras = explode('_', $texto);

        // Convierte la primera letra de cada palabra en mayúscula
        $palabras = array_map('ucfirst', $palabras);

        // Une las palabras nuevamente en un solo string
        $textoTransformado = implode(' ', $palabras);

        return $textoTransformado;
    }
}
