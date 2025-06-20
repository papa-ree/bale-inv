<?php

use function Livewire\Volt\{title, mount, computed, state, rules};
use Illuminate\Support\Facades\DB;
use Paparee\BaleInv\App\Models\InventoryItem;
use Jantinnerezo\LivewireAlert\LivewireAlert;

state(['inventory_item_name', 'stock', 'note']);
title('INV | Add Stock');

rules(
    fn() => [
        'inventory_item_name' => 'required|string',
        'stock' => 'required|integer',
    ],
);

$availableItems = computed(function () {
    return InventoryItem::get();
});

$store = function (LivewireAlert $alert) {
    // $this->authorize('user management');

    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $item = InventoryItem::whereInventoryItemName($this->inventory_item_name)->first();

        $item->increaseStock($this->stock, $this->note ?? 'Increase stock by add stock form');

        DB::commit();
        session()->flash('saved', [
            'title' => 'Stock Added',
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
            {{ 'Add Stock' }}
        </div>
        <form wire:submit='store' class="mt-8">
            <div class="mb-4">
                <x-bale.select-dropdown label="select Item" x-data="{ itemName: $wire.entangle('inventory_item_name') }">
                    <x-slot name="defaultValue">
                        <span x-text="itemName == null ? 'Open this select menu' : itemName"></span>
                    </x-slot>
                    @foreach ($this->availableItems as $item)
                        <label for="{{ $item->id }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $item->inventory_item_name }}"
                            @click="title='{{ $item->inventory_item_name }}'">
                            <span
                                class="text-sm text-gray-500 dark:text-neutral-400">{{ $item->inventory_item_name }}</span>
                            <input type="radio" name="inventory_item_name" wire:model='inventory_item_name'
                                value="{{ $item->inventory_item_name }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $item->id }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="inventory_item_name" />
            </div>

            <div class="mb-4">
                <x-bale.input type="text" label="Stock" wire:model="stock" autocomplete="off" name="stock"
                    x-mask="99999999" />
                <x-input-error for="stock" />
            </div>

            <div class="mb-4">
                <x-bale.input type="text" label="Note (Optional)" wire:model="note" autocomplete="off"
                    name="note" />
                <x-input-error for="note" />
            </div>

            <x-bale.modal-action>
                <x-bale.button label="Add Stock" type="submit" wire:dirty.attr="disabled" wire:target='store' />
            </x-bale.modal-action>
        </form>
    </x-bale.page-container>
</div>
