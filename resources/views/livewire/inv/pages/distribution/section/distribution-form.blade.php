<?php

use function Livewire\Volt\{title, mount, state, computed, rules};
use Paparee\BaleInv\App\Models\InventoryItem;
use Paparee\BaleInv\App\Models\InventoryAssignment;
use Paparee\BaleInv\App\Services\SatkerService;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Database\Eloquent\Builder;

state(['inventory_item_name', 'contact_location', 'assign_contact', 'condition', 'status', 'note', 'quantity']);
state(['availableConditions' => fn() => InventoryAssignment::conditions()]);
state(['availableStatuses' => fn() => InventoryAssignment::statuses()]);

$availableItems = computed(function () {
    return InventoryItem::whereHas('inventory', function (Builder $query) {
        $query->where('stock', '>', 0);
    })
        ->orderBy('inventory_item_name')
        ->get();
});

$availableLocations = computed(function () {
    return cache()->get('nawasara_instansi', collect());
});

rules(
    fn() => [
        'inventory_item_name' => 'required',
        'quantity' => 'required|integer',
        'contact_location' => 'required|string',
        'assign_contact' => 'required|string',
        'condition' => 'required|string',
        'status' => 'required|string',
    ],
);

$assign = function (LivewireAlert $alert) {
    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $item = InventoryItem::whereInventoryItemName($this->inventory_item_name)->first();

        $item->distributeStock($this->quantity, $this->contact_location, $this->assign_contact, $this->condition, $this->status, $this->note);

        DB::commit();

        session()->flash('saved', [
            'title' => 'Item Added',
        ]);

        $this->redirect('distributions', navigate: true);
    } catch (\Throwable $th) {
        $this->dispatch('disabling-button', params: false);

        DB::rollBack();
        info($th->getMessage());
        $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
    }
};

?>

<div>
    <x-bale.page-container>
        <form wire:submit='assign'>
            <div class="mb-4">
                <x-bale.select-dropdown label="select item" x-data="{ itemName: $wire.entangle('inventory_item_name') }">
                    <x-slot name="defaultValue">
                        <span x-text="itemName == null ? 'Open this select menu' : itemName"></span>
                    </x-slot>
                    @foreach ($this->availableItems as $item)
                        {{-- {{ $item->inventory_item_name }} --}}
                        <label for="{{ $item->inventory_item_name }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $item->id }}" @click="title='{{ $item->inventory_item_name }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $item->inventory_item_name }}
                                (Stock: {{ $item->inventory->stock }})
                            </span>
                            <input type="radio" name="inventory_item_name" wire:model='inventory_item_name'
                                value="{{ $item->inventory_item_name }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $item->inventory_item_name }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="inventory_item_name" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.input type="text" x-mask="99999999" wire:model='quantity' label="quantity" />
                <x-input-error for="quantity" />
            </div>

            <div class="mb-4">
                <x-bale.select-dropdown label="select location" x-data="{ contactLocations: $wire.entangle('contact_location') }">
                    <x-slot name="defaultValue">
                        <span x-text="contactLocations == null ? 'Open this select menu' : contactLocations"></span>
                    </x-slot>
                    @foreach ($this->availableLocations as $key => $location)
                        <label for="{{ $key . $location['id'] }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $key . $location['id'] }}" @click="title='{{ $location['name'] }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $location['name'] }}

                            </span>
                            <input type="radio" name="contact_location" wire:model='contact_location'
                                value="{{ $location['name'] }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $key . $location['id'] }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="contact_location" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.input type="text" wire:model='assign_contact' label="Contact" />
                <x-input-error for="assign_contact" />
            </div>

            <div class="mb-4">
                <x-bale.select-dropdown label="select condition" x-data="{ id: $id('condition'), conditions: $wire.entangle('condition') }">
                    <x-slot name="defaultValue">
                        <span x-text="conditions == null ? 'Open this select menu' : conditions"></span>
                    </x-slot>
                    @foreach ($availableConditions as $key => $condition)
                        <label :for="id"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $key }}" @click="title='{{ $condition['name'] }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $condition['name'] }}

                            </span>
                            <input type="radio" name="condition" wire:model='condition'
                                value="{{ $condition['name'] }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                :id="id">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="condition" />
            </div>

            <div class="mb-4">
                <x-bale.select-dropdown label="select status" x-data="{ statuses: $wire.entangle('status') }">
                    <x-slot name="defaultValue">
                        <span x-text="statuses == null ? 'Open this select menu' : statuses"></span>
                    </x-slot>
                    @foreach ($availableStatuses as $key => $status)
                        <label for="{{ $key }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $key }}" @click="title='{{ $status['name'] }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $status['name'] }}

                            </span>
                            <input type="radio" name="status" wire:model='status' value="{{ $status['name'] }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $key }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="status" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.input type="text" wire:model='note' label="note" />
                <x-input-error for="note" />
            </div>

            <x-bale.button label="assign" type="submit" />
        </form>
    </x-bale.page-container>
</div>
