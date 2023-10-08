<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->name}}
        </h2>
    </x-slot>

        <div class="mx-auto sm:px-6 lg:px-8 ">
            <livewire:dashboard-projects :project="$project"/>
        </div>
</x-app-layout>
