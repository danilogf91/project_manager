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

    public $stateColors = [
        'execution' => '#5BCA5A',
        'total' => '#800080',
        'planification' => '#FFA500',
        'finished' => '#CC5555',
    ];

    public $states = [
        'execution',
        'finished',
        'planification',
        'total',
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
    public $real = 0;
    public $total = 0;
    public $projects = 0;
    public $projectsFinished = 0;
    public $projectsExecuted = 0;
    public $projectsPlaned = 0;
    public $projectsData = [];
    public $committedSum = 0;
    public $years = 0;
    public $yearSearch = 0;
    public $stateSearch;
    public $dollarOrEuro = "dollar";
    public $stateProject = [];

    public $totalsByInvestment = [];
    public $totalsByState = [];

    public function render()
    {
        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price'); // OK
        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'committed');
        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'executed_dollars');
        $this->total = $this->budgeted;

        $projects = $this->getProjects();

        $data = $this->dataGraph('general_classification');

        $area = Project::select('data.area')
            ->addSelect(DB::raw('SUM(data.global_price) as total'))
            ->leftJoin('data', 'projects.id', '=', 'data.project_id')
            ->when($this->stateSearch !== 'all', function ($query) {
                return $query->where('projects.state', $this->stateSearch);
            })
            ->groupBy('data.area')
            ->distinct() // Aplicar el método distinct
            ->get();

        $this->projectsData = $this->getProjectsData();

        arsort($this->projectsData);

        $this->totalsByInvestment = $this->getTotalBySearchParams('investments', $this->yearSearch, $this->stateSearch);
        $this->totalsByState = $this->getTotalBySearchParams('state', $this->yearSearch, $this->stateSearch);
        $this->stateProject = $this->getDiferentValues('state');

        $numProjectsByStateGraph = (new ColumnChartModel())
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
                $numProjectsByStateGraph->addColumn($label, $value, $this->stateColors[$label]);
            }
        }

        $numProjectsGraphValues = (new ColumnChartModel())
            ->setTitle('Projects')
            ->setAnimated($this->firstRun)
            ->withOnColumnClickEventName('onColumnClick')
            ->setLegendVisibility(true)
            ->setColumnWidth(80)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setHorizontal(true)
            ->setDataLabelsEnabled($this->showDataLabels);

        foreach ($this->totalsByInvestment as $label => $value) {
            if ($value === 0) {
            } else {
                $numProjectsGraphValues->addColumn($label, round($value, 2), $this->colors[$label]);
            }
        }

        $numProjectsByStateGraphValues = (new ColumnChartModel())
            ->setTitle('Projects')
            ->setAnimated($this->firstRun)
            ->withOnColumnClickEventName('onColumnClick')
            ->setLegendVisibility(true)
            ->setColumnWidth(80)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setDataLabelsEnabled($this->showDataLabels);

        foreach ($this->totalsByState as $label => $value) {
            if ($value === 0) {
            } else {
                $numProjectsByStateGraphValues->addColumn($label, round($value, 2), $this->stateColors[$label]);
            }
        }

        $numProjectsGraph = $projects
            ->groupBy('investments')
            ->map(function ($data, $type) {
                return [
                    'type' => $type,
                    'count' => $data->count(),
                ];
            })
            ->sortByDesc('count')
            ->reduce(
                function (ColumnChartModel $numProjectsGraph, $data) {
                    $type = $data['type'];
                    $value = $data['count'];

                    return $numProjectsGraph->addColumn($type, round($value, 2), $this->colors[$type]);
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

        $numProjectsPieGraphByInvestmentsValues = (new PieChartModel())
            ->setTitle('Projects by Investments')
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setType('donut');

        foreach ($this->totalsByState as $label => $value) {
            if ($value === 0) {
            } else {
                $numProjectsPieGraphByInvestmentsValues->addSlice($label, round($value, 2), $this->stateColors[$label] ?? '#333');
            }
        }

        $numProjectsPieGraphByInvestments = (new PieChartModel())
            ->setTitle('# Projects by Investments')
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setType('donut');

        foreach ($this->projectsData as $label => $value) {
            if ($value === 0 || $label === "total") {
            } else {
                $numProjectsPieGraphByInvestments->addSlice($label, round($value, 2), $this->stateColors[$label] ?? '#333');
            }
        }

        $areaGraphValues = $area
            ->reduce(
                function (RadarChartModel $areaGraphValues, $data) {
                    return $areaGraphValues->addSeries("Investment $", $data->area, round($data->total, 2));
                },
                LivewireCharts::radarChartModel()
                    ->setAnimated($this->firstRun)
                    ->setTitle('Investments by Area')
            );


        $investmentTypeGraphValues = LivewireCharts::radarChartModel()
            ->setAnimated(true)
            ->setTitle('Type of Investment');

        foreach ($this->totalsByInvestment as $label => $value) {
            if ($value === 0 || $label === "total") {
            } else {
                $investmentTypeGraphValues->addSeries("Investment $", $label, round($value, 2));
            }
        }



        return view('livewire.test-chart')
            ->with([
                'numProjectsGraph' => $numProjectsGraph,
                'pieChartModel' => $pieChartModel,
                'numProjectsPieGraphByInvestmentsValues' => $numProjectsPieGraphByInvestmentsValues,
                'numProjectsPieGraphByInvestments' => $numProjectsPieGraphByInvestments,
                'areaGraphValues' => $areaGraphValues,
                'investmentTypeGraphValues' => $investmentTypeGraphValues,
                'numProjectsByStateGraph' => $numProjectsByStateGraph,
                'numProjectsGraphValues' => $numProjectsGraphValues,
                'numProjectsByStateGraphValues' => $numProjectsByStateGraphValues
            ]);
    }

    public function mount()
    {
        $this->yearSearch = date('Y');
        $this->stateSearch = "all";
        $this->dollarOrEuro = "dollar";

        $this->years = $this->getYears();

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



    public function getTotalBySearchParams($search, $yearSearch, $campo)
    {
        $query = Project::select($search)
            ->addSelect(DB::raw('SUM(data.global_price) as total'))
            ->leftJoin('data', 'projects.id', '=', 'data.project_id')
            ->groupBy($search);

        if (is_numeric($yearSearch)) {
            $query->whereYear('start_date', $yearSearch);
        }

        if ($campo !== 'all') {
            $query->where('state', $campo);
        }

        $projects = $query->pluck('total', $search)->toArray();

        return $projects;
    }



    public function getYears()
    {
        $uniqueYears = Project::where('data_uploaded', 1)
            ->distinct()
            ->get(['start_date'])
            ->pluck('start_date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y');
            })
            ->unique();

        return $uniqueYears->sortDesc();
    }

    public function getDiferentValues($column)
    {
        return Project::distinct()->pluck($column);
    }

    public function getProjects()
    {
        return Project::whereIn('investments', $this->types)
            ->when($this->yearSearch !== 'all', function ($query) {
                return $query->whereYear('start_date', $this->yearSearch);
            })
            ->when($this->stateSearch !== 'all', function ($query) {
                return $query->where('state', $this->stateSearch);
            })
            ->get();
    }

    public function getNumTotalOfProjects()
    {
        if (is_numeric($this->yearSearch)) {
            $projectTotalValue = Project::whereYear('start_date', $this->yearSearch)->count();
        } else {
            $projectTotalValue = Project::count();
        }

        return $projectTotalValue;
    }

    public function getProjectsData()
    {
        $projectsFinishedValue = $this->searchValue($this->yearSearch, 'finished');
        $projectsExecutedValue = $this->searchValue($this->yearSearch, 'execution');
        $projectsPlanedValue = $this->searchValue($this->yearSearch, 'planification');

        $this->projects = $this->getNumTotalOfProjects();

        if ($this->stateSearch === "all") {
            $projectsData = [
                'total' => $this->projects,
                'execution' => $projectsExecutedValue,
                'planification' => $projectsPlanedValue,
                'finished' => $projectsFinishedValue
            ];
        } else if ($this->stateSearch === "execution") {
            $projectsData = [
                'execution' => $projectsExecutedValue,
            ];
        } else if ($this->stateSearch === "planification") {
            $projectsData = [
                'planification' => $projectsPlanedValue,
            ];
        } else if ($this->stateSearch === "finished") {
            $projectsData = [
                'finished' => $projectsFinishedValue
            ];
        }
        return $projectsData;
    }
}
