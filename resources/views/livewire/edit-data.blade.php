<div>
    <button
        wire:click="$set('openModal', true)"
        class="w-6 h-6 mr-1 bg-stone-500 text-white rounded">
        <x-icon name="pencil-square" />
    </button>

    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title" class="font-extrabold text-xl">
            {{ __('Edit Data') }}
        </x-slot>

        <x-slot name="content">
            <div class="px-4 text-xs grid grid-cols-1 md:grid-cols-3 gap-4 max-h-[450px] overflow-y-auto">
                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="area"
                        class="block text-sm font-medium"
                    >
                        Area
                    </label>
                    <input
                        wire:model.live="area"
                        type="text"
                        name="area"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='area'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="group_1"
                        class="block text-sm font-medium"
                    >
                        Group 1
                    </label>
                    <input
                        wire:model.live="group_1"
                        type="text"
                        name="group_1"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='group_1'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="group_2"
                        class="block text-sm font-medium"
                    >
                        Group 2
                    </label>
                    <input
                        wire:model.live="group_2"
                        type="text"
                        name="group_2"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='group_2'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="general_classification"
                        class="block text-sm font-medium"
                    >
                        General Classification
                    </label>
                    <input
                        wire:model.live="general_classification"
                        type="text"
                        name="general_classification"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='general_classification'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="item_type"
                        class="block text-sm font-medium"
                    >
                        Item Type
                    </label>
                    <input
                        wire:model.live="item_type"
                        type="text"
                        name="item_type"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='item_type'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="qty"
                        class="block text-sm font-medium"
                    >
                        Qty
                    </label>
                    <input
                        wire:model.live="qty"
                        type="number"
                        min="0"
                        name="qty"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='qty'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="unit_price"
                        class="block text-sm font-medium"
                    >
                        Unit Price
                    </label>
                    <input
                        wire:model.live="unit_price"
                        type="number"
                        min="0"
                        name="unit_price"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='unit_price'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="global_price"
                        class="block text-sm font-medium"
                    >
                        Global Price
                    </label>
                    <input
                        wire:model.live="global_price"
                        type="number"
                        disabled
                        min="0"
                        name="global_price"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='global_price'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="stage"
                        class="block text-sm font-medium"
                    >
                        Stage
                    </label>
                    <input
                        wire:model.live="stage"
                        type="text"
                        name="stage"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='stage'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="real_value"
                        class="block text-sm font-medium"
                    >
                        Real Value
                    </label>
                    <input
                        wire:model.live="real_value"
                        type="number"
                        min="0"
                        name="real_value"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='real_value'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="committed"
                        class="block text-sm font-medium"
                    >
                        Committed
                    </label>
                    <input
                        wire:model.live="committed"
                        type="number"
                        min="0"
                        name="committed"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='committed'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="percentage"
                        class="block text-sm font-medium"
                    >
                        Percentage
                    </label>
                    <input
                        wire:model.live="percentage"
                        type="number"
                        min="0"
                        max="100"
                        name="percentage"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='percentage'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="code"
                        class="block text-sm font-medium"
                    >
                        Code
                    </label>
                    <input
                        wire:model.live="code"
                        type="text"
                        name="code"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='code'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="order_no"
                        class="block text-sm font-medium"
                    >
                        ORDER NO
                    </label>
                    <input
                        wire:model.live="order_no"
                        type="text"
                        name="order_no"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='order_no'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label

                        htmlFor="input_num"
                        class="block text-sm font-medium"
                    >
                        IMPUT NUMBER
                    </label>
                    <input
                        wire:model.live="input_num"
                        type="text"
                        name="input_num"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='input_num'/>
                </div>

                {{-- <div class="col-span-3">
                    <label

                        htmlFor="observations"
                        class="block text-sm font-medium"
                    >
                        Observations
                    </label>
                    <input
                        wire:model.live="observations"
                        type="text"
                        name="observations"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    />
                    <x-input-error for='observations'/>
                </div> --}}

                <div class="col-span-3">
                    <label
                        htmlFor="observations"
                        class="block text-sm font-medium"
                    >
                        Observations
                    </label>
                    <textarea
                        wire:model.live="observations"
                        name="observations"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    ></textarea>
                    <x-input-error for='observations'/>
                </div>

                <div class="col-span-3">
                    <label
                        htmlFor="description"
                        class="block text-sm font-medium"
                    >
                        Description
                    </label>
                    <textarea
                        wire:model.live="description"
                        name="description"
                        class="mt-1 p-2 w-full border rounded disabled:opacity-50"
                    ></textarea>
                    <x-input-error for='description'/>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('openModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" wire:click='update({{ $projectId }})' wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
