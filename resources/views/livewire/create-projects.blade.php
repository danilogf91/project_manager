<div>
   <x-button wire:click="$set('openModal', true)" >Create project</x-button>

    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title" class="font-extrabold text-xl">
            {{ __('Create project') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="col-span-3">
                    <label
                        htmlFor="name"
                        class="block text-sm font-medium"
                    >
                        Project Name
                    </label>
                    <input
                        wire:model.live="name"
                        type="text"
                        name="name"
                        class="mt-1 p-2 w-full border rounded"
                    />
                    <x-input-error for='name'/>
                </div>

                <div class="col-span-3">
                    <label
                        htmlFor="pda_code"
                        class="block text-sm font-medium"
                    >
                        PDA code
                    </label>
                    <input
                        wire:model.live="pda_code"
                        type="text"
                        name="pda_code"
                        class="mt-1 p-2 w-full border rounded"
                    />
                    <x-input-error for='pda_code'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="rate"
                        class="block text-sm font-medium"
                    >
                        Rate $ to €
                    </label>
                    <input
                        wire:model.live="rate"
                        type="number"
                        min="0"
                        step="0.01"
                        name="rate"
                        class="mt-1 p-2 w-full border rounded"
                    />
                    <x-input-error for='rate'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="state"
                        class="block text-sm font-medium"
                    >
                        Project State
                    </label>
                    <select
                        wire:model.live="state"
                        name="state"
                        class="mt-1 p-2 w-full border rounded"
                    >
                        <option value="planification">
                            Planification
                        </option>
                        <option value="execution">Execution</option>
                        <option value="finished">Finished</option>
                    </select>
                    <x-input-error for='state'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="investments"
                        class="block text-sm font-medium"
                    >
                        Investments
                    </label>
                    <select
                        wire:model.live="investments"
                        name="investments"
                        class="mt-1 p-2 w-full border rounded"
                    >
                        <option value="innovation">Innovation</option>
                        <option value="efficiency_&_saving">
                            Efficiency & Saving
                        </option>
                        <option value="replacement_&_restructuring">
                            Replacement & Restructuring
                        </option>
                        <option value="quality_&_hygiene">
                            Quality & Hygiene
                        </option>
                        <option value="health_&_safety">
                            Health & Safety
                        </option>
                        <option value="environment">Environment</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="capacity_increase">
                            Capacity Increase
                        </option>
                    </select>
                    <x-input-error for='investments'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="justification"
                        class="block text-sm font-medium"
                    >
                        Justification
                    </label>
                    <select
                        wire:model.live="justification"
                        name="justification"
                        class="mt-1 p-2 w-full border rounded"
                    >
                        <option value="normal_capex">
                            Normal Capex
                        </option>
                        <option value="special_project">
                            Special Project
                        </option>
                    </select>
                    <x-input-error for='justification'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="start_date"
                        class="block text-sm font-medium"
                    >
                        Start Date
                    </label>
                    <input
                        wire:model.live="start_date"
                        type="date"
                        name="start_date"
                        class="mt-1 p-2 w-full border rounded"
                    />
                    <x-input-error for='start_date'/>
                </div>

                <div class="md:col-span-1 col-span-3">
                    <label
                        htmlFor="finish_date"
                        class="block text-sm font-medium"
                    >
                        Ending Date
                    </label>
                    <input
                        wire:model.live="finish_date"
                        type="date"
                        name="finish_date"
                        class="mt-1 p-2 w-full border rounded"
                    />
                    <x-input-error for='finish_date'/>
                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('openModal', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" wire:click='createProject' wire:loading.attr="disabled">
                {{ __('Create') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
