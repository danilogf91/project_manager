<div>
    <button
        wire:click="openModal"
        class="w-6 h-6 mr-1 bg-green-500 text-white rounded">
        <x-icon name="document" />
    </button>


    <x-dialog-modal wire:model.live="viewModal">

        <x-slot name="title" class="font-extrabold text-xl">
                <input wire:click ="dataEvent" wire:key="{{ $imageKey }}"
                role="button"
                class="pointer text-stone-800 hover:text-stone-600 hover:underline"
                wire:model.live='pda_file' type="file" name="pda_file">
                {{-- {{ $project->id }} --}}
        </x-slot>

        <x-slot name="content">
            <span class="font-extrabold text-xl">
                <h1>PDA FILE UPLOAD</h1>
            </span>

            @error('pda_file')
                <div class="text-xl bg-red-100 border-l-4 border-red-500 text-red-700 p-4 w-full mx-auto">
                    <x-input-error for='pda_file'/>
                </div>
            @enderror

            <div wire:loading wire:target="saveData" class="text-xl bg-green-100 border-l-4 border-green-500 text-green-700 p-4 w-full mx-auto">
                Procesando...
            </div>


        </x-slot>

        <x-slot name="footer">
            <x-danger-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-danger-button>

            <x-button class="ml-3 {{ ($buttonState || !$pda_file) ? 'pointer-events-none opacity-50' : '' }}" wire:click='saveData'>
                {{ __('Save Data') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>

