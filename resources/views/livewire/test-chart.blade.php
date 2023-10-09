<div class="m-2">

    <div class="justify-center rounded flex flex-col md:flex-row gap-2">
        <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="yearSearch"
                name="yearSearch"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="all">
                    all
                </option>
                @foreach($years as $year)
                <option value="{{ $year }}">
                    {{ $year }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="stateSearch"
                name="stateSearch"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="all">
                    all
                </option>
                @foreach($stateProject as $state)
                <option value="{{ $state }}">
                    {{ $state }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="dollarOrEuro"
                name="dollarOrEuro"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="dollar">
                    Dollar $
                </option>
                <option value="euro">
                    Euro €
                </option>

            </select>
        </div> --}}



    </div>

    {{-- <table>
        <tr>
            <th>Clave</th>
            <th>Valor</th>
        </tr>
        @foreach($stateProject as $clave => $valor)
        <tr>
            <td>{{ $clave }}</td>
            <td>{{ $valor }}</td>
        </tr>
        @endforeach
    </table> --}}

    <div class="rounded flex flex-col sm:flex-row gap-2 mt-2">

        <div class="w-full sm:w-1/5 bg-white rounded">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold"># Projects</span>
                <h3 class="text-xl font-semibold mt-2">{{ $projects }}</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Budgeted</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($budgeted, 0, ',', '.')}} $</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Booked</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($booked, 0, ',', '.')}} $</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Executed</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($executed, 0, ',', '.') }} $</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Real</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($executed, 0, ',', '.') }} $</h3>
            </div>
        </div>

    </div>

        <div class="flex flex-col mt-2 md:flex-row gap-2">

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-column-chart
                    key="{{ $numProjectsGraph->reactiveKey() }}"
                    :column-chart-model="$numProjectsGraph"
                />
           </div>

           <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-column-chart
                    key="{{ $numProjectsGraphValues->reactiveKey() }}"
                    :column-chart-model="$numProjectsGraphValues"
                />
            </div>
       </div>

        <div class="flex flex-col mt-2 md:flex-row gap-2">

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-pie-chart
                    key="{{ $numProjectsPieGraphByInvestments->reactiveKey() }}"
                    :pie-chart-model="$numProjectsPieGraphByInvestments"
                />
            </div>

           <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-pie-chart
                    key="{{ $numProjectsPieGraphByInvestmentsValues->reactiveKey() }}"
                    :pie-chart-model="$numProjectsPieGraphByInvestmentsValues"
                />
          </div>
       </div>

        <div class="flex flex-col mt-2 md:flex-row gap-2">

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-column-chart
                    key="{{ $numProjectsByStateGraph->reactiveKey() }}"
                    :column-chart-model="$numProjectsByStateGraph"
                />
           </div>

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-column-chart
                    key="{{ $numProjectsByStateGraphValues->reactiveKey() }}"
                    :column-chart-model="$numProjectsByStateGraphValues"
                />
           </div>
       </div>


       <div class="flex flex-col mt-2 md:flex-row gap-2">

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-radar-chart
                    key="{{ $investmentTypeGraphValues->reactiveKey() }}"
                    :radar-chart-model="$investmentTypeGraphValues"
                />
            </div>

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-radar-chart
                    key="{{ $areaGraphValues->reactiveKey() }}"
                    :radar-chart-model="$areaGraphValues"
                />
            </div>
        </div>
   </div>

</div>
