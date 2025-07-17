<?php

// use function Livewire\Volt\{state};
use Livewire\Volt\Component;
use Livewire\Attributes\{Computed, On, Validate};
use Paparee\BaleInv\App\Models\Inventory;
use Paparee\BaleInv\App\Models\InventoryAssignment;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Database\Eloquent\Builder;
use Paparee\BaleCms\App\Traits\WithGlobalValidationHandler;

new class extends Component {
    use WithGlobalValidationHandler;

    public $item, $item_name, $contact_location, $assign_contact, $condition, $status, $note, $quantity;

    #[On('setItem')]
    public function setItem($itemId)
    {
        $this->item = Inventory::find($itemId);
        $this->item_name = $this->item->item_name;
    }

    #[Computed]
    public function availableItems()
    {
        return Inventory::where('stock', '>', 0)->orderBy('item_name')->get();
    }

    #[Computed]
    public function availableLocations()
    {
        return cache()->get('nawasara_instansi', collect());
    }

    #[Computed]
    public function availableConditions()
    {
        return InventoryAssignment::conditions();
    }

    #[Computed]
    public function availableStatuses()
    {
        return InventoryAssignment::statuses();
    }

    public function rules()
    {
        return ['item_name' => 'required', 'quantity' => 'required|integer', 'contact_location' => 'required|string', 'assign_contact' => 'required|string', 'condition' => 'required|string', 'status' => 'required|string'];
    }

    public function assign(LivewireAlert $alert)
    {
        // dd($this);
        $this->validate();

        // Validasi kuantitas terlebih dahulu
        if ($this->quantity <= 0) {
            $alert->title('Invalid quantity!')->position('top-end')->warning()->timer(4000)->toast()->show();

            $this->dispatch('message-failed');
            return;
        }

        // Cek ketersediaan stok
        if ($this->quantity > $this->item->stock) {
            $alert->title('Stock not available!')->position('top-end')->warning()->timer(4000)->toast()->show();

            $this->dispatch('message-failed');
            return;
        }

        $this->dispatch('disabling-button', params: true);
        DB::beginTransaction();

        try {
            // Distribusikan item di sini
            $this->item->distributeStock($this->quantity, $this->contact_location, $this->assign_contact, $this->condition, $this->status, $this->note);

            DB::commit();

            $this->dispatch('refresh-inventory-list');
            $this->dispatch('closeBaleModal', id: 'distributeItemModal');

            $alert->title('Item Distributed!')->position('top-end')->success()->toast()->show();
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());

            $alert->title('Something went wrong!')->position('top-end')->error()->toast()->show();

            $this->dispatch('message-failed');
        } finally {
            $this->dispatch('disabling-button', params: false);
        }
    }

    public function resetVal()
    {
        $this->reset();
        $this->resetValidation();
    }
};
?>

<div x-data="{
    itemName: '',
    init() {
        this.resetState();
    },
    resetState() {
        this.itemName = '';
        this.itemStock = '';
        this.itemUnit = '';
        this.itemSpec = '';
    },
    handleItemData(detail) {
        this.itemName = detail.itemData.item_name;
        this.itemStock = detail.itemData.stock;
        this.itemUnit = detail.itemData.unit;
        this.itemSpec = detail.itemData.specification;
    },
}" x-init="init()" @item-data.window="handleItemData($event.detail)">

    <form wire:submit='assign'>
        <div class="mb-4" x-show="itemName">
            <x-label value="Selected Item" />
            <span x-text="itemName"></span>
            <span x-text="itemStock"></span>
            <span x-text="itemUnit"></span>
            <span x-text="itemSpec"></span>
        </div>

        <div class="mb-4" x-show="!itemName">
            <x-bale.select-dropdown label="select item" x-data="{ itemName: $wire.entangle('item_name') }">
                <x-slot name="defaultValue">
                    <span x-text="itemName == null ? 'Open this select menu' : itemName"></span>
                </x-slot>
                @foreach ($this->availableItems as $item)
                    <label for="{{ $item->item_name }}"
                        class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                        wire:key="{{ $item->id }}" @click="title='{{ $item->item_name }}'">
                        <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $item->item_name }}
                            (Stock: {{ $item->stock }})
                        </span>
                        <input type="radio" name="item_name" wire:model='item_name' value="{{ $item->item_name }}"
                            class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                            id="{{ $item->item_name }}">
                    </label>
                @endforeach
            </x-bale.select-dropdown>
            <x-input-error for="item_name" />
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
                @foreach ($this->availableConditions as $key => $condition)
                    <label :for="id"
                        class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                        wire:key="{{ $key }}" @click="title='{{ $condition['name'] }}'">
                        <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $condition['name'] }}

                        </span>
                        <input type="radio" name="condition" wire:model='condition' value="{{ $condition['name'] }}"
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
                @foreach ($this->availableStatuses as $key => $status)
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

        <x-bale.modal-action>
            <x-bale.button label="assign" type="submit" class="ml-3" @click='$wire.assign(), useSpinner()' />
            <x-bale.secondary-button label="Cancel" type="button"
                wire:click="$dispatch('closeBaleModal', { id: 'distributeItemModal' }); $wire.resetVal()" />
        </x-bale.modal-action>
    </form>

</div>
