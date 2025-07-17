<?php

use function Livewire\Volt\{title, mount, computed, state, rules};
use Illuminate\Support\Facades\DB;
use Paparee\BaleInv\App\Models\InventoryMasterItem;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\Rule;

state(['edit_mode' => false, 'item_name', 'item_type', 'item', 'stock', 'item_brand', 'is_consumable', 'specification', 'item_device_type', 'item_license_purpose']);
state(['availableCategories' => fn() => InventoryMasterItem::category()]);
title(fn() => $this->edit_mode ? 'INV | Edit Master Item' : 'INV | Add Master Item');

// $availableUsers = computed(function () {
//     return InventoryMasterItem::finf();
// });

mount(function ($item) {
    $this->item = InventoryMasterItem::find($item);

    if ($this->item) {
        $this->edit_mode = true;
        $this->item_name = $this->item->item_name;
        $this->item_type = $this->item->item_type;
        $this->item_brand = $this->item->item_brand;
        $this->is_device = $this->item->is_device;
        $this->is_consumable = $this->item->is_consumable;
        $this->specification = $this->item->specification;
        $this->item_device_type = $this->item->item_device_type;
        $this->item_license_purpose = $this->item->item_license_purpose;
    }
});

rules(
    fn() => [
        'item_name' => ['required', 'string', 'min:3', 'max:100', Rule::unique(Paparee\BaleInv\App\Models\InventoryMasterItem::class)->ignore($this->item)],
        // 'item_name' => 'required|string|min:3|max:100|unique:Paparee\BaleInv\App\Models\InventoryMasterItem,item_name' ,
        'item_type' => 'required|string',
        'item_brand' => 'required|string',
    ],
);

$store = function (LivewireAlert $alert) {
    // dd($this);
    // $this->authorize('user management');

    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $item = InventoryMasterItem::create([
            'item_name' => $this->item_name,
            'item_type' => $this->item_type,
            'item_brand' => $this->item_brand,
            'is_device' => $this->item_type == 'hardware' ? 1 : 0,
            'is_consumable' => $this->is_consumable ? 1 : 0,
            'specification' => $this->specification,
            'item_device_type' => $this->item_device_type,
            'item_license_purpose' => $this->item_license_purpose,
        ]);

        DB::commit();
        session()->flash('saved', [
            'title' => 'Master Item Added',
        ]);

        $this->dispatch('refresh-inventory-list');

        $this->redirect('master-items', navigate: true);
    } catch (\Throwable $th) {
        $this->dispatch('disabling-button', params: false);

        DB::rollBack();
        info($th->getMessage());
        $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
    }
};

$update = function (LivewireAlert $alert) {
    // dd($this);
    // $this->authorize('user management');

    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $this->item->update([
            'item_name' => $this->item_name,
            'item_type' => $this->item_type,
            'item_brand' => $this->item_brand,
            'is_device' => $this->item_type == 'hardware' ? 1 : 0,
            'is_consumable' => $this->is_consumable ? 1 : 0,
            'specification' => $this->specification,
            'item_device_type' => $this->item_device_type,
            'item_license_purpose' => $this->item_license_purpose,
        ]);

        DB::commit();
        session()->flash('saved', [
            'title' => 'Master Item Updated',
        ]);

        $this->dispatch('refresh-inventory-list');

        $this->redirect('master-items', navigate: true);
    } catch (\Throwable $th) {
        $this->dispatch('disabling-button', params: false);

        DB::rollBack();
        info($th->getMessage());
        $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
    }
};

?>

<div>
    <x-bale.back-breadcrumb :href="route('master-items.index')" label="Master item list" />

    <x-bale.page-container>
        <div class="text-xl font-semibold select-none">
            {{ $edit_mode ? 'Edit Master Item' : 'Add Master Item' }}
        </div>
        <form wire:submit='{{ $edit_mode ? 'update' : 'store' }}' class="mt-8" x-data="{ itemType: $wire.entangle('item_type') }">
            {{-- item name --}}
            <div class="mb-4">
                <x-bale.input type="text" class="sm:w-1/4" label="Item Name" wire:model="item_name" autocomplete="off"
                    name="item_name" autofocus />
                <x-input-error for="item_name" />
            </div>

            {{-- category --}}
            <div class="flex items-end mb-4 gap-x-3">
                <div class="sm:w-1/4">
                    <x-bale.select-dropdown label="select type">
                        <x-slot name="defaultValue">
                            <span x-text="itemType == null ? 'Open this select menu' : itemType"></span>
                        </x-slot>
                        @foreach ($availableCategories as $category)
                            {{-- {{ $category['name'] }} --}}
                            <label for="{{ $category['name'] }}"
                                class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                                wire:key="{{ $category['name'] }}" @click="title='{{ $category['name'] }}'">
                                <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $category['name'] }}</span>
                                <input type="radio" name="item_type" wire:model='item_type'
                                    value="{{ $category['name'] }}"
                                    class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                    id="{{ $category['name'] }}">
                            </label>
                        @endforeach
                    </x-bale.select-dropdown>
                    <x-input-error for="item_type" />
                </div>

                {{-- consumable --}}
                <div class="sm:w-1/4" x-show="itemType != 'software'" wire:transition>
                    <label for="is_consumable"
                        class="flex items-center justify-between px-4 py-3 transition duration-200 border border-gray-200 rounded-lg dark:border-gray-700 hover:dark:bg-gray-700 hover:bg-gray-100">
                        <div>
                            <h3 class="text-sm font-medium text-gray-800 dark:text-white">Consumable</h3>
                        </div>
                        <input id="is_consumable" type="checkbox" wire:model='is_consumable'
                            class="w-5 h-5 text-blue-500 transition duration-200 dark:bg-gray-900 form-checkbox rounded-xl">
                    </label>
                </div>
            </div>

            {{-- brand --}}
            <div class="mb-4">
                <x-bale.input type="text" label="Brand" wire:model="item_brand" autocomplete="off"
                    name="item_brand" />
                <x-input-error for="item_brand" />
            </div>

            {{-- specification --}}
            <div class="mb-4">
                <label for="specification" class="block w-full mb-2 text-sm font-medium dark:text-white">
                    Specification (Optionasl)
                </label>
                <textarea id="specification" wire:model="specification"
                    class="block w-full px-3 py-2 border-gray-200 rounded-lg sm:py-3 sm:px-4 sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                    rows="3" placeholder="Specification..." data-hs-textarea-auto-height=""></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500" id="specification">
                    Insert specification
                </p>

                <x-input-error for="specification" />
            </div>

            <div class="mb-4" x-show="itemType == 'hardware'" wire:transition>
                <x-bale.input type="text" label="Device Type" wire:model="item_device_type" autocomplete="off"
                    name="item_device_type" />
                <x-input-error for="item_device_type" />
            </div>

            <div class="mb-4" x-show="itemType == 'software'" wire:transition>
                <x-bale.input type="text" label="License purpose" wire:model="item_license_purpose"
                    autocomplete="off" name="item_license_purpose" />
                <x-input-error for="item_license_purpose" />
            </div>

            <x-bale.modal-action>
                <x-bale.button label="{{ $edit_mode ? 'Update' : 'Store' }}" type="submit" wire:dirty.attr="disabled"
                    wire:target='store, update' />
            </x-bale.modal-action>
        </form>
    </x-bale.page-container>
</div>
