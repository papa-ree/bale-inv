<?php

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use function Livewire\Volt\{title, mount};

title('INV | Stock Item');

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});
?>

<div>
    <x-bale.page-header title="Stock Item">
        <x-slot name="action">
            <div class="flex flex-row gap-x-3" x-data="{
                openDistributeItemModal() {
                    $wire.dispatch('openBaleModal', { id: 'distributeItemModal' });
                },
            }">
                <x-bale.secondary-button label="Distribute Item" type="button" @click="openDistributeItemModal" />

                <x-bale.secondary-button label="Opname Stock" type="button" link
                    href="{{ route('inventories.opname-stock') }}" />

                <x-bale.secondary-button label="Add Stock" type="button" link
                    href="{{ route('inventories.add-stock') }}" />

                <x-bale.button label="Add Stock Item" type="button" link
                    href="{{ route('inventories.create', 'new') }}" />
            </div>
        </x-slot>
    </x-bale.page-header>

    <livewire:inv.pages.inventory.section.inventory-table />

    {{-- distribution form modal --}}
    <x-bale.modal modalId="distributeItemModal" size="xl" staticBackdrop>
        <livewire:inv.pages.distribution.modal.distribution-form-modal />
    </x-bale.modal>
</div>
