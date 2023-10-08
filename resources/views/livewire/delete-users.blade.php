<div>
    <button
        wire:click="$set('openModal', true)"
        class="w-6 h-6 mr-1 bg-red-500 text-white rounded">
        <x-icon name="x-mark" />
    </button>


    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title" class="font-extrabold text-xl">
            {{ __('Delete user') }}
        </x-slot>

        <x-slot name="content">
            <span class="font-extrabold text-xl">
                {{ $user->name }}
            </span>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('openModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-3" wire:click='delete({{ $user->id }})' wire:loading.attr="disabled">
                {{ __('Delete User') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
