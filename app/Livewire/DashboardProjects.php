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

    public $resumeColors = [
        'Budgeted' => '#5BCA5A',
        'Executed' => '#CC5555',
        'Booked' => '#FFA500',
        'Real' => '#800080',
    ];

    public $resume = [
        "Budgeted",
        "Executed",
        "Booked",
        "Real",
    ];

    public $resumePie = [
        'Real',
        'Rest'
    ];

    public $resumePieColors = [
        'Real' => '#5BCA5A',
        'Rest' => '#CC5555',
    ];

    public $project;
    public $firstRun = true;
    public $total = 0;
    public $columnNames;
    public $searchData = 'area';
    public $investments = 'global_price';
    public $percentage = 0;
    public $real_value = 0;
    public $rateValue = 1;

    public $rateConvertion = 1;

    public $dollarOrEuro = "euro";

    public $budgeted = 0;
    public $booked = 0;
    public $executed = 0;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->total = $this->getValueBySearch('global_price_euros');
        $this->real_value = $this->getValueBySearch('real_value_euros');
        $this->booked = $this->getValueBySearch('booked_euros');
        $this->executed = $this->getValueBySearch('executed_euros');
        $this->budgeted = $this->total;

        if ($this->total != 0) {
            $this->percentage = round($this->executed / $this->total * 100, 2);
        } else {
            $this->percentage = 0;
        }

        $dataModel = new Data();
        $this->columnNames = $dataModel->getColumnNames();

        $this->rateValue = 1;
        $this->rateConvertion = 1;
    }

    public function updated($property, $value)
    {
        if ($property === "dollarOrEuro" && $value === "euro") {
            $this->rateConvertion = 1;
        } else {
            $this->rateConvertion = (float)$this->rateValue;
        }

        $this->total = $this->getValueBySearch('global_price_euros');
        $this->real_value = $this->getValueBySearch('real_value_euros');
        $this->booked = $this->getValueBySearch('booked_euros');
        $this->executed = $this->getValueBySearch('executed_euros');
        $this->budgeted = $this->total;

        if ($this->total != 0) {
            $this->percentage = round($this->executed / $this->total * 100, 2);
        } else {
            $this->percentage = 0;
        }
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

        $title = "Resume_" . (($this->dollarOrEuro === "euro") ? "€" : "$");

        return view('livewire.dashboard-projects')
            ->with([
                'radarChartModel' => $this->radarDataGraph($area),
                'columnChartModel' => $this->columnDataGraph($area, "Clasification_for_" . $this->searchData),
                'resumeGraph' => $this->columnDataGraphTwo($this->createResumeGraph(), $title),
                'resumePercentageGraph' => $this->columnDataGraphTwo($this->createResumeGraph("%"), "Resume %"),
                'pieChartModel' => $this->pieDataGraph($area, $this->rateConvertion),
                'pieChartModelResume' => $this->pieDataGraphTwo($this->createResumePieGraph(), $this->rateConvertion, "budgeted - real"),
                'pieChartModelResumeTwo' => $this->pieDataGraphTwo($this->createResumePieGraphTwo(), $this->rateConvertion, "budgeted - booked"),
                'multiColumnChartModel' => $this->multicolumnDataGraph($dataQuery),
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

    public function getValueBySearch($value)
    {
        $value = Data::where('project_id', $this->project->id)->sum($value);

        if ($this->dollarOrEuro === "dollar") {
            return round($value * $this->rateConvertion, 2);
        } else {
            return round($value, 2);
        }
    }

    public function multicolumnDataGraph($dataQuery)
    {
        $values = $dataQuery->select(
            $this->searchData,
            DB::raw('SUM(executed_euros) as executedValue'),
            DB::raw('SUM(global_price_euros) as budgetedValue'),
            DB::raw('SUM(booked_euros) as bookedValue'),
            DB::raw('SUM(real_value_euros) as realValue')
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

                    $executedValue = $this->convertAndUpdate($data->executedValue);
                    $budgetedValue = $this->convertAndUpdate($data->budgetedValue);
                    $bookedValue = $this->convertAndUpdate($data->bookedValue);
                    $realValue = $this->convertAndUpdate($data->realValue);

                    return $multiColumnChartModel
                        ->addSeriesColumn($type, 'Budgeted', $budgetedValue)
                        ->addSeriesColumn($type, 'Executed', $executedValue)
                        ->addSeriesColumn($type, 'Booked', $bookedValue)
                        ->addSeriesColumn($type, 'Real', $realValue);
                },
                LivewireCharts::multiColumnChartModel()
                    ->setAnimated($this->firstRun)
                    ->withOnColumnClickEventName('onColumnClick')
                    // ->setTitle('Comparison')
                    ->setTitle("Resume for " . $this->textTransform($this->searchData))
                    ->stacked()
                    ->withGrid()
                    ->withDataLabels()
                    ->withLegend()
                    ->legendPositionTop()
            );

        return $multiColumnChartModel;
    }

    public function radarDataGraph($data)
    {
        $radarChartModel = LivewireCharts::radarChartModel()
            ->setTitle($this->textTransform($this->searchData) . " -> " . $this->textTransform($this->investments))
            ->setAnimated($this->firstRun)
            ->withOnPointClickEvent('onPointClick')
            ->withGrid()
            // ->withDataLabels()
            ->withLegend()
            ->legendPositionTop();

        foreach ($data as $element) {
            if ($this->validateNumber($element['total']) && $element[$this->searchData] != null) {
                $radarChartModel->addSeries("Investment", $element[$this->searchData], $this->convertAndUpdate($element['total']));
            }
        }
        return $radarChartModel;
    }

    public function pieDataGraph($data, $rate)
    {
        $pieChartModel =
            (new PieChartModel())
            // ->setTitle($this->searchData . " " . $this->investments)
            ->setTitle($this->textTransform($this->searchData) . " -> " . $this->textTransform($this->investments))
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withOnSliceClickEvent('onSliceClick')
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setType('donut');

        foreach ($data as $element) {
            if ($this->validateNumber($element['total']) && $element[$this->searchData] != null) {
                $pieChartModel->addSlice($element[$this->searchData], round($element['total'] * $rate, 2), $this->generateColor());
            }
        }
        return $pieChartModel;
    }

    public function pieDataGraphTwo($data, $rate, $title)
    {
        $pieChartModel =
            (new PieChartModel())
            // ->setTitle($this->searchData . " " . $this->investments)
            ->setTitle($title)
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withOnSliceClickEvent('onSliceClick')
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->setType('donut');

        foreach ($data as $element) {
            if ($this->validateNumber($element['total']) && $element['label'] != null) {
                $pieChartModel->addSlice($element['label'], round($element['total'] * $rate, 2), $this->resumePieColors[$element['label']]);
            }
        }
        return $pieChartModel;
    }

    public function columnDataGraph($data, $title)
    {
        $columnChartModel =
            (new ColumnChartModel())
            // ->addColumn('Total', $this->total, $this->generateColor())
            ->withOnColumnClickEventName('onColumnClick')
            // ->setTitle($this->searchData . " " . $this->investments)
            // ->setTitle("Clasification for " . $this->textTransform($this->searchData))
            ->setTitle($this->textTransform($title))
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->legendPositionTop()
            ->setDataLabelsEnabled(true);

        foreach ($data as $element) {
            if ($this->validateNumber($element['total']) && $element[$this->searchData] != null) {
                $columnChartModel->addColumn($element[$this->searchData], round((float)$element['total'], 2), $this->generateColor());
            }
        }

        return $columnChartModel;
    }

    public function columnDataGraphTwo($data, $title)
    {
        $columnChartModel =
            (new ColumnChartModel())
            ->withOnColumnClickEventName('onColumnClick')
            ->setTitle($this->textTransform($title))
            ->setAnimated($this->firstRun)
            ->setLegendVisibility(true)
            ->withGrid()
            ->withDataLabels()
            ->withLegend()
            ->legendPositionTop()
            ->setDataLabelsEnabled(true);

        foreach ($data as $element) {
            if ($this->validateNumber($element['total']) && $element['label'] != null) {
                $columnChartModel->addColumn($element['label'], round((float)$element['total'], 2), $this->resumeColors[$element['label']]);
            }
        }

        return $columnChartModel;
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

    public function validateNumber($number)
    {
        return (is_numeric($number) && !is_nan($number) && $number > 0);
    }

    public function convertAndUpdate($value)
    {
        return round(((float)$value * $this->rateConvertion), 2);
    }

    public function textTransform($cadena)
    {
        $palabras = explode('_', $cadena);
        $palabrasCapitalizadas = array_map('ucfirst', $palabras);
        $resultado = implode(' ', $palabrasCapitalizadas);
        return $resultado;
    }

    public function createResumeGraph($percentage = null)
    {
        if (!$percentage) {
            $conversion = 1;
        } else {
            if (($this->total > 0)) {
                $conversion = 100 / $this->total;
            } else {
                $conversion = 1;
            }
        }

        return [
            [
                "label" => "Budgeted",
                "total" => round($this->budgeted * $conversion, 2)
            ],
            [
                "label" => "Executed",
                "total" =>  round($this->executed * $conversion, 2)
            ],
            [
                "label" => "Booked",
                "total" => round($this->booked * $conversion, 2)
            ],
            [
                "label" => "Real",
                "total" => round($this->real_value * $conversion, 2)
            ]
        ];
    }

    public function createResumePieGraph()
    {
        if ($this->budgeted > 0) {
            $conversion = 1;
        } else {
            $conversion = 100 / $this->budgeted;
        }

        return [
            [
                "label" => "Real",
                "total" => round($this->real_value * $conversion, 2)
            ],
            [
                "label" => "Rest",
                "total" => round(($this->budgeted - $this->real_value) * $conversion, 2)
            ],
        ];
    }

    public function createResumePieGraphTwo()
    {
        if ($this->budgeted > 0) {
            $conversion = 1;
        } else {
            $conversion = 100 / $this->budgeted;
        }

        return [
            [
                "label" => "Real",
                "total" => round($this->real_value * $conversion, 2)
            ],
            [
                "label" => "Rest",
                "total" => round(($this->budgeted - $this->booked) * $conversion, 2)
            ],
        ];
    }
}
