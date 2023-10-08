<div>
@if ($active)
    <section class="mt-4">
        <div>
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex items-center justify-between d px-4 py-1">
                    <div class="flex items-center">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                wire:model.live.debounce.500ms='search'
                                type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                placeholder="Search" required="">
                            </div>

                            <button
                                wire:click="$set('search', '')"
                                class="w-6 h-6 ml-3 bg-red-500 hover:bg-red-400 text-white rounded">
                                <x-icon name="x-mark" />
                            </button>
                    </div>

                    <div class="py-4 px-3">
                            <div class="space-x-4 items-center mb-3">
                                <button
                                    wire:click="export"
                                    class="
                                     bg-green-500  border border-transparent rounded-md font-semibold text-xs text-white uppercase
                                     tracking-widest hover:bg-green-400 focus:bg-green-400 active:bg-green-600 focus:outline-none focus:ring-2
                                     focus:ring-green-700 focus:ring-offset-2 transition ease-in-out duration-150 p-1 flex items-center">
                                    Export <x-icon class="ml-1 w-8 h-8" name="circle-stack" />
                                </button>
                            </div>
                    </div>

                    <div class="py-4 px-3">
                        <div class="flex ">
                            <div class="flex space-x-4 items-center mb-3">
                                <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                                <select
                                    wire:model.live="perPage"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @if ($is_admin_user)
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">

                            <livewire:create-projects/>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th wire:click="setSortBy('id')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">id</th>
                                <th wire:click="setSortBy('name')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">name</th>
                                <th cope="col" class="px-2 py-1"></th>
                                <th wire:click="setSortBy('pda_code')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">pda code</th>
                                <th wire:click="setSortBy('rate')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">rate</th>
                                <th wire:click="setSortBy('state')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">state</th>
                                <th wire:click="setSortBy('investments')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">investments</th>
                                <th wire:click="setSortBy('justification')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">justification</th>
                                <th wire:click="setSortBy('start_date')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">start date</th>
                                <th wire:click="setSortBy('finish_date')" scope="col" class="cursor-pointer hover:bg-gray-200 px-2 py-1">finish date</th>
                                @if ($is_admin_user)
                                <th scope="col" class="px-2 py-1">
                                    <span class="sr-only">Actions</span>
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $projects as $project )

                            <tr wire:key="{{ $project->id }}" class="border-b dark:border-gray-700">
                                <th scope="row"
                                class="px-2 py-1 font-sm text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $project->id }}</th>
                                <th scope="row"
                                    class="px-2 py-1 font-sm text-gray-900 whitespace-nowrap dark:text-white">

                                    @if ($project->data_uploaded && $is_admin_user)
                                        <span role="button" class="pointer text-red-500 hover:text-red-600 hover:underline">
                                            <a href="{{ route('data', ['id' => $project->id]) }}">
                                                {{ $project->name }}
                                            </a>
                                        </span>
                                    @else
                                        <span>
                                            {{ $project->name }}
                                        </span>
                                    @endif
                                </th>

                                <td class="px-2 py-1">
                                    @if ($project->data_uploaded)
                                        <span role="button" class="pointer text-red-500 hover:text-red-600 hover:underline">
                                            <a href="/projects/{{ $project->id }}">
                                                Dashboard
                                            </a>
                                        </span>
                                    @else
                                        <span>
                                                No Data
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">{{ $project->pda_code }}</td>
                                <td class="px-2 py-1">{{ $project->rate }}</td>
                                <td class="px-2 py-1">{{ $project->state }}</td>
                                <td class="px-2 py-1">{{ $project->investments }}</td>
                                <td class="px-2 py-1">{{ $project->justification }}</td>
                                <td class="px-2 py-1">{{ $project->start_date }}</td>
                                <td class="px-2 py-1">{{ $project->finish_date }}</td>

                                @if ($is_admin_user)
                                    <td class="px-2 py-1 flex items-center justify-start">
                                        <livewire:edit-projects :key="$project->id" :project="$project" />
                                        <livewire:delete-projects :key="$project->id.$project->name" :project="$project" />

                                        @if (!$project->data_uploaded)
                                            <livewire:save-projects-data :key="$project->name.$project->id" :project="$project" />
                                        @else
                                            <livewire:delete-projects-data :key="$project->id.$project->name.$project->id" :project="$project" />
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="py-4 px-3">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </section>
@else
    @livewire('user-disabled')
@endif
</div>
