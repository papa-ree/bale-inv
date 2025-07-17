<?php

use function Livewire\Volt\{title, mount, computed, state, rules};
use Illuminate\Support\Facades\DB;
use Paparee\BaleInv\App\Models\Inventory;
use Paparee\BaleInv\App\Models\InventoryMasterItem;
use Paparee\BaleInv\App\Models\InventoryMovement;
use Jantinnerezo\LivewireAlert\LivewireAlert;

state(['edit_mode' => false, 'item_name', 'item_type', 'inventory', 'stock', 'unit', 'item_brand', 'is_consumable', 'item_device_type']);
// state(['availableCategories' => fn() => Inventory::category()]);
title(fn() => $this->edit_mode ? 'INV | Edit Item' : 'INV | Add Item');

mount(function ($inventory) {
    $this->inventory = InventoryMasterItem::find($inventory);

    if ($this->inventory) {
        $this->edit_mode = true;
        $this->item_name = $this->inventory->item_name;
        $this->stock = $this->inventory->stock;
        $this->unit = $this->inventory->unit;
    }
});

$availableItems = computed(function () {
    return InventoryMasterItem::orderBy('item_name')->get();
});

rules(
    fn() => [
        'item_name' => 'required|string',
        'stock' => 'required|integer',
        'unit' => 'required|string',
    ],
);

$store = function (LivewireAlert $alert) {
    // $this->authorize('user management');

    $this->validate();

    $item = InventoryMasterItem::whereItemName($this->item_name)->first();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $inventory = Inventory::create([
            'item_name' => $item->item_name,
            'item_type' => $item->item_type,
            'item_brand' => $item->item_brand,
            'is_device' => $item->is_device,
            'is_consumable' => $item->is_consumable,
            'specification' => $item->specification,
            'item_device_type' => $item->item_device_type,
            'item_license_purpose' => $item->item_license_purpose,
            'stock' => $this->stock,
            'unit' => $this->unit,
        ]);

        $setStock = InventoryMovement::create([
            'inventory_id' => $inventory->id,
            'type' => 'opname',
            'direction' => 'in',
            'quantity' => $this->stock,
            'note' => 'Initiate Stock',
            'user_uuid' => Auth::user()->uuid,
        ]);

        // $inventory->refresh();
        // $inventory->setStock($this->stock, 'Initiate Stock');

        DB::commit();

        session()->flash('saved', [
            'title' => 'Stock Added',
        ]);

        $this->redirect('inventories', navigate: true);
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
        <div class="text-xl font-semibold select-none">
            {{ $edit_mode ? 'Edit Inventory Item' : 'Add Inventory Item' }}
        </div>
        <form wire:submit='store' class="mt-8">
            {{-- inventory name --}}
            <div class="mb-4 sm:w-1/2">
                <x-bale.select-dropdown label="select type" x-data="{ selectedItem: $wire.entangle('item_name') }">
                    <x-slot name="defaultValue">
                        <span x-text="selectedItem == null ? 'Open this select menu' : selectedItem"></span>
                    </x-slot>
                    @foreach ($this->availableItems as $item)
                        <label for="{{ $item->item_name }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $item->item_name }}" @click="selectedItem='{{ $item->item_name }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $item->item_name }}</span>
                            <input type="radio" name="item_type" wire:model='item_type' value="{{ $item->item_name }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $item->item_name }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="item_name" />
            </div>

            <div class="mb-4 sm:w-1/4">
                <x-bale.input type="text" class="" x-mask="9999999" label="Stock" wire:model="stock"
                    autocomplete="off" name="stock" />
                <x-input-error for="stock" />
            </div>

            <div class="mb-4 sm:w-1/4">
                <x-bale.input type="text" class="" label="Unit" wire:model="unit" autocomplete="off"
                    name="unit" />
                <x-input-error for="unit" />
            </div>

            <x-bale.modal-action>
                <x-bale.button label="{{ $edit_mode ? 'Update' : 'Store' }}" type="submit" wire:dirty.attr="disabled"
                    wire:target='store' />
            </x-bale.modal-action>
        </form>
    </x-bale.page-container>
</div>
