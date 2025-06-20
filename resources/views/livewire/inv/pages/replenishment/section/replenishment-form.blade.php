<?php

use function Livewire\Volt\{title, mount, state, computed, rules};
use Paparee\BaleInv\App\Models\InventoryItem;
use Paparee\BaleInv\App\Models\InventoryAssignment;
use Paparee\BaleInv\App\Services\SatkerService;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

state(['inventory_item_name', 'contact_location', 'assign_contact', 'condition', 'status', 'note', 'quantity']);
// state(['availableConditions' => fn() => InventoryAssignment::conditions()]);
// state(['availableStatuses' => fn() => InventoryAssignment::statuses()]);

// $availableItems = computed(function () {
//     return InventoryItem::with('inventory')->orderBy('inventory_item_name')->get();
// });

// $availableLocations = computed(function () {
//     return cache()->get('bale_inv_maps', collect());
// });

// rules(
//     fn() => [
//         'inventory_item_name' => 'required',
//         'quantity' => 'required|integer',
//         'contact_location' => 'required|string',
//         'assign_contact' => 'required|string',
//         'condition' => 'required|string',
//         'status' => 'required|string',
//     ],
// );

// $assign = function (LivewireAlert $alert) {
//     $this->validate();

//     DB::beginTransaction();

//     try {
//         $this->dispatch('disabling-button', params: true);

//         $item = InventoryItem::whereInventoryItemName($this->inventory_item_name)->first();

//         $item->distributeStock($this->quantity, $this->contact_location, $this->assign_contact, $this->condition, $this->status, $this->note);

//         DB::commit();

//         session()->flash('saved', [
//             'title' => 'Item Added',
//         ]);

//         $this->redirect('distributions', navigate: true);
//     } catch (\Throwable $th) {
//         $this->dispatch('disabling-button', params: false);

//         DB::rollBack();
//         info($th->getMessage());
//         $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
//     }
// };
?>

<div>
    <x-bale.page-container>
        <form wire:submit='assign'>




            <x-bale.button label="assign" type="submit" />
        </form>
    </x-bale.page-container>
</div>
