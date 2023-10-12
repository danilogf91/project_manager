<?php

namespace App\Livewire;

use App\Http\Controllers\ExchangeRate;
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
        'Innovation',
        'Efficiency & Saving',
        'Replacement & Restructuring',
        'Quality & Hygiene',
        'Health & Safety',
        'Environment',
        'Maintenance',
        'Capacity Increase'
    ];

    public $stateColors = [
        'Execution' => '#5BCA5A',
        'Finished' => '#CC5555',
        'Planification' => '#FFA500',
        'Total' => '#800080',
    ];

    public $states = [
        'Execution',
        'Finished',
        'Planification',
        'Total',
    ];

    public $colors = [
        'Innovation' => '#f6ad55',
        'Efficiency & Saving' => '#fc8181',
        'Replacement & Restructuring' => '#90cdf4',
        'Quality & Hygiene' => '#66DA26',
        'Health & Safety' => '#ffce56',
        'Environment' => '#4bc0c0',
        'Maintenance' => '#36a2eb',
        'Capacity Increase' => '#9966FF',
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

    public $showSpinner = true;

    public $exchangeRate = 1;

    public $totalsByInvestment = [];
    public $totalsByState = [];

    public function render()
    {
        if ($this->dollarOrEuro === "dollar") {
            $this->exchangeRate = 1;
        } else {
            $exchangeRateController = new ExchangeRate();
            $this->exchangeRate = $exchangeRateController->getExchangeRate();
        }

        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price', $this->stateSearch);
        $this->budgeted = round($this->budgeted * $this->exchangeRate, 2);

        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'booked', $this->stateSearch);
        $this->booked = round($this->booked * $this->exchangeRate, 2);

        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'executed_dollars', $this->stateSearch);
        $this->executed = round($this->executed * $this->exchangeRate, 2);

        $this->total = $this->budgeted;

        $projectsGraph = $this->getProjects();

        $data = $this->dataGraph('general_classification');

        $area = $this->getAreaData();


        $this->projectsData = $this->getProjectsData();

        arsort($this->projectsData);

        $this->totalsByInvestment = $this->getTotalBySearchParams('investments', $this->yearSearch, $this->stateSearch);
        arsort($this->totalsByInvestment);
        $this->totalsByState = $this->getTotalBySearchParams('state', $this->yearSearch, $this->stateSearch);
        arsort($this->totalsByState);
        $this->stateProject = $this->getDiferentValues('state');

        $numProjectsGraph = $projectsGraph
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
                    // ->setTitle('#')
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

        $numProjectsGraphValues = (new ColumnChartModel())
            // ->setTitle('Projects borrar')
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
                $numProjectsGraphValues->addColumn($label, round($value * $this->exchangeRate, 2), $this->colors[$label]);
            }
        }

        $pieChartModel = $data
            ->sortByDesc('Total')
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
            ->setTitle('Project status $')
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setType('donut');

        foreach ($this->totalsByState as $label => $value) {
            if ($value === 0) {
            } else {
                $numProjectsPieGraphByInvestmentsValues->addSlice($label, round($value * $this->exchangeRate, 2), $this->stateColors[$label] ?? '#333');
            }
        }

        $numProjectsPieGraphByInvestments = (new PieChartModel())
            ->setTitle('Project status %')
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

        $numProjectsByStateGraph = (new ColumnChartModel())
            ->setTitle('Project status #')
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

        $numProjectsByStateGraphValues = (new ColumnChartModel())
            ->setTitle('Project status $')
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
                $numProjectsByStateGraphValues->addColumn($label, round($value * $this->exchangeRate, 2), $this->stateColors[$label]);
            }
        }

        $areaGraphValues = $area
            ->reduce(
                function (RadarChartModel $areaGraphValues, $data) {
                    return $areaGraphValues->addSeries("Investment " . (($this->dollarOrEuro === "dollar") ? "$" : "€"), $data->area, round($data->total * $this->exchangeRate, 2));
                },
                LivewireCharts::radarChartModel()
                    ->setAnimated($this->firstRun)
                    ->setTitle('Area Clasification')
            );

        // dd($area);


        $investmentTypeGraphValues = LivewireCharts::radarChartModel()
            ->setAnimated(true)
            ->setTitle('Type of Investment');

        foreach ($this->totalsByInvestment as $label => $value) {
            if ($value === 0 || $label === "total") {
            } else {
                $investmentTypeGraphValues->addSeries("Investment " . (($this->dollarOrEuro === "dollar") ? "$" : "€"), $label, round($value * $this->exchangeRate, 2));
            }
        }

        $this->showSpinner =  false;

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
        $exchangeRateController = new ExchangeRate();

        $this->exchangeRate = $exchangeRateController->getExchangeRate();

        $this->yearSearch = date('Y');
        $this->stateSearch = "all";
        $this->dollarOrEuro = "dollar";

        $this->years = $this->getYears();

        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price', $this->stateSearch);
        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'real_value', $this->stateSearch);
        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'booked', $this->stateSearch);

        $this->total = $this->budgeted;

        $this->projectsFinished = $this->searchValue($this->yearSearch, 'finished');

        $this->projectsExecuted = $this->searchValue($this->yearSearch, 'execution');

        $this->projectsPlaned = $this->searchValue($this->yearSearch, 'planification');

        $this->projects =  50;

        $this->projectsData = [
            'Total' => $this->projects,
            'Execution' => $this->projectsExecuted,
            'Planification' => $this->projectsPlaned,
            'Finished' => $this->projectsFinished,
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

    public function searchFunction($yearSearch, $searchLabel, $item, $stateSearch = "all")
    {
        if (is_numeric($yearSearch)) {
            // Realiza la conversión y la consulta aquí
            $budgeted = round(Data::whereHas('project', function ($query) use ($yearSearch, $searchLabel, $stateSearch) {
                $query->whereYear($searchLabel, intval($yearSearch))
                    ->where('data_uploaded', 1);
                if ($stateSearch != "all") {
                    $query->where('state', $stateSearch);
                }
            })->sum($item), 2);
        } else {
            // Realiza la consulta sin la condición de año aquí
            $budgeted = round(Data::whereHas('project', function ($query) use ($stateSearch) {
                $query->where('data_uploaded', 1);
                if ($stateSearch != "all") {
                    $query->where('state', $stateSearch);
                }
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
            $projectTotalValue = Project::whereYear('start_date', $this->yearSearch)
                ->when($this->stateSearch !== 'all', function ($query) {
                    return $query->where('state', $this->stateSearch);
                })
                ->count();
        } else {
            $projectTotalValue = Project::when($this->stateSearch !== 'all', function ($query) {
                return $query->where('state', $this->stateSearch);
            })
                ->count();
        }

        return $projectTotalValue;
    }

    public function getProjectsData()
    {
        $projectsFinishedValue = $this->searchValue($this->yearSearch, 'Finished');
        $projectsExecutedValue = $this->searchValue($this->yearSearch, 'Execution');
        $projectsPlanedValue = $this->searchValue($this->yearSearch, 'Planification');

        $this->projects = $this->getNumTotalOfProjects();

        if ($this->stateSearch === "all") {
            $projectsData = [
                'Total' => $this->projects,
                'Execution' => $projectsExecutedValue,
                'Planification' => $projectsPlanedValue,
                'Finished' => $projectsFinishedValue
            ];
        } else if ($this->stateSearch === "Execution") {
            $projectsData = [
                'Execution' => $projectsExecutedValue,
            ];
        } else if ($this->stateSearch === "Planification") {
            $projectsData = [
                'Planification' => $projectsPlanedValue,
            ];
        } else if ($this->stateSearch === "Finished") {
            $projectsData = [
                'Finished' => $projectsFinishedValue
            ];
        }
        return $projectsData;
    }

    public function getAreaData()
    {
        return Project::select('data.area')
            ->addSelect(DB::raw('SUM(data.global_price) as total'))
            ->leftJoin('data', 'projects.id', '=', 'data.project_id')
            ->where('projects.data_uploaded', 1) // Agregar esta condición
            ->when($this->stateSearch !== 'all', function ($query) {
                return $query->where('projects.state', $this->stateSearch);
            })
            ->groupBy('data.area')
            ->distinct()
            ->get();
    }
}
