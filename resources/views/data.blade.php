<x-app-layout>
        <div class="mx-auto sm:px-6 lg:px-8">
            <livewire:data-table :is_admin="$user->is_admin" :id="$id" :name="$name" :active="$user->active"/>
        </div>
</x-app-layout>
