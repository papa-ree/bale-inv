<?php

use function Livewire\Volt\{title, mount, state, computed, rules};
use Paparee\BaleInv\App\Models\Inventory;
use Paparee\BaleInv\App\Models\InventoryItem;
use Paparee\BaleInv\App\Models\InventoryAssignment;
use Paparee\BaleInv\App\Services\SatkerService;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Database\Eloquent\Builder;

state(['item_name', 'contact_location', 'assign_contact', 'return_condition', 'status', 'note', 'item']);
state(['availableConditions' => fn() => InventoryAssignment::conditions()]);
state([
    'availableStatuses' => fn() => collect(InventoryAssignment::statuses())
        ->whereIn('name', ['available', 'missing'])
        ->values()
        ->all(),
]);

title('INV | Return Item');

mount(function ($item) {
    $this->item = InventoryAssignment::find($item);
    $this->item_name = $this->item->item_name;
    $this->quantity = $this->item->quantity;
    $this->contact_location = $this->item->contact_location;
    $this->assign_contact = $this->item->assign_contact;
});

rules(
    fn() => [
        'return_condition' => 'required|string',
        'status' => 'required|string',
        'note' => 'required|string',
    ],
);

$return = function (LivewireAlert $alert) {
    // dump($this->item->item_data);
    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $this->item->update([
            'contact_location' => 'Dinas Kominfo dan Statistik',
            'assign_contact' => 'Bidang Aptika (Infrastruktur)',
            'type' => 'return',
            'return_condition' => $this->return_condition,
            'returned_at' => now(),
            'is_returned' => true,
            'user_uuid' => Auth::user()->uuid,
            'status' => $this->status,
            'note' => $this->note,
        ]);

        if ($this->return_condition != 'damaged') {
            $this->item->item_data->returnItem($this->note);
        }

        $return_assign_log = new Inventory();

        $return_assign_log->assignItemLog($this->item->id, 'return', 'Dinas Kominfo dan Statistik', 'Bidang Aptika (Infrastruktur)', $this->return_condition, $this->status, $this->note, Auth::user()->uuid);

        DB::commit();

        $this->dispatch('refresh-inventory-list');

        session()->flash('saved', [
            'title' => 'Item Returned',
        ]);

        $this->redirect('it-inventories', navigate: true);
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
        <div class="text-lg font-semibold">Return {{ $item_name }} in {{ $assign_contact . ' ' . $contact_location }}
        </div>

        <form wire:submit='return' class="mt-6">

            <div class="mb-4">
                <x-bale.select-dropdown label="select return condition" x-data="{ conditions: $wire.entangle('return_condition') }">
                    <x-slot name="defaultValue">
                        <span x-text="conditions == null ? 'Open this select menu' : conditions"></span>
                    </x-slot>
                    @foreach ($availableConditions as $key => $condition)
                        <label for="{{ $key . 'condition' }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $key }}" @click="title='{{ $condition['name'] }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $condition['name'] }}

                            </span>
                            <input type="radio" name="condition" wire:model='return_condition'
                                value="{{ $condition['name'] }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $key . 'condition' }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="return_condition" />
            </div>

            <div class="mb-4">
                <x-bale.select-dropdown label="select return status" x-data="{ statuses: $wire.entangle('status') }">
                    <x-slot name="defaultValue">
                        <span x-text="statuses == null ? 'Open this select menu' : statuses"></span>
                    </x-slot>
                    @foreach ($availableStatuses as $key => $status)
                        <label for="{{ $key . 'status' }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $key }}" @click="title='{{ $status['name'] }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $status['name'] }}

                            </span>
                            <input type="radio" name="status" wire:model='status' value="{{ $status['name'] }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $key . 'status' }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="status" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.input type="text" wire:model='note' label="note" required />
                <x-input-error for="note" />
            </div>

            <x-bale.modal-action>
                <x-bale.button label="assign" type="submit" />
            </x-bale.modal-action>
        </form>
    </x-bale.page-container>
</div>
