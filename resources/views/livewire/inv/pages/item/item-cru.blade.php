<?php

use function Livewire\Volt\{title, mount, computed, state, rules};
use Illuminate\Support\Facades\DB;
use Paparee\BaleInv\App\Models\InventoryItem;
use Jantinnerezo\LivewireAlert\LivewireAlert;

state(['edit_mode' => false, 'inventory_item_name', 'inventory_item_type', 'item', 'stock', 'inventory_item_brand', 'inventory_item_device_type']);
state(['availableCategories' => fn() => InventoryItem::category()]);
title(fn() => $this->edit_mode ? 'INV | Edit Item' : 'INV | Add Item');

// $availableUsers = computed(function () {
//     return InventoryItem::finf();
// });

mount(function ($item) {
    $this->item = InventoryItem::find($item);

    if ($this->item) {
        $this->edit_mode = true;
        $this->inventory_item_name = $this->item->inventory_item_name;
        $this->inventory_item_type = $this->item->inventory_item_type;
    }
});

rules(
    fn() => [
        'inventory_item_name' => 'required|string|min:3|max:100|unique:Paparee\BaleInv\App\Models\InventoryItem,inventory_item_name',
        'inventory_item_type' => 'required|string',
        'stock' => 'required|integer',
        'inventory_item_brand' => 'required|string',
        'inventory_item_device_type' => 'required|string',
    ],
);

$store = function (LivewireAlert $alert) {
    // $this->authorize('user management');

    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $item = InventoryItem::create([
            'inventory_item_name' => $this->inventory_item_name,
            'inventory_item_type' => $this->inventory_item_type,
            'inventory_item_brand' => $this->inventory_item_brand,
            'is_device' => $this->inventory_item_type == 'hardware' ? 1 : 0,
            'inventory_item_device_type' => $this->inventory_item_device_type,
        ]);

        $item->setStock($this->stock, 'Initiate Stock');

        DB::commit();
        session()->flash('saved', [
            'title' => 'Item Added',
        ]);

        $this->redirect('items', navigate: true);
    } catch (\Throwable $th) {
        $this->dispatch('disabling-button', params: false);

        DB::rollBack();
        info($th->getMessage());
        $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
    }
};

?>

<div>
    {{-- @dd($availableCategories) --}}
    <x-bale.page-container>
        <div class="text-xl font-semibold">
            {{ $edit_mode ? 'Edit Item' : 'Add Item' }}
        </div>
        <form wire:submit='store' class="mt-8">
            <div class="mb-4">
                <x-bale.input type="text" label="Item Name" wire:model="inventory_item_name" autocomplete="off"
                    name="inventory_item_name" autofocus />
                <x-input-error for="inventory_item_name" />
            </div>
            <div class="mb-4">
                <x-bale.select-dropdown label="select type" x-data="{ itemType: $wire.entangle('inventory_item_type') }">
                    <x-slot name="defaultValue">
                        <span x-text="itemType == null ? 'Open this select menu' : itemType"></span>
                    </x-slot>
                    @foreach ($availableCategories as $category)
                        {{-- {{ $category['name'] }} --}}
                        <label for="{{ $category['name'] }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $category['name'] }}" @click="title='{{ $category['name'] }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $category['name'] }}</span>
                            <input type="radio" name="inventory_item_type" wire:model='inventory_item_type'
                                value="{{ $category['name'] }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $category['name'] }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="inventory_item_type" />
            </div>

            <div class="mb-4">
                <x-bale.input type="text" label="Stock" wire:model="stock" autocomplete="off" name="stock"
                    x-mask="99999999" />
                <x-input-error for="stock" />
            </div>

            <div class="mb-4">
                <x-bale.input type="text" label="Brand" wire:model="inventory_item_brand" autocomplete="off"
                    name="inventory_item_brand" />
                <x-input-error for="inventory_item_brand" />
            </div>

            <div class="mb-4">
                <x-bale.input type="text" label="Device Type" wire:model="inventory_item_device_type"
                    autocomplete="off" name="inventory_item_device_type" />
                <x-input-error for="inventory_item_device_type" />
            </div>

            <x-bale.modal-action>
                <x-bale.button label="{{ $edit_mode ? 'Update' : 'Store' }}" type="submit" wire:dirty.attr="disabled"
                    wire:target='store' />
            </x-bale.modal-action>
        </form>
    </x-bale.page-container>
</div>
