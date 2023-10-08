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

class TestChart extends Component
{
    public $firstRun = true;

    public $showDataLabels = true;
    public $types = [
        'innovation',
        'efficiency_&_saving',
        'replacement_&_restructuring',
        'quality_&_hygiene',
        'health_&_safety',
        'environment',
        'maintenance',
        'capacity_increase'
    ];

    public $stageColors = [
        'Ejecutado' => '#f6ad55',
        'Por ejecutar' => '#36a2eb',
        'Test' => '#90cdf4',
    ];

    public $colors = [
        'innovation' => '#f6ad55',
        'efficiency_&_saving' => '#fc8181',
        'replacement_&_restructuring' => '#90cdf4',
        'quality_&_hygiene' => '#66DA26',
        'health_&_safety' => '#ffce56',
        'environment' => '#4bc0c0',
        'maintenance' => '#36a2eb',
        'capacity_increase' => '#9966FF',
    ];

    public $budgeted = 0;
    public $booked = 0;
    public $executed = 0;
    public $total = 0;
    public $projects = 0;
    public $projectsFinished = 0;
    public $projectsExecuted = 0;
    public $projectsPlaned = 0;
    public $projectsData = [];
    public $committedSum = 0;
    public $years = 0;
    public $yearSearch = 0;

    public function render()
    {
        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price');
        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'real_value');
        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'committed');
        $this->total = $this->budgeted;

        $projects = Project::whereIn('investments', $this->types)
            ->whereYear('start_date', $this->yearSearch)
            ->get();

        $projectsQuery = Project::whereIn('investments', $this->types);

        if (is_numeric($this->yearSearch)) {
            $projectsQuery->whereYear('start_date', $this->yearSearch);
        }

        $projects = $projectsQuery->get();

        $data = $this->dataGraph('general_classification');
        $area = $this->dataGraph('area');


        $projectsFinishedValue = $this->searchValue($this->yearSearch, 'finished');

        $projectsExecutedValue = $this->searchValue($this->yearSearch, 'execution');

        $projectsPlanedValue = $this->searchValue($this->yearSearch, 'planification');


        if (is_numeric($this->yearSearch)) {
            $projectTotalValue = Project::whereYear('start_date', $this->yearSearch)->count();
        } else {
            $projectTotalValue = Project::count();
        }

        $this->projects = $projectTotalValue;

        $projectsData = [
            'total' => $projectTotalValue,
            'execution' => $projectsExecutedValue,
            'planification' => $projectsPlanedValue,
            'finished' => $projectsFinishedValue
        ];

        $this->projectsData = $projectsData;

        arsort($this->projectsData);

        $columnChartModel2 = (new ColumnChartModel())
            ->setTitle('Projects')
            ->setAnimated($this->firstRun)
            ->withOnColumnClickEventName('onColumnClick')
            ->setLegendVisibility(true)
            ->setColumnWidth(80)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setDataLabelsEnabled($this->showDataLabels);

        foreach ($this->projectsData as $label => $value) {
            if ($value === 0) {
            } else {
                $columnChartModel2->addColumn($label, $value, $this->generateColor());
            }
        }

        $columnChartModel = $projects
            ->groupBy('investments')
            ->map(function ($data, $type) {
                return [
                    'type' => $type,
                    'count' => $data->count(),
                ];
            })
            ->sortByDesc('count')
            ->reduce(
                function (ColumnChartModel $columnChartModel, $data) {
                    $type = $data['type'];
                    $value = $data['count'];

                    return $columnChartModel->addColumn($type, $value, $this->colors[$type]);
                },
                (new ColumnChartModel())
                    ->setTitle('# de Projects')
                    ->setAnimated($this->firstRun)
                    ->withOnColumnClickEventName('onColumnClick')
                    ->setLegendVisibility(true)
                    ->setColumnWidth(80)
                    ->withGrid()
                    ->setHorizontal(true)
                    ->withDataLabels()
                    ->withLegend()
                    ->setDataLabelsEnabled($this->showDataLabels)
            );

        $pieChartModel = $data
            ->sortByDesc('total')
            ->reduce(
                function (PieChartModel $pieChartModel, $data) {
                    $type = $data->general_classification;
                    $value = $data->total;

                    return $pieChartModel->addSlice($type, round($value, 2), $this->generateColor() ?? '#333');
                },
                (new PieChartModel())
                    ->setTitle('Projects by Investments')
                    ->setAnimated($this->firstRun)
                    ->setLegendVisibility(true)
                    ->withGrid()
                    ->withDataLabels()
                    ->withLegend()
                    ->setType('donut')
            );

        $radarChartModel = $area
            ->reduce(
                function (RadarChartModel $radarChartModel, $data) {
                    return $radarChartModel->addSeries($data->first()->area, $data->area, round($data->total, 2));
                },
                LivewireCharts::radarChartModel()
                    ->setAnimated($this->firstRun)
                    ->setTitle('Investments by Area')
            );


        return view('livewire.test-chart')
            ->with([
                'columnChartModel' => $columnChartModel,
                'pieChartModel' => $pieChartModel,
                'radarChartModel' => $radarChartModel,
                'columnChartModel2' => $columnChartModel2
            ]);
    }

    public function mount()
    {
        $this->yearSearch = date('Y');

        $uniqueYears = Project::where('data_uploaded', 1)
            ->distinct()
            ->get(['start_date'])
            ->pluck('start_date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y');
            })
            ->unique();

        $this->years = $uniqueYears->sortDesc();

        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price');
        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'real_value');
        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'committed');

        $this->total = $this->budgeted;

        $this->projectsFinished = $this->searchValue($this->yearSearch, 'finished');

        $this->projectsExecuted = $this->searchValue($this->yearSearch, 'execution');

        $this->projectsPlaned = $this->searchValue($this->yearSearch, 'planification');

        if (is_numeric($this->yearSearch)) {
            $this->projects = Project::whereYear('start_date', $this->yearSearch)->count();
        } else {
            $this->projects = Project::count();
        }

        $this->projectsData = [
            'total' => $this->projects,
            'execution' => $this->projectsExecuted,
            'planification' => $this->projectsPlaned,
            'finished' => $this->projectsFinished,
        ];

        arsort($this->projectsData);
    }

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

    public function searchFunction($yearSearch, $searchLabel, $item)
    {
        if (is_numeric($yearSearch)) {
            // Realiza la conversión y la consulta aquí
            $budgeted = round(Data::whereHas('project', function ($query) use ($yearSearch, $searchLabel) {
                $query->whereYear($searchLabel, intval($yearSearch))
                    ->where('data_uploaded', 1);
            })->sum($item), 2);
        } else {
            // Realiza la consulta sin la condición de año aquí
            $budgeted = round(Data::whereHas('project', function ($query) {
                $query->where('data_uploaded', 1);
            })->sum($item), 2);
        }
        return $budgeted;
    }

    public function searchValue($search, $label)
    {
        if (is_numeric($search)) {
            $value = Project::where('state', $label)
                ->whereYear('start_date', intval($search))
                ->count();
        } else {
            $value = Project::where('state', $label)
                ->count();
        }
        return $value;
    }

    public function dataGraph($label)
    {
        $dataQuery = Data::select($label, DB::raw('SUM(global_price) as total'))
            ->whereHas('project', function ($query) {
                $query->where('data_uploaded', 1);
            })
            ->groupBy($label);

        if (is_numeric($this->yearSearch)) {
            $dataQuery->whereHas('project', function ($query) {
                $query->whereYear('start_date', intval($this->yearSearch));
            });
        }
        return $dataQuery->get();
    }
}
