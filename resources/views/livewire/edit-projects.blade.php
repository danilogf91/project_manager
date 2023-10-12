<div>
    <button
        wire:click="$set('openModal', true)"
        class="w-6 h-6 mr-1 bg-stone-500 text-white rounded">
        <x-icon name="pencil-square" />
    </button>

    <x-dialog-modal wire:model.live="openModal">
        <x-slot name="title" class="font-extrabold text-xl">
            {{ __('Edit project') }}
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

                <div class="col-span-3">


                    <label
                        htmlFor="classification_of_investments"
                        class="block text-sm font-medium"
                    >
                        Classification of Investments
                    </label>
                    <select
                        wire:model.live="classification_of_investments"
                        name="classification_of_investments"
                        class="mt-1 p-2 w-full border rounded"
                    >
                        <option value="Buildings">Buildings</option>
                        <option value="Furniture">Furniture</option>
                        <option value="General Install">General Install</option>
                        <option value="Land">Land</option>
                        <option value="Machines & Equipm">Machines & Equipm</option>
                        <option value="Office Hardware Software">Office Hardware Software</option>
                        <option value="Other">Other</option>
                        <option value="Vehicles">Vehicles</option>
                        <option value="Vessel & Fishing Equipment">Vessel & Fishing Equipment</option>
                        <option value="Warenhouse & Distrib">Warenhouse & Distrib</option>
                    </select>
                    <x-input-error for='classification_of_investments'/>
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
                        min={0}
                        step={0.01}
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
                        <option value="Planification">
                            Planification
                        </option>
                        <option value="Execution">Execution</option>
                        <option value="Finished">Finished</option>
                    </select>
                    <x-input-error for='planification'/>
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
                        <option value="Innovation">Innovation</option>
                        <option value="Efficiency & Saving">
                            Efficiency & Saving
                        </option>
                        <option value="Replacement & Restructuring">
                            Replacement & Restructuring
                        </option>
                        <option value="Quality & Hygiene">
                            Quality & Hygiene
                        </option>
                        <option value="Health & Safety">
                            Health & Safety
                        </option>
                        <option value="Environment">Environment</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Capacity Increase">
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
                        <option value="Normal Capex">
                            Normal Capex
                        </option>
                        <option value="Special Project">
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
                        wire:model="finish_date"
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

            <x-button class="ml-3" wire:click='update({{ $project->id }})' wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>
