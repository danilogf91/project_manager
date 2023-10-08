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

    </div>

    <div class="rounded flex flex-col sm:flex-row gap-2 mt-2">

        <div class="w-full sm:w-1/4 bg-white rounded">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold"># Projects</span>
                <h3 class="text-xl font-semibold mt-2">{{ $projects }}</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/4 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Budgeted</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($budgeted, 0, ',', '.')}} $</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/4 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Booked</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($booked, 0, ',', '.')}} $</h3>
            </div>
        </div>

        <div class="w-full sm:w-1/4 bg-white rounded mb-2 md:mb-0">
            <div class="p-2 text-center"> <!-- Agregar text-center aquí -->
                <span class="text-2xl font-extrabold">Executed</span>
                <h3 class="text-xl font-semibold mt-2">{{ number_format($executed, 0, ',', '.') }} $</h3>
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

           <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-column-chart
                    key="{{ $columnChartModel->reactiveKey() }}"
                    :column-chart-model="$columnChartModel"
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
                    key="{{ $pieChartModel->reactiveKey() }}"
                    :pie-chart-model="$pieChartModel"
                />
          </div>
       </div>

        <div class="flex flex-col mt-2 md:flex-row gap-2">

            <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
                <livewire:livewire-column-chart
                    key="{{ $columnChartModel2->reactiveKey() }}"
                    :column-chart-model="$columnChartModel2"
                />
           </div>

           <div class="h-[30rem] shadow rounded p-4 border bg-white flex-1">
            <livewire:livewire-column-chart
                key="{{ $columnChartModel2->reactiveKey() }}"
                :column-chart-model="$columnChartModel2"
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
            <livewire:livewire-radar-chart
                key="{{ $radarChartModel->reactiveKey() }}"
                :radar-chart-model="$radarChartModel"
            />
      </div>
   </div>

</div>
