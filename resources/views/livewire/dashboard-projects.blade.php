<div class="p-2">
    <div class="justify-center rounded flex flex-col md:flex-row gap-2">
        <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="searchData"
                name="searchData"
                class="text-center px-7 py-2 w-full border rounded"
            >
            @foreach($columnNames as $columnName)
            @php
                $transformedColumnName = $this->formatText($columnName);
            @endphp
            <option value="{{ $columnName }}">
                {{ $transformedColumnName }}
            </option>
            @endforeach

            </select>
        </div>

        <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="investments"
                name="investments"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="global_price_euros">
                    Global Price
                </option>
                <option value="real_value_euros">
                    Real value
                </option>
                <option value="booked_euros">
                    Booked
                </option>
                <option value="executed_euros">
                    Executed
                </option>
            </select>
        </div>

        <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="dollarOrEuro"
                name="dollarOrEuro"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="euro">
                    Euro €
                </option>
                <option value="dollar">
                    Dollar $
                </option>

            </select>
        </div>

        {{-- <div class="md:col-span-1 col-span-3">
            <input type="number" wire:model="rateValue" class="rounded text-center px-7 py-2 w-full border">
        </div> --}}
    </div>


    <div class="rounded flex flex-col md:flex-row gap-2 mt-2">
        {{-- <button wire:click="radarDataGraphSave">Descargar Gráfica</button> --}}

        <div class="w-full md:w-1/5 bg-white rounded">
            <div class="p-4 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Budgeted</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($budgeted, 0, ',', '.')}} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>

        <div class="w-full md:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-4 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Booked</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($booked, 0, ',', '.')}} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>
        {{-- PONER EN VERDE CUANDO SE HAYA DADO COMO TERMINADO EL PROYECTO --}}
        @if ($project->state === "finished")
            <div class="bg-green-400 w-full md:w-1/5 rounded mb-2 md:mb-0">
                <div class="p-4 text-center">
                    <span class="text-2xl font-extrabold">Complete</span>
                    <h3 class="text-xl font-semibold mt-2">{{ number_format($percentage, 2, ',', '.') }} %</h3>
                </div>
            </div>
        @else
            <div class="bg-white w-full md:w-1/5 rounded mb-2 md:mb-0">
                <div class="p-4 text-center">
                    <span class="text-2xl font-extrabold">Executed</span>
                    <h3 class="text-xl font-semibold mt-2">{{ number_format($percentage, 2, ',', '.') }} %</h3>
                </div>
            </div>
        @endif

        <div class="w-full md:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-4 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Executed</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($executed, 0, ',', '.') }} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>

        <div class="w-full md:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-4 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Real (SAP)</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($real_value, 0, ',', '.')}} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>
    </div>

    <div class="flex flex-col mt-2 md:flex-row gap-2">

         <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-column-chart
                key="{{ $columnChartModel->reactiveKey() }}"
                :column-chart-model="$columnChartModel"
            />
        </div>

        {{-- <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-column-chart
            id="my-unique-chart"
            key="{{ $multiColumnChartModel->reactiveKey() }}"
            :column-chart-model="$multiColumnChartModel"/>
            <button id="captureButton">Capturar y Descargar</button>
       </div> --}}

        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-column-chart
                id="my-unique-chart"
                key="{{ $multiColumnChartModel->reactiveKey() }}"
                :column-chart-model="$multiColumnChartModel"/>
            <button id="captureButton">Capturar y Descargar</button>
        </div>

    </div>

    <div class="flex flex-col mt-2 md:flex-row gap-2">

        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-column-chart
                key="{{ $resumePercentageGraph->reactiveKey() }}"
                :column-chart-model="$resumePercentageGraph"
            />
        </div>

        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
           <livewire:livewire-column-chart
               key="{{ $resumeGraph->reactiveKey() }}"
               :column-chart-model="$resumeGraph"
           />
       </div>
   </div>

    <div class="flex flex-col mt-2 md:flex-row gap-2">
        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-pie-chart
                key="{{ $pieChartModel->reactiveKey() }}"
                :pie-chart-model="$pieChartModel"
            />
        </div>

        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-pie-chart
                key="{{ $pieChartModelResume->reactiveKey() }}"
                :pie-chart-model="$pieChartModelResume"
            />
        </div>
    </div>

    <div class="flex flex-col mt-2 md:flex-row gap-2">
        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">

            <livewire:livewire-radar-chart
                key="{{ $radarChartModel->reactiveKey() }}"
                :radar-chart-model="$radarChartModel"
            />
        </div>

        <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-pie-chart
                key="{{ $pieChartModelResumeTwo->reactiveKey() }}"
                :pie-chart-model="$pieChartModelResumeTwo"
            />
        </div>

    </div>

</div>
{{--
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Agrega un controlador de clic al botón de captura
    document.getElementById('captureButton').addEventListener('click', function() {
        // Obtén el elemento que contiene la gráfica por su id único
        var chartContainer = document.getElementById('my-unique-chart');

        // Crea un elemento Canvas del mismo tamaño que el contenedor de la gráfica
        var canvas = document.createElement('canvas');
        canvas.width = chartContainer.offsetWidth;
        canvas.height = chartContainer.offsetHeight;

        // Dibuja la gráfica en el lienzo
        var context = canvas.getContext('2d');
        context.drawImage(chartContainer, 0, 0, canvas.width, canvas.height);

        // Crea una URL de datos para el lienzo (imagen en formato base64)
        var dataUrl = canvas.toDataURL('image/png');

        // Crea un enlace de descarga y establece la URL de datos como su origen
        var downloadLink = document.createElement('a');
        downloadLink.href = dataUrl;
        downloadLink.download = 'chart.png';
        downloadLink.textContent = 'Descargar la imagen';

        // Dispara el clic en el enlace de descarga para iniciar la descarga
        downloadLink.click();
    });
});
</script> --}}


<script>
    document.addEventListener('livewire:initialized', () => {

        document.getElementById('captureButton').addEventListener('click', function() {
        // Obtén el elemento que contiene la gráfica por su id único
        var chartContainer = document.getElementById('my-unique-chart');

        if (chartContainer) {
            // Crea un elemento Canvas del mismo tamaño que el contenedor de la gráfica
        var canvas = document.createElement('canvas');
        canvas.width = chartContainer.offsetWidth;
        canvas.height = chartContainer.offsetHeight;

        // Dibuja la gráfica en el lienzo
        var context = canvas.getContext('2d');
        context.drawImage(chartContainer, 0, 0, canvas.width, canvas.height);

        // Crea una URL de datos para el lienzo (imagen en formato base64)
        var dataUrl = canvas.toDataURL('image/png');

        // Crea un enlace de descarga y establece la URL de datos como su origen
        var downloadLink = document.createElement('a');
        downloadLink.href = dataUrl;
        downloadLink.download = 'chart.png';
        downloadLink.textContent = 'Descargar la imagen';

        // Dispara el clic en el enlace de descarga para iniciar la descarga
        downloadLink.click();
        } else {
            console.error("Elemento con id 'my-unique-chart' no encontrado.");
        }
    });
    });
</script>
