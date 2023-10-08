<div>
    <button
        wire:click="$set('openModal', true)"
        class="w-6 h-6 mr-1 bg-stone-500 text-white rounded">
        <x-icon name="pencil-square" />
    </button>

    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title" class="font-extrabold text-xl">
            {{ __('Edit user') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-3">
                    <label

                        htmlFor="name"
                        class="block text-sm font-medium"
                    >
                        Name
                    </label>
                    <input
                        wire:model.live="name"
                        type="text"
                        name="name"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='name'/>
                </div>
                <div class="col-span-3">
                    <label
                        htmlFor="pda_code"
                        class="block text-sm font-medium"
                    >
                        Email
                    </label>
                    <input
                        disabled
                        wire:model.live="email"
                        type="email"
                        name="email"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='email'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="rate"
                        class="block text-sm font-medium"
                    >
                        Role
                    </label>
                    <select
                        wire:model.live="is_admin"
                        name="is_admin"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50">
                    <option value="0">User</option>
                    <option value="1">Admin</option>
                </select>
                    <x-input-error for='is_admin'/>
                </div>
                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="state"
                        class="block text-sm font-medium"
                    >
                        User State
                    </label>
                    <select
                        wire:model.live="active"
                        name="active"
                        class="mt-1 p-2 w-full border rounded"
                    >
                        <option value="1">Enabled</option>
                        <option value="0">Disable</option>
                    </select>
                    <x-input-error for='active'/>
                </div>
                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="finish_date"
                        class="block text-sm font-medium"
                    >
                    Created at
                    </label>
                    <input
                        wire:model.live="created_at"
                        disabled
                        type="date"
                        name="created_at"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='created_at'/>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('openModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" wire:click='update({{ $user->id }})' wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
