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
    public $dollarOrEuro = "euro";
    public $stateProject = [];
    public $typeOfProject = [];
    public $typeOfProjectSearch;

    public $showSpinner = true;

    public $exchangeRate = 1;
    public $rateValue = 1;

    public $totalsByInvestment = [];
    public $totalsByState = [];


    public function updated($property, $value)
    {

        // dd($property, $value);

        if ($property === "typeOfProjectSearch") {
            $this->stateProject = Project::distinct()
                ->where('data_uploaded', 1)
                ->when($value !== 'all', function ($query) use ($value) {
                    return $query->where('projects.classification_of_investments', $value);
                })
                ->when($this->yearSearch !== 'all', function ($query) {
                    return $query->whereRaw('YEAR(projects.start_date) = ?', [$this->yearSearch]);
                })
                ->pluck('state');
            // dd($this->stateProject);
        }

        if ($property === "stateSearch") {
            $this->typeOfProject = Project::distinct()
                ->where('data_uploaded', 1)
                ->when($value !== 'all', function ($query) use ($value) {
                    return $query->where('projects.state', $value);
                })
                ->when($this->yearSearch !== 'all', function ($query) {
                    return $query->whereRaw('YEAR(projects.start_date) = ?', [$this->yearSearch]);
                })
                ->pluck('classification_of_investments');

            // dd($this->typeOfProject);
        }

        if ($property === "yearSearch") {
            $this->stateProject = Project::distinct()
                ->where('data_uploaded', 1)
                ->when($value !== 'all', function ($query) use ($value) {
                    return $query->whereRaw('YEAR(projects.start_date) = ?', [$value]);
                })
                ->pluck('state');

            $this->typeOfProject = Project::distinct()
                ->where('data_uploaded', 1)
                ->when($value !== 'all', function ($query) use ($value) {
                    return $query->whereRaw('YEAR(projects.start_date) = ?', [$value]);
                })
                ->pluck('classification_of_investments');
        }

        if ($property === "dollarOrEuro" && $value === "dollar") {
            $this->exchangeRate = (float)$this->rateValue;
        } else {
            $this->exchangeRate = 1;
        }
    }

    public function render()
    {
        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price_euros');
        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'booked_euros');
        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'executed_euros');
        $this->real = $this->searchFunction($this->yearSearch, 'start_date', 'real_value_euros');
        $this->total = $this->budgeted;

        $projectsGraph = $this->getProjects();

        $data = $this->dataGraph('general_classification');

        $area = $this->getAreaData();

        $this->projectsData = $this->getProjectsData();

        arsort($this->projectsData);

        $this->totalsByInvestment = $this->getTotalBySearchParams('investments', $this->yearSearch, $this->stateSearch, $this->typeOfProjectSearch);
        arsort($this->totalsByInvestment);
        $this->totalsByState = $this->getTotalBySearchParams('state', $this->yearSearch, $this->stateSearch, $this->typeOfProjectSearch);
        arsort($this->totalsByState);

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

                    return $numProjectsGraph->addColumn($type, round((float)$value, 2), $this->colors[$type]);
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
            if ($value != 0) {
                $numProjectsGraphValues->addColumn($label, round((float)$value * $this->exchangeRate, 2), $this->colors[$label]);
            }
        }

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
                $numProjectsPieGraphByInvestmentsValues->addSlice($label, round((float)$value * $this->exchangeRate, 2), $this->stateColors[$label] ?? '#333');
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
            if ($value === 0 || $label === "Total") {
            } else {
                $numProjectsPieGraphByInvestments->addSlice($label, round((float)$value, 2), $this->stateColors[$label] ?? '#333');
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
                $numProjectsByStateGraphValues->addColumn($label, round((float)$value * $this->exchangeRate, 2), $this->stateColors[$label]);
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
                // 'pieChartModel' => $pieChartModel,
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
        // $exchangeRateController = new ExchangeRate();

        $this->stateProject = $this->getDiferentValues('state');
        $this->typeOfProject = $this->getDiferentValues('classification_of_investments');

        $this->exchangeRate = 1;

        $this->yearSearch = date('Y');
        $this->stateSearch = "all";
        $this->typeOfProjectSearch = "all";
        $this->dollarOrEuro = "euro";

        $this->years = $this->getYears();

        $this->budgeted = $this->searchFunction($this->yearSearch, 'start_date', 'global_price_euros');
        $this->booked = $this->searchFunction($this->yearSearch, 'start_date', 'booked_euros');
        $this->executed = $this->searchFunction($this->yearSearch, 'start_date', 'executed_euros');
        $this->real = $this->searchFunction($this->yearSearch, 'start_date', 'real_value_euros');

        $this->total = $this->budgeted;

        $this->projectsFinished = $this->searchValue($this->yearSearch, 'finished', $this->typeOfProjectSearch);

        $this->projectsExecuted = $this->searchValue($this->yearSearch, 'execution', $this->typeOfProjectSearch);

        $this->projectsPlaned = $this->searchValue($this->yearSearch, 'planification', $this->typeOfProjectSearch);

        $this->projects =  0;

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

    public function searchFunction($yearSearch, $searchLabel, $item)
    {
        $value = Data::whereHas('project', function ($query) use ($yearSearch, $searchLabel) {
            $query->where('data_uploaded', 1);

            if (is_numeric($yearSearch)) {
                $query->whereYear($searchLabel, intval($yearSearch));
            }

            if ($this->stateSearch != "all") {
                $query->where('state', $this->stateSearch);
            }

            if ($this->typeOfProjectSearch != "all") {
                $query->where('classification_of_investments', $this->typeOfProjectSearch);
            }
        })->sum($item);

        return round((float)$value / (float)$this->exchangeRate, 2);
    }

    public function searchValue($search, $label, $classification)
    {
        $query = Project::where('state', $label);

        if ($classification != 'all') {
            $query->where('classification_of_investments', $classification);
        }

        if (is_numeric($search)) {
            $value = $query->whereYear('start_date', intval($search))->count();
        } else {
            $value = $query->count();
        }

        return $value;
    }


    public function dataGraph($label)
    {
        $dataQuery = Data::select($label, DB::raw('SUM(global_price_euros) as total'))
            ->whereHas('project', function ($query) {
                $query->where('data_uploaded', 1);
                if (is_numeric($this->yearSearch)) {
                    $query->whereYear('start_date', intval($this->yearSearch));
                }
            })
            ->groupBy($label);

        return $dataQuery->get();
    }

    public function getTotalBySearchParams($search, $yearSearch, $campo, $campo1)
    {
        return Project::select($search)
            ->addSelect(DB::raw('SUM(data.global_price_euros) as total'))
            ->leftJoin('data', 'projects.id', '=', 'data.project_id')
            ->when(is_numeric($yearSearch), function ($query) use ($yearSearch) {
                $query->whereYear('start_date', $yearSearch);
            })
            ->when($campo !== 'all', function ($query) use ($campo) {
                $query->where('state', $campo);
            })
            ->when($campo1 !== 'all', function ($query) use ($campo1) {
                $query->where('classification_of_investments', $campo1);
            })
            ->groupBy($search)
            ->pluck('total', $search)
            ->toArray();
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
        return Project::distinct()
            ->where('data_uploaded', 1)
            ->pluck($column);
    }

    public function getProjects()
    {
        return Project::whereIn('investments', $this->types)
            ->when($this->yearSearch !== 'all', function ($query) {
                return $query->whereYear('start_date', $this->yearSearch);
            })
            ->when($this->stateSearch !== 'all', function ($query) {
                return $query->where('state', $this->stateSearch);
            })->when($this->typeOfProjectSearch !== 'all', function ($query) {
                return $query->where('classification_of_investments', $this->typeOfProjectSearch);
            })
            ->get();
    }

    public function getNumTotalOfProjects()
    {
        return Project::when(is_numeric($this->yearSearch), function ($query) {
            $query->whereYear('start_date', $this->yearSearch);
        })->when($this->stateSearch !== 'all', function ($query) {
            $query->where('state', $this->stateSearch);
        })->when($this->typeOfProjectSearch !== 'all', function ($query) {
            $query->where('classification_of_investments', $this->typeOfProjectSearch);
        })->count();
    }

    public function getProjectsData()
    {
        $projectsData = [];
        $this->projects = $this->getNumTotalOfProjects();
        $projectsData['Total'] = $this->projects;

        if ($this->stateSearch === "all") {
            $projectsData['Execution'] = $this->searchValue($this->yearSearch, 'Execution', $this->typeOfProjectSearch);
            $projectsData['Planification'] = $this->searchValue($this->yearSearch, 'Planification', $this->typeOfProjectSearch);
            $projectsData['Finished'] = $this->searchValue($this->yearSearch, 'Finished', $this->typeOfProjectSearch);
        } else {
            $projectsData[$this->stateSearch] = $this->searchValue($this->yearSearch, $this->stateSearch, $this->typeOfProjectSearch);
        }

        return $projectsData;
    }


    public function getAreaData()
    {
        return Project::select('data.area')
            ->addSelect(DB::raw('SUM(data.global_price_euros) as total'))
            ->leftJoin('data', 'projects.id', '=', 'data.project_id')
            ->where('projects.data_uploaded', 1) // Agregar esta condición
            ->when($this->stateSearch !== 'all', function ($query) {
                return $query->where('projects.state', $this->stateSearch);
            })
            ->when($this->typeOfProjectSearch !== 'all', function ($query) {
                return $query->where('projects.classification_of_investments', $this->typeOfProjectSearch);
            })
            ->groupBy('data.area')
            ->distinct()
            ->get();
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="fixed top-0 left-0 w-full h-full flex items-center justify-center bg-stone-200">
                    <div class="p-4 rounded">
                        <p class="text-3xl font-extrabold">Loading....</p>
                    </div>
                </div>
        HTML;
    }
}
