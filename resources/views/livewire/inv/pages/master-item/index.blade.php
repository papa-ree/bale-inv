<?php

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use function Livewire\Volt\{title, mount};

title('INV | Master Item');

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});
?>

<div>
    <x-bale.page-header title="Master Item">
        <x-slot name="action">
            <div class="flex flex-row gap-x-3">
                <x-bale.button label="Add Item" type="button" link href="{{ route('master-items.create', 'new') }}" />
            </div>
        </x-slot>
    </x-bale.page-header>

    <livewire:inv.pages.master-item.section.master-item-table />

    <x-bale.modal modalId="masterItemDeleteModal" size="xl" staticBackdrop>
        <livewire:inv.pages.master-item.modal.master-item-delete-confirmation-modal />
    </x-bale.modal>
</div>
