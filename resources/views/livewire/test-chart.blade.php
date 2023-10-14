<div class="m-2">



@if ($showSpinner)

<div class="text-center">
    <div role="status">
        <svg aria-hidden="true" class="inline w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
        <span class="sr-only">Loading...</span>
    </div>
</div>
@endif

    <div class="justify-center rounded flex flex-col md:flex-row gap-2">
        <div class="md:col-span-1 col-span-3">
            <select
                wire:model.live="yearSearch"
                name="yearSearch"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="all">
                    All
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
                wire:model.live="typeOfProjectSearch"
                name="typeOfProjectSearch"
                class="text-center px-7 py-2 w-full border rounded"
            >
                <option value="all">
                    Type of Project
                </option>
                @foreach($typeOfProject as $state)
                <option value="{{ $state }}">
                    {{ $state }}
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
                    State
                </option>
                @foreach($stateProject as $state)
                <option value="{{ $state }}">
                    {{ $state }}
                </option>
                @endforeach
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

        <div class="md:col-span-1 col-span-3">
            <input type="number" wire:model.live="rateValue" class="rounded">
        </div>
    </div>

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
                <h3 class="text-xl font-semibold mt-2">{{ number_format($budgeted, 0, ',', '.')}} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Booked</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($booked, 0, ',', '.')}} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Executed</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($executed, 0, ',', '.') }} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/5 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Real (SAP)</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($real, 0, ',', '.') }} {{ ($dollarOrEuro === "dollar") ? "$": "€" }}</h3>
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
