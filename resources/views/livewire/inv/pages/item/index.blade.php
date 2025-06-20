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
            <div class="flex flex-row gap-x-3">
                <x-bale.secondary-button label="Opname Stock" type="button" link
                    href="{{ route('items.opname-stock') }}" />
                <x-bale.secondary-button label="Add Stock" type="button" link href="{{ route('items.add-stock') }}" />
                <x-bale.button label="Add Item" type="button" link href="{{ route('items.create', 'new') }}" />
            </div>
        </x-slot>
    </x-bale.page-header>

    <livewire:inv.pages.item.section.item-table />
</div>
