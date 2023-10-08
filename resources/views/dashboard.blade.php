<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

        <div class="mx-auto mt-1 sm:px-6 lg:px-8">
            <livewire:test-chart />
        </div>
</x-app-layout>
