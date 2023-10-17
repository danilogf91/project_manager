<div>
    <button
        wire:click="$set('openModal', true)"
        class="w-6 h-6 mr-1 bg-blue-600 text-white rounded">
        <x-icon name="inbox-arrow-down" />
    </button>

    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title" class="font-extrabold text-2xl italic">
            Delete or Download  {{ $project->pda_code }} file
        </x-slot>

        <x-slot name="content">
            <span class="font-extrabold text-xl">
                Download PDA File
            </span>

            <button
                wire:click="downloadPDA"
                class="w-8 h-8 mr-1 bg-green-500 text-white rounded">
                <x-icon name="circle-stack" />
            </button>

        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('openModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click='delete' wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
